<?php

App::uses('AppController', 'Controller');

class ObjectReferencesController extends AppController {

	public $components = array('Security' ,'RequestHandler', 'Session');

	public $paginate = array(
			'limit' => 20,
			'order' => array(
					'ObjectReference.id' => 'desc'
			),
	);

  public function add($objectId, $targetId = false, $targetType = false) {
		if (Validation::uuid($objectId)) {
			$temp = $this->ObjectReference->MispObject->find('first', array(
				'recursive' => -1,
				'fields' => array('Object.id'),
				'conditions' => array('Object.uuid' => $id)
			));
			if (empty($temp)) throw new NotFoundException('Invalid Object');
			$objectId = $temp['Object']['id'];
		} else if (!is_numeric($objectId)) {
			throw new NotFoundException(__('Invalid object'));
		}
		$object = $this->ObjectReference->MispObject->find('first', array(
			'conditions' => array('Object.id' => $objectId),
			'recursive' => -1,
			'contain' => array(
				'Event' => array(
					'fields' => array('Event.id', 'Event.orgc_id')
				)
			)
		));
		if (!$this->userRole['perm_add']) {
			throw new MethodNotAllowedException('You don\'t have the required permissions to add object reference.');
		}
		if (empty($object) || (!$this->_isSiteAdmin() && $object['Event']['orgc_id'] != $this->Auth->user('orgc_id'))) {
			throw new MethodNotAllowedException('Invalid object.');
		}
		$this->set('objectId', $objectId);
		if ($this->request->is('post')) {
			$data = array();
			if (!isset($this->request->data['ObjectReference'])) {
				$this->request->data['ObjectReference'] = $this->request->data;
			}
			$referenced_type = 1;
			$target_object = $this->ObjectReference->MispObject->find('first', array(
				'conditions' => array('Object.uuid' => $this->request->data['ObjectReference']['uuid']),
				'recursive' => -1,
				'fields' => array('Object.id', 'Object.uuid', 'Object.event_id')
			));
			if (!empty($target_object)) {
				$referenced_id = $target_object['Object']['id'];
				if ($target_object['Object']['event_id'] != $object['Event']['id']) {
					throw new NotFoundException('Invalid target. Target has to be within the same event.');
				}
			} else {
				$target_attribute = $this->ObjectReference->MispObject->Attribute->find('first', array(
					'conditions' => array('Attribute.uuid' => $this->request->data['ObjectReference']['uuid']),
					'recursive' => -1,
					'fields' => array('Attribute.id', 'Attribute.uuid', 'Attribute.event_id')
				));
				if (empty($target_attribute)) {
					throw new NotFoundException('Invalid target.');
				}
				if ($target_attribute['Attribute']['event_id'] != $object['Event']['id']) {
					throw new NotFoundException('Invalid target. Target has to be within the same event.');
				}
				$referenced_id = $target_attribute['Attribute']['id'];
				$referenced_type = 0;
			}
			$data = array(
				'referenced_type' => $referenced_type,
				'referenced_id' => $referenced_id,
				'uuid' => $this->request->data['ObjectReference']['uuid'],
				'relationship_type' => !empty($this->request->data['ObjectReference']['relationship_type']) ? $this->request->data['ObjectReference']['relationship_type'] : '',
				'comment' => !empty($this->request->data['ObjectReference']['comment']) ? $this->request->data['ObjectReference']['comment'] : '',
				'event_id' => $object['Event']['id'],
				'object_id' => $objectId
			);
			$data['referenced_type'] = $referenced_type;
			$data['relationship_type'] = $this->request->data['ObjectReference']['relationship_type'];
			$data['uuid'] = $this->request->data['ObjectReference']['uuid'];
			$this->ObjectReference->create();
			$result = $this->ObjectReference->save(array('ObjectReference' => $data));
			if ($result) {
				if ($this->_isRest()) {
					$object = $this->ObjectReference->find("first", array(
						'recursive' => -1,
						'conditions' => array('ObjectReference' => $this->ObjectReference->id)
					));
					return $this->RestResponse->viewData($object, $this->response->type());
				} else if ($this->request->is('ajax')) {
					return new CakeResponse(array('body'=> json_encode(array('saved' => true, 'success' => 'Object reference added.')),'status'=>200, 'type' => 'json'));
				}
			} else {
				if ($this->_isRest()) {
					return $this->RestResponse->saveFailResponse('ObjectReferences', 'add', false, $this->ObjectReference->validationErrors, $this->response->type());
				} else if ($this->request->is('ajax')) {
					return new CakeResponse(array('body'=> json_encode(array('saved' => false, 'errors' => 'Object reference could not be added.')),'status'=>200, 'type' => 'json'));
				}
			}
		} else {
			if ($this->_isRest()) {
				return $this->RestResponse->describe('ObjectReferences', 'add', false, $this->response->type());
			} else {
				$event = $this->ObjectReference->MispObject->Event->find('first', array(
					'conditions' => array('Event.id' => $object['Event']['id']),
					'recursive' => -1,
					'fields' => array('Event.id'),
					'contain' => array(
						'Attribute' => array(
							'conditions' => array('Attribute.deleted' => 0, 'Attribute.object_id' => 0),
							'fields' => array('Attribute.id', 'Attribute.uuid', 'Attribute.type', 'Attribute.category', 'Attribute.value', 'Attribute.to_ids')
						),
						'Object' => array(
							'conditions' => array('Object.deleted' => 0),
							'conditions' => array('NOT' => array('Object.id' => $objectId)),
							'fields' => array('Object.id', 'Object.uuid', 'Object.name', 'Object.meta-category'),
							'Attribute' => array(
								'conditions' => array('Attribute.deleted' => 0),
								'fields' => array('Attribute.id', 'Attribute.uuid', 'Attribute.type', 'Attribute.category', 'Attribute.value', 'Attribute.to_ids')
							)
						)
					)
				));
				$toRearrange = array('Attribute', 'Object');
				foreach ($toRearrange as $d) {
					if (!empty($event[$d])) {
						$temp = array();
						foreach ($event[$d] as $data) {
							$temp[$data['uuid']] = $data;
						}
						$event[$d] = $temp;
					}
				}
				$this->set('event', $event);
				$this->set('objectId', $objectId);
				$this->layout = 'ajax';
				$this->render('ajax/add');
			}
		}

  }

  public function delete($id, $hard = false) {

  }

  public function view($id) {

  }
}
