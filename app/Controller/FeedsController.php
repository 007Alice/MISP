<?php
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

/**
 * Feedss Controller
 */
class FeedsController extends AppController {

	public $components = array('Security' ,'RequestHandler');	// XXX ACL component

	public $paginate = array(
			'limit' => 60,
			'recursive' => -1,
			'contain' => array(
			),
			'maxLimit' => 9999, // LATER we will bump here on a problem once we have more than 9999 events
			'order' => array(
					'Feed.url' => 'ASC'
			),
	);

	public $uses = array('Feed');

	public function beforeFilter() {
		parent::beforeFilter();
		if (!$this->_isSiteAdmin()) throw new MethodNotAllowedException('You don\'t have the required privileges to do that.');
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('feeds', $this->paginate());
	}
	
	public function view($feedId) {
		$feed = $this->Feed->find('first', array('conditions' => array('Feed.id' => $feedId)));
	}
	
	public function toggleEnabled($feedId) {
		
	}
	
	public function add() {
		if ($this->request->is('post')) {
			if (isset($this->request->data['Feed']['pull_rules'])) $this->request->data['Feed']['rules'] = $this->request->data['Feed']['pull_rules'];
			$result = $this->Feed->save($this->request->data);
			if ($result) {
				$this->Session->setFlash('Feed added.');
				$this->redirect(array('controller' => 'feeds', 'action' => 'index'));
			}
			else $this->Session->setFlash('Feed could not be added.');
		} else {
			
		}
	}
	
	public function edit($feedId) {
		$this->Feed->id = $feedId;
		if (!$this->Feed->exists()) throw new NotFoundException('Invalid feed.');
		$this->Feed->read();
		if ($this->request->is('post') || $this->request->is('put')) {
			if (isset($this->request->data['Feed']['pull_rules'])) $this->request->data['Feed']['rules'] = $this->request->data['Feed']['pull_rules'];
			$this->request->data['Feed']['id'] = $feedId;
			$fields = array('id', 'name', 'provider', 'enabled', 'rules', 'url');
			$feed = array();
			foreach ($fields as $field) $feed[$field] = $this->request->data['Feed'][$field];
			$result = $this->Feed->save($feed);
			if ($result) {
				$this->Session->setFlash('Feed updated.');
				$this->redirect(array('controller' => 'feeds', 'action' => 'index'));
			}
			else $this->Session->setFlash('Feed could not be updated.');
		} else {
			$this->request->data = $this->Feed->data;
			$this->request->data['Feed']['pull_rules'] = $this->request->data['Feed']['rules'];
		}
	}
	
	public function delete($feedId) {
		if (!$this->request->is('post')) throw new MethodNotAllowedException('This action requires a post request.');
		$this->Feed->id = $feedId;
		if (!$this->Feed->exists()) throw new NotFoundException('Invalid feed.');
		if ($this->Feed->delete($feedId)) $this->Session->setFlash('Feed deleted.');
		else $this->Session->setFlash('Feed could not be deleted.');
		$this->redirect(array('controller' => 'feeds', 'action' => 'index'));
	}
	
	public function fetchFromFeed($feedId) {
		$this->Feed->id = $feedId;
		if (!$this->Feed->exists()) throw new NotFoundException('Invalid feed.');
		App::uses('SyncTool', 'Tools');
		$syncTool = new SyncTool();
		$this->Feed->read();
		$HttpSocket = $syncTool->setupHttpSocketFeed($this->Feed->data);
		$actions = $this->Feed->getNewEventUuids($this->Feed->data, $HttpSocket);
		$result = $this->Feed->downloadFromFeed($actions, $this->Feed->data, $HttpSocket, $this->Auth->user());
		
		return new CakeResponse(array('body'=> json_encode(array('saved' => true, 'success' => 'Attribute added.')),'status'=>200));
	}
	
	public function previewIndex($feedId) {
		$this->Feed->id = $feedId;
		if (!$this->Feed->exists()) throw new NotFoundException('Invalid feed.');
		if (isset($this->passedArgs['pages'])) $currentPage = $this->passedArgs['pages'];
		else $currentPage = 1;
		$urlparams = '';
		$passedArgs = array();
		
		App::uses('SyncTool', 'Tools');
		$syncTool = new SyncTool();
		$this->Feed->read();
		$HttpSocket = $syncTool->setupHttpSocketFeed($this->Feed->data);
		$events = $this->Feed->getManifest($this->Feed->data, $HttpSocket);
		
		$pageCount = count($events);
		App::uses('CustomPaginationTool', 'Tools');
		$customPagination = new CustomPaginationTool();
		$params = $customPagination->createPaginationRules($events, $this->passedArgs, $this->alias);
		$this->params->params['paging'] = array($this->modelClass => $params);
		if (is_array($events)) $customPagination->truncateByPagination($events, $params);
		else ($events = array());
		
		$this->set('events', $events);
		$this->loadModel('Event');
		$threat_levels = $this->Event->ThreatLevel->find('all');
		$this->set('threatLevels', Set::combine($threat_levels, '{n}.ThreatLevel.id', '{n}.ThreatLevel.name'));
		$this->set('eventDescriptions', $this->Event->fieldDescriptions);
		$this->set('analysisLevels', $this->Event->analysisLevels);
		$this->set('distributionLevels', $this->Event->distributionLevels);
		$shortDist = array(0 => 'Organisation', 1 => 'Community', 2 => 'Connected', 3 => 'All', 4 => ' sharing Group');
		$this->set('shortDist', $shortDist);
		$this->set('id', $feedId);
		$this->set('urlparams', $urlparams);		
		$this->set('passedArgs', json_encode($passedArgs));
		$this->set('passedArgsArray', $passedArgs);
	}
	
}