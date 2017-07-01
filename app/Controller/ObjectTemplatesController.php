<?php

App::uses('AppController', 'Controller');

class ObjectTemplatesController extends AppController {
	public $components = array('Security' ,'RequestHandler', 'Session');

	public $paginate = array(
			'limit' => 60,
			'order' => array(
					'Object.id' => 'desc'
			),
			'contain' => array(
				'Organisation' => array('fields' => array('Organisation.id', 'Organisation.name', 'Organisation.uuid'))
			),
			'recursive' => -1
	);

/*
  public function add($eventId) {

  }

  public function edit($id) {

  }

  public function delete($id) {

  }
*/

	public function objectChoice() {
		$templates_raw = $this->ObjectTemplate->find('all', array(
			'recursive' => -1,
			'fields' => array('id', 'meta-category', 'name', 'description', 'org_id'),
			'contain' => array('Organisation.name')
		));
		$templates = array();
		foreach ($templates_raw as $k => $template) {
			unset($template['ObjectTemplate']['meta-category']);
			$template['ObjectTemplate']['org_name'] = $template['Organisation']['name'];
			$templates[$templates_raw[$k]['ObjectTemplate']['meta-category']][] = $template['ObjectTemplate'];
		}
		debug($templates);
		$this->set('templates', $templates);
	}

  public function view($id) {
		$params = array(
			'recursive' => -1,
			'contain' => array(
				'Organisation' => array('fields' => array('Organisation.id', 'Organisation.name', 'Organisation.uuid'))
			),
			'conditions' => array('ObjectTemplate.id' => $id)
		);
		if ($this->_isSiteAdmin()) {
				$params['contain']['User']= array('fields' => array('User.id', 'User.email'));
		}
		$objectTemplate = $this->ObjectTemplate->find('first', $params);
		if (empty($objectTemplate)) {
			throw new NotFoundException('Invalid object template');
		}
		if ($this->_isRest()) {
			return $this->RestResponse->viewData($objectTemplate, $this->response->type());
		} else {
			$this->set('id', $id);
			$this->set('template', $objectTemplate);
		}
  }

	public function viewElements($id, $context = 'all') {
		$elements = $this->ObjectTemplate->ObjectTemplateElement->find('all', array(
			'conditions' => array('ObjectTemplateElement.object_template_id' => $id)
		));
		$this->set('list', $elements);
		$this->layout = 'ajax';
		$this->render('ajax/view_elements');
	}

	public function index() {
		if ($this->_isRest()) {
			$rules = $this->paginate;
			unset($rules['limit']);
			unset($rules['order']);
			$objectTemplates = $this->ObjectTemplate->find('all', $rules);
			return $this->RestResponse->viewData($objectTemplates, $this->response->type());
		} else {
			$objectTemplates = $this->paginate();
			$this->set('list', $objectTemplates);
		}
	}

	public function update() {
		$result = $this->ObjectTemplate->update($this->Auth->user());
		$this->Log = ClassRegistry::init('Log');
		$fails = 0;
		$successes = 0;
		if (!empty($result)) {
			if (isset($result['success'])) {
				foreach ($result['success'] as $id => $success) {
					if (isset($success['old'])) $change = $success['name'] . ': updated from v' . $success['old'] . ' to v' . $success['new'];
					else $change = $success['name'] . ' v' . $success['new'] . ' installed';
					$this->Log->create();
					$this->Log->save(array(
							'org' => $this->Auth->user('Organisation')['name'],
							'model' => 'ObjectTemplate',
							'model_id' => $id,
							'email' => $this->Auth->user('email'),
							'action' => 'update',
							'user_id' => $this->Auth->user('id'),
							'title' => 'Object template updated',
							'change' => $change,
					));
					$successes++;
				}
			}
			if (isset($result['fails'])) {
				foreach ($result['fails'] as $id => $fail) {
					$this->Log->create();
					$this->Log->save(array(
							'org' => $this->Auth->user('Organisation')['name'],
							'model' => 'ObjectTemplate',
							'model_id' => $id,
							'email' => $this->Auth->user('email'),
							'action' => 'update',
							'user_id' => $this->Auth->user('id'),
							'title' => 'Object template failed to update',
							'change' => $fail['name'] . ' could not be installed/updated. Error: ' . $fail['fail'],
					));
					$fails++;
				}
			}
		} else {
			$this->Log->create();
			$this->Log->save(array(
					'org' => $this->Auth->user('Organisation')['name'],
					'model' => 'ObjectTemplate',
					'model_id' => 0,
					'email' => $this->Auth->user('email'),
					'action' => 'update',
					'user_id' => $this->Auth->user('id'),
					'title' => 'Object template update (nothing to update)',
					'change' => 'Executed an update of the Object Template library, but there was nothing to update.',
			));
		}
		if ($successes == 0 && $fails == 0) $this->Session->setFlash('All object templates are up to date already.');
		else if ($successes == 0) $this->Session->setFlash('Could not update any of the object templates');
		else {
			$message = 'Successfully updated ' . $successes . ' object templates.';
			if ($fails != 0) $message .= ' However, could not update ' . $fails . ' object templates.';
			$this->Session->setFlash($message);
		}
		$this->redirect(array('controller' => 'ObjectTemplates', 'action' => 'index'));

	}
}
