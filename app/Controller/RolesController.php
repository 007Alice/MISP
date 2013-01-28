<?php

App::uses('AppController', 'Controller');

/**
 * Roles Controller
 *
 * @property Role $Role
 */
class RolesController extends AppController {

	public $options = array('0' => 'Read Only', '1' => 'Manage My Own Events', '2' => 'Manage Organization Events', '3' => 'Manage &amp; Publish Organization Events');

	public $components = array(
		'Acl',
		'Auth' => array(
			'authorize' => array(
				'Actions' => array('actionPath' => 'controllers/Roles')
			)
		),
		'Security',
		'Session', 'AdminCrud' // => array('fields' => array('name'))
	);

	public $helpers = array('Js' => array('Jquery'));

	public $paginate = array(
			'limit' => 60,
			'order' => array(
					'Role.name' => 'ASC'
			)
	);

	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 *
 * @throws NotFoundException
 */
	public function view($id = null) {
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid role'));
		}
		$this->set('role', $this->Role->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if($this->Auth->User['User']['org'] != 'ADMIN') $this->redirect(array('controller' => 'roles', 'action' => 'index', 'admin' => false));
		$this->AdminCrud->adminAdd();
		$this->set('options', $this->options);
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		if($this->Auth->User['User']['org'] != 'ADMIN') $this->redirect(array('controller' => 'roles', 'action' => 'index', 'admin' => false));
		$this->AdminCrud->adminIndex();
		$this->set('options', $this->options);
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function admin_edit($id = null) {
		if($this->Auth->User['User']['org'] != 'ADMIN') $this->redirect(array('controller' => 'roles', 'action' => 'index', 'admin' => false));
		$this->AdminCrud->adminEdit($id);
		$this->set('options', $this->options);
	}

/**
 * admin_delete method
 *
 * @param string $id
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 *
 * @return void
 */
	public function admin_delete($id = null) {
		$this->AdminCrud->adminDelete($id);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->recursive = 0;
		$this->set('list', Sanitize::clean($this->paginate()));
		$this->set('options', $this->options);
	}
}
