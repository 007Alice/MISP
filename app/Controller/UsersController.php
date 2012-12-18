<?php
App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public $newkey;

	public $components = array('Acl','Security');	// TODO ACL, components

	public $paginate = array(
			'limit' => 60,
			'order' => array(
					'User.org' => 'ASC'
			)
	);

	public function beforeFilter() {
		parent::beforeFilter();

		// what pages are allowed for non-logged-in users
		$this->Auth->allow('login', 'logout');
	}

	public function isAuthorized($user) {
		// Admins can access everything
		if (parent::isAuthorized($user)) {
			return true;
		}
		// Do not allow admin routing
		if (isset($this->request->params['admin']) && true == $this->request->params['admin'])
			return false;
		// Only on own user for these actions
		if (in_array($this->action, array('view', 'edit', 'delete', 'resetauthkey'))) {
			$userid = $this->request->params['pass'][0];
			if ("me" == $userid ) return true;
			return ($userid === $this->Auth->user('id'));
		}
		// the other pages are allowed by logged in users
		return true;
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function view($id = null) {
		if ("me" == $id) $id = $this->Auth->user('id');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		// Only own profile verified by isAuthorized
		$this->set('user', Sanitize::clean($this->User->read(null, $id)));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function edit($id = null) {
		if ("me" == $id) $id = $this->Auth->user('id');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		// Only own profile verified by isAuthorized
		if ($this->request->is('post') || $this->request->is('put')) {
			// What fields should be saved (allowed to be saved)
			$fieldList = array('email', 'autoalert', 'gpgkey', 'nids_sid' );
			if ("" != $this->request->data['User']['password'])
				$fieldList[] = 'password';
			// Save the data
			if ($this->User->save($this->request->data, true ,$fieldList)) {
				$this->Session->setFlash(__('The profile has been updated'));
				$this->_refreshAuth();
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The profile could not be updated. Please, try again.'));
			}
		} else {
			$this->User->recursive = 0;
			$this->User->read(null, $id);
			$this->User->set('password', '');
			$this->request->data = Sanitize::clean($this->User->data);
		}
		// XXX ACL roles
		$roles = Sanitize::clean($this->User->Role->find('list'));
		$this->set(compact('roles'));
	}

		public function change_pw() {
		$id = $this->Auth->user('id');
		$this->User->id = $id;
		if ($this->request->is('post') || $this->request->is('put')) {
			// What fields should be saved (allowed to be saved)
			$fieldList[] = 'password';
			// Save the data
			if ($this->User->save($this->request->data, true ,$fieldList)) {
				$this->Session->setFlash(__('Password Changed.'));
				$this->User->saveField('email', $this->Auth->user('email'));
				$this->User->saveField('change_pw', 0);
				$this->_refreshAuth();
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The password could not be updated. Please, try again.'));
			}
		} else {
			$this->User->recursive = 0;
			$this->User->read(null, $id);
			$this->User->set('password', '');
			$this->request->data = Sanitize::clean($this->User->data);
		}
		// XXX ACL roles
		$this->extraLog("change_pw");
		$roles = Sanitize::clean($this->User->Role->find('list'));
		$this->set(compact('roles'));
	}
/**
 * delete method
 *
 * @param string $id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function delete($id = null) {
		if ("me" == $id) $id = $this->Auth->user('id');
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		//Replaced by isAuthorized
		//// Only own profile
		//if ($this->Auth->user('id') != $id) {
		//	throw new ForbiddenException('You are not authorized to delete this profile.');
		//}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', Sanitize::clean($this->paginate()));
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', Sanitize::clean($this->User->read(null, $id)));
		$temp = $this->User->field('invited_by');
		$this->set('user2', Sanitize::clean($this->User->read(null, $temp)));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			// set invited by
			$this->request->data['User']['invited_by'] = $this->Auth->user('id');
			$this->request->data['User']['change_pw']= 1;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				// reset auth key for a new user
				$this->set('authkey', $this->newkey);
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			// generate auth key for a new user
			$this->newkey = $this->User->generateAuthKey();
			$this->set('authkey', $this->newkey);
		}
		// XXX ACL roles
		$roles = Sanitize::clean($this->User->Role->find('list'));
		$this->set(compact('roles'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$fields = array();
			foreach (array_keys($this->request->data['User']) as $field) {
				if($field != 'password') array_push($fields, $field);
			}
			// TODO Audit, extraLog, fields get orig
			$fieldsOldValues = array();
			foreach ($fields as $field) {
				if($field != 'confirm_password') array_push($fieldsOldValues, $this->User->field($field));
				else array_push($fieldsOldValues, $this->User->field('password'));
			}
			// TODO Audit, extraLog, fields get orig END
			if ("" != $this->request->data['User']['password'])
				$fields[] = 'password';
			if ($this->User->save($this->request->data, true, $fields)) {
				// TODO Audit, extraLog, fields compare
				// newValues to array
				$fieldsNewValues = array();
				foreach ($fields as $field) {
					if ($field != 'confirm_password') {
						$newValue = $this->data['User'][$field];
						if (gettype($newValue) == 'array') {
							$newValueStr = '';
							$cP = 0;
							foreach ($newValue as $newValuePart) {
								if ($cP < 2) $newValueStr .= '-' . $newValuePart;
								else  $newValueStr = $newValuePart . $newValueStr;
								$cP++;
							}
							array_push($fieldsNewValues, $newValueStr);
						}
						else array_push($fieldsNewValues, $newValue);
					}
					else array_push($fieldsNewValues, $this->data['User']['password']);
				}
				// compare
				$fieldsResultStr = '';
				$c = 0;
				foreach ($fields as $field) {
					if (isset($fieldsOldValues[$c]) && $fieldsOldValues[$c] != $fieldsNewValues[$c]) {
						if($field != 'confirm_password') $fieldsResultStr = $fieldsResultStr . ', ' . $field . ' (' . $fieldsOldValues[$c] . ') => (' . $fieldsNewValues[$c] . ')';
					}
					$c++;
				}
				$fieldsResultStr = substr($fieldsResultStr, 2);
				$this->extraLog("edit", "user", $fieldsResultStr);	// TODO Audit, check: modify User
				// TODO Audit, extraLog, fields compare END
				$this->Session->setFlash(__('The user has been saved'));
				$this->_refreshAuth(); // in case we modify ourselves
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->User->recursive = 0;
			$this->User->read(null, $id);
			$this->User->set('password', '');
			$this->request->data = Sanitize::clean($this->User->data);

		}
		// TODO ACL CLEANUP combobox for orgs
		$orgIds = array('ADMIN', 'NCIRC', 'Other MOD');
		$orgIds = $this->_arrayToValuesIndexArray($orgIds);
		$this->set('orgIds', compact('orgIds'));
		// XXX ACL, Roles in Users
		$roles = Sanitize::clean($this->User->Role->find('list'));
		$this->set(compact('roles'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		$user = $this->User->read('email', $id);
		$fieldsDescrStr = 'User (' . $id . '): ' . $user['User']['email'];
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->extraLog("delete", $fieldsDescrStr, '');	// TODO Audit, check: modify User
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function login() {
		if ($this->Auth->login()) {
			$this->extraLog("login");	// TODO Audit, extraLog, check: customLog i.s.o. extraLog, no auth user?: $this->User->customLog('login', $this->Auth->user('id'), array('title' => '','user_id' => $this->Auth->user('id'),'email' => $this->Auth->user('email'),'org' => 'IN2'));
			$this->redirect($this->Auth->redirect());
		} else {
			// don't display authError before first login attempt
			if (str_replace("//","/",$this->webroot . $this->Session->read('Auth.redirect')) == $this->webroot && $this->Session->read('Message.auth.message') == $this->Auth->authError) {
				$this->Session->delete('Message.auth');
			}
			// don't display "invalid user" before first login attempt
			if($this->request->is('post')) $this->Session->setFlash(__('Invalid username or password, try again'));

		}
	}

	public function routeafterlogin() {
		// Terms and Conditions Page
		if (!$this->Auth->user('termsaccepted')) {
			$this->redirect(array('action' => 'terms'));
		}

		// News page
		$newNewsdate = new DateTime("2012-03-27");	// TODO general, fixed odd date??
		$newsdate = new DateTime($this->Auth->user('newsread'));
		if ($newNewsdate > $newsdate) {
			$this->redirect(array('action' => 'news'));
		}

		// Events list
		$this->redirect(array('controller' => 'events', 'action' => 'index'));
	}

	public function logout() {
		if ($this->Session->check('Auth.User')) { // TODO session, user is logged in, so ..
			$this->extraLog("logout");	// TODO Audit, extraLog, check: customLog i.s.o. extraLog, $this->User->customLog('logout', $this->Auth->user('id'), array());
		}
		$this->Session->setFlash(__('Good-Bye'));
		$this->redirect($this->Auth->logout());
	}

	public function resetauthkey($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true), 'default', array(), 'error');
			$this->redirect(array('action' => 'index'));
		}
		if ('me' == $id ) $id = $this->Auth->user('id');

		//Replaced by isAuthorized
		//// only allow reset key for own account, except for admins
		//if (!$this->_isAdmin() && $id != $this->Auth->user('id')) {
		//	throw new ForbiddenException('Not authorized to reset the key for this user');
		//}

		// reset the key
		$this->User->id = $id;
		$newkey = $this->User->generateAuthKey();
		$this->User->saveField('authkey', $newkey);
		$this->Session->setFlash(__('New authkey generated.', true));
		$this->_refreshAuth();
		$this->redirect($this->referer());
	}

	public function memberslist() {
		$this->loadModel('Attribute');
		$this->loadModel('Event');

		// Orglist
		$fields = array('User.org', 'count(User.id) as `num_members`');
		$params = array('recursive' => 0,
							'fields' => $fields,
							'group' => array('User.org'),
							'order' => array('User.org'),
		);
		$orgs = $this->User->find('all', $params);
		$this->set('orgs', Sanitize::clean($orgs));

		// What org posted what type of attribute
		$this->loadModel('Attribute');
		$fields = array('Event.org', 'Attribute.type', 'count(Attribute.type) as `num_types`');
		$params = array('recursive' => 0,
							'fields' => $fields,
							'group' => array('Attribute.type', 'Event.org'),
							'order' => array('Event.org', 'num_types DESC'),
		);
		$typesHistogram = Sanitize::clean($this->Attribute->find('all', $params));
		$this->set('typesHistogram', $typesHistogram);

		// Nice graphical histogram
		$this->loadModel('Attribute');
		$sigTypes = array_keys($this->Attribute->typeDefinitions);
		$replace = array('-', '|');
		$graphFields = '';
		foreach ($sigTypes as &$sigType) {
			if ($graphFields != "")  $graphFields .= ", ";
			$graphFields .= "'" . $sigType . "'";
		}
		$graphFields = str_replace($replace, "_", $graphFields);
		$this->set('graphFields', $graphFields);

		$graphData = array();
		$prevRowOrg = "";
		$i = -1;
		foreach ($typesHistogram as &$row) {
			if ($prevRowOrg != $row['Event']['org']) {
				$i++;
				$graphData[] = "";
				$prevRowOrg = $row['Event']['org'];
				$graphData[$i] .= "org: '" . $row['Event']['org'] . "'";
			}
			$graphData[$i] .= ', ' . str_replace($replace, "_", $row['Attribute']['type']) . ': ' . $row[0]['num_types'];
		}
		$this->set('graphData', $graphData);
	}

	public function terms() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('termsaccepted', true);

			$this->_refreshAuth();  // refresh auth info
			$this->Session->setFlash(__('You accepted the Terms and Conditions.'));
			$this->redirect(array('action' => 'routeafterlogin'));
		}
		$this->set('termsaccepted', $this->Auth->user('termsaccepted'));
	}

	public function news() {
		$this->User->id = $this->Auth->user('id');
		$this->User->saveField('newsread', date("Y-m-d"));
		$this->_refreshAuth();  // refresh auth info
	}

	public function extraLog($action = null, $description = null, $fieldsResult = null) {	// TODO move audit to AuditsController?
		// new data
		$userId = $this->Auth->user('id');
		$model = 'User';
		$modelId = $this->Auth->user('id');
		if ($action == 'login') {
			$description = "User (" . $this->Auth->user('id') . "): " . $this->data['User']['email'];
		} elseif ($action == 'logout') {
			$description = "User (" . $this->Auth->user('id') . "): " . $this->Auth->user('email');
		} elseif ($action == 'edit') {
			$description = "User (" . $this->User->id . "): " . $this->data['User']['email'];
		} elseif ($action == 'change_pw') {
			$description = "User (" . $this->User->id . "): " . $this->data['User']['email'];
			$fieldsResult = "Password changed.";
		}

		// query
		$this->Log = ClassRegistry::init('Log');
		$this->Log->create();
		$this->Log->save(array(
			'org' => $this->Auth->user('org'),
			'email' => $this->Auth->user('email'),
			'action' => $action,
			'title' => $description,
			'change' => $fieldsResult));

		// write to syslogd as well
		App::import('Lib', 'SysLog.SysLog');
		$syslog = new SysLog();
		if ($fieldsResult) $syslog->write('notice', $description . ' -- ' . $action . ' -- ' . $fieldsResult);
		else $syslog->write('notice', $description . ' -- ' . $action);
	}

/**
 * Used for fields_before and fields for audit
 *
 * @param $array
 */
	public function arrayCopy(array $array) {
		$result = array();
		foreach ($array as $key => $val) {
			if (is_array( $val)) {
				$result[$key] = arrayCopy($val);
			} elseif (is_object($val)) {
				$result[$key] = clone $val;
			} else {
				$result[$key] = $val;
			}
		}
		return $result;
	}

	public function setRoleId($fk = '2') { // TODO generateAllFor<FieldName>
		$params = array(
				'conditions' => array('User.role_id' => ''),
				'recursive' => 0,
				'fields' => array('User.id'),
		);
		$users = $this->User->find('all', $params);
		foreach ($users as $user) {
			$this->User->id = $user['User']['id'];
			$this->User->saveField('role_id', $fk);
		}
	}

/**
 * generateAllFor<FieldName>
 **/
 	public function generateAllFor($field) {
		parent::generateAllFor($field);
	}
}
