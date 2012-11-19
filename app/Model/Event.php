<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property User $User
 * @property Attribute $Attribute
 */
class Event extends AppModel {

	public $name = 'Event';					// TODO general

	public $actsAs = array('SysLogLogable.SysLogLogable' => array(	// TODO Audit, logable
		'userModel' => 'User',
		'userKey' => 'user_id',
		'change' => 'full'
	));

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	public $virtualFields = array();

/**
 * Description field
 *
 * @var array
 */
	public $fieldDescriptions = array(
		'risk' => array('desc' => 'Risk levels: *low* means mass-malware, *medium* means APT malware, *high* means sophisticated APT malware or 0-day attack', 'formdesc' => 'Risk levels:<br/>low: mass-malware<br/>medium: APT malware<br/>high: sophisticated APT malware or 0-day attack'),
		'private' => array('desc' => 'This field tells if the event should be shared with other CyDefSIG servers'),
		'classification' => array('desc' => 'Set the Traffic Light Protocol classification. <ol><li><em>TLP:AMBER</em>- Share only within the organization on a need-to-know basis</li><li><em>TLP:GREEN:NeedToKnow</em>- Share within your constituency on the need-to-know basis.</li><li><em>TLP:GREEN</em>- Share within your constituency.</li></ol>'),
		'submittedfile' => array('desc' => 'GFI sandbox: export upload', 'formdesc' => 'GFI sandbox:<br/>export upload'),
	);

	public $riskDescriptions = array(
		'Undefined' => array('desc' => '*undefined* no risk', 'formdesc' => 'No risk'),
		'Low' => array('desc' => '*low* means mass-malware', 'formdesc' => 'Mass-malware'),
		'Medium' => array('desc' => '*medium* means APT malware', 'formdesc' => 'APT malware'),
		'High' => array('desc' => '*high* means sophisticated APT malware or 0-day attack', 'formdesc' => 'Sophisticated APT malware or 0-day attack')
	);

	public $distributionDescriptions = array(
		'Your organization only' => array('desc' => 'This field determines the current distribution of the even', 'formdesc' => "Only organization members will see the event"),
		'This server-only' => array('desc' => 'This field determines the current distribution of the even', 'formdesc' => "Every organisation on the server can see  this event"),
		'This Community-only' => array('desc' => 'This field determines the current distribution of the even', 'formdesc' => "Event visible to all on this and _allied_ CyDefSIG instances but will not be shared past it"), // former Community
		'Connected communities' => array('desc' => 'This field determines the current distribution of the even', 'formdesc' => "Event visible to CyDefSIG instances with more then two servers but will not be shared past it"),
		'All communities' => array('desc' => 'This field determines the current distribution of the even', 'formdesc' => "To be distributed to every connected CyDefSIG server"),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'org' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'date' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'risk' => array(
				'rule' => array('inList', array('Undefined', 'Low','Medium','High')),
				'message' => 'Options : Undefined, Low, Medium, High',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
		),
		'info' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'published' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'uuid' => array(
			'uuid' => array(
				'rule' => array('uuid'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'private' => array(
				'boolean' => array(
						'rule' => array('boolean'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
						'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
		),
		//'classification' => array(
		//		'rule' => array('inList', array('TLP:AMBER', 'TLP:GREEN:NeedToKnow', 'TLP:GREEN')),
		//		//'message' => 'Your custom message here',
		//		//'allowEmpty' => false,
		//		'required' => true,
		//		//'last' => false, // Stop validation after this rule
		//		//'on' => 'create', // Limit validation to 'create' or 'update' operations
		//),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		if ('true' == Configure::read('CyDefSIG.private')) {

			$this->virtualFields = Set::merge($this->virtualFields, array(
				'distribution' => 'IF (Event.private=true AND Event.cluster=false, "Your organization only", IF (Event.private=true AND Event.cluster=true, "This server-only", IF (Event.private=false AND Event.cluster=true, "This Community-only", IF (Event.communitie=true, "Connected communities" , "All communities"))))',
			));

			$this->fieldDescriptions = Set::merge($this->fieldDescriptions, array(
				'distribution' => array('desc' => 'This field determines the current distribution of the event', 'formdesc' => 'This field determines the current distribution of the event:<br/>Org - only organization memebers will see the event<br/>Community - event visible to all on this CyDefSIG instance but will not be shared past it</br>All - to be distributed to other connected CyDefSIG servers'),
			));

			$this->validate = Set::merge($this->validate,array(
				'cluster' => array(
					'boolean' => array(
						'rule' => array('boolean'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
						'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
				),
				'communitie' => array(
					'boolean' => array(
						'rule' => array('boolean'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
						'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
				),
				'distribution' => array(
					'rule' => array('inList', array("Your organization only", "This server-only", "This Community-only", "Connected communities", "All communities")),
						//'message' => 'Your custom message here',
						'allowEmpty' => false,
						'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			));
		}
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		//'Org' => array(
		//	'className' => 'Org',
		//	'foreignKey' => 'org',
		//	'conditions' => '',
		//	'fields' => '',
		//	'order' => ''
		//)
 		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Attribute' => array(
			'className' => 'Attribute',
			'foreignKey' => 'event_id',
			'dependent' => true,		 // cascade deletes
			'conditions' => '',
			'fields' => '',
			'order' => array('Attribute.category ASC', 'Attribute.type ASC'),
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function beforeDelete() {
		// delete event from the disk
		$this->read();  // first read the event from the db
		// FIXME secure this filesystem access/delete by not allowing to change directories or go outside of the directory container.
		// only delete the file if it exists
		$filepath = APP . "files" . DS . $this->data['Event']['id'];
		App::uses('Folder', 'Utility');
		$file = new Folder ($filepath);
		if (is_dir($filepath)) {
			if (!$this->destroyDir($filepath)) {
				throw new InternalErrorException('Delete of event file directory failed. Please report to administrator.');
			}
		}
	}

	public function destroyDir($dir) {
	if (!is_dir($dir) || is_link($dir)) return unlink($dir);
		foreach (scandir($dir) as $file) {
			if ($file == '.' || $file == '..') continue;
			if (!$this->destroyDir($dir . DS . $file)) {
				chmod($dir . DS . $file, 0777);
				if (!$this->destroyDir($dir . DS . $file)) return false;
			};
		}
		return rmdir($dir);
	}

	public function beforeValidate() {
		parent::beforeValidate();
		// generate UUID if it doesn't exist
		if (empty($this->data['Event']['uuid'])) {
			$this->data['Event']['uuid'] = String::uuid();
		}
	}

	public function massageData(&$data) {
		switch ($data['Event']['distribution']) {
			case 'Your organization only':
				$data['Event']['private'] = true;
				$data['Event']['cluster'] = false;
				$data['Event']['communitie'] = false;
				break;
			case 'This server-only': // TODO
				$data['Event']['private'] = true;
				$data['Event']['cluster'] = true;
				$data['Event']['communitie'] = false;
				break;
			case 'This Community-only':
				$data['Event']['private'] = false;
				$data['Event']['cluster'] = true;
				$data['Event']['communitie'] = false;
				break;
			case 'Connected communities': // TODO
				$data['Event']['private'] = false;
				$data['Event']['cluster'] = false;
				$data['Event']['communitie'] = true;
				break;
			case 'All communities':
				$data['Event']['private'] = false;
				$data['Event']['cluster'] = false;
				$data['Event']['communitie'] = false;
				break;
		}
		return $data;
	}

	public function isOwnedByOrg($eventid, $org) {
		return $this->field('id', array('id' => $eventid, 'org' => $org)) === $eventid;
	}

	public function getRelatedEvents() {
		// FIXME rewrite this to use the getRelatedAttributes function from the Attributes Model.
		// only this way the code will be consistent

		// first get a list of related event_ids
		// then do a single query to search for all the events with that id
		$relatedEventIds = Array();
		foreach ($this->data['Attribute'] as &$attribute) {
			if ($attribute['type'] == 'other') {
				continue;  // sigs of type 'other' should not be matched against the others
			}
			$conditions = array('Attribute.value =' => $attribute['value'], 'Attribute.type =' => $attribute['type']);
			$similarAttributes = $this->Attribute->find('all',array('conditions' => $conditions));
			foreach ($similarAttributes as &$similarAttribute) {
				if ($this->id == $similarAttribute['Attribute']['event_id']) {
					continue; // same as this event, not needed in the list
				}
				$relatedEventIds[] = $similarAttribute['Attribute']['event_id'];
			}
		}
		$conditions = array("Event.id" => $relatedEventIds);
		$relatedEvents = $this->find('all',
							array('conditions' => $conditions,
								'recursive' => 0,
								'order' => 'Event.date DESC',
								'fields' => 'Event.*'
								)
		);
		return $relatedEvents;
	}

/**
 * Clean up an Event Array that was received by an XML request.
 * The structure needs to be changed a little bit to be compatible with what CakePHP expects
 *
 * This function receives the reference of the variable, so no return is required as it directly
 * modifies the original data.
 *
 * @param &$data The reference to the variable
 */
	public function cleanupEventArrayFromXML(&$data) {
		// Workaround for different structure in XML/array than what CakePHP expects
		if (isset($data['Event']['Attribute']) && is_array($data['Event']['Attribute'])) {
			if (is_numeric(implode(array_keys($data['Event']['Attribute']), ''))) {
				// normal array of multiple Attributes
				$data['Attribute'] = $data['Event']['Attribute'];
			} else {
				// single attribute
				$data['Attribute'][0] = $data['Event']['Attribute'];
			}
		}
		unset($data['Event']['Attribute']);

		return $data;
	}

/**
 * Uploads the event and the associated Attributes to another Server
 * TODO move this to a component
 *
 * @return bool true if success, false or error message if failed
 */
	public function uploadEventToServer($event, $server, $HttpSocket=null) {
		if (true == $event['Event']['private']) { // never upload private events
			return "Event is private and non exportable";
		}

		$url = $server['Server']['url'];
		$authkey = $server['Server']['authkey'];
		if (null == $HttpSocket) {
			App::uses('HttpSocket', 'Network/Http');
			$HttpSocket = new HttpSocket();
		}
		$request = array(
				'header' => array(
						'Authorization' => $authkey,
						'Accept' => 'application/xml',
						'Content-Type' => 'application/xml',
						//'Connection' => 'keep-alive' // LATER followup cakephp ticket 2854 about this problem http://cakephp.lighthouseapp.com/projects/42648-cakephp/tickets/2854
				)
		);
		$uri = $url . '/events';

		// LATER try to do this using a separate EventsController and renderAs() function
		$xmlArray = array();
		// rearrange things to be compatible with the Xml::fromArray()
		$event['Event']['Attribute'] = $event['Attribute'];
		unset($event['Attribute']);

		// cleanup the array from things we do not want to expose
		//unset($event['Event']['org']);
		// remove value1 and value2 from the output
		foreach ($event['Event']['Attribute'] as $key => &$attribute) {
			// do not keep attributes that are private, nor cluster
			if (($attribute['private'] && !$attribute['cluster'] && !$attribute['communitie']) || ($attribute['private'] && $attribute['cluster'] && !$attribute['communitie'])) {
				unset($event['Event']['Attribute'][$key]);
				continue; // stop processing this
			}
			// Distribution, correct Community to Org only in Attribute
			if ($attribute['cluster'] && !$attribute['private']) {
				$attribute['private'] = true;
				$attribute['cluster'] = false;
				//$attribute['communitie'] = false;
				$attribute['distribution'] = 'Your organization only';
			}
			// Distribution, correct All to Community in Attribute
			if (!$attribute['cluster'] && !$attribute['private'] && $attribute['communitie']) {
				$attribute['cluster'] = true;
				$attribute['distribution'] = 'This Community-only';
			}
			// remove value1 and value2 from the output
			unset($attribute['value1']);
			unset($attribute['value2']);
			// also add the encoded attachment
			if ($this->Attribute->typeIsAttachment($attribute['type'])) {
				$encodedFile = $this->Attribute->base64EncodeAttachment($attribute);
				$attribute['data'] = $encodedFile;
			}
		}
		// Distribution, correct Community to Org only in Event
		if ($event['Event']['cluster'] && !$event['Event']['private']) {
			$event['Event']['private'] = true;
			$event['Event']['cluster'] = false;
			$event['Event']['distribution'] = 'Your organization only';
		}
		// Distribution, correct All to Community in Event
		if (!$event['Event']['cluster'] && !$event['Event']['private'] && $event['Event']['communitie']) {
			$event['Event']['cluster'] = true;
			$event['Event']['distribution'] = 'This Community-only';
		}

		// display the XML to the user
		$xmlArray['Event'][] = $event['Event'];
		$xmlObject = Xml::fromArray($xmlArray, array('format' => 'tags'));
		$eventsXml = $xmlObject->asXML();
		// do a REST POST request with the server
		$data = $eventsXml;
		// LATER validate HTTPS SSL certificate
		$this->Dns = ClassRegistry::init('Dns');
		if ($this->Dns->testipaddress(parse_url($uri, PHP_URL_HOST))) {
			// TODO NETWORK for now do not know how to catch the following..
			// TODO NETWORK No route to host
			$response = $HttpSocket->post($uri, $data, $request);
			if ($response->code == '200') {	// 200 (OK) + entity-action-result
				if ($response->isOk()) {
					return true;
				} else {
					try {
						// parse the XML response and keep the reason why it failed
						$xmlArray = Xml::toArray(Xml::build($response->body));
					} catch (XmlException $e) {
						return true;
					}
					if (strpos($xmlArray['response']['name'],"Event already exists")) {	// strpos, so i can piggyback some value if needed.
						return true;
					} else {
						return $xmlArray['response']['name'];
					}
				}
			} else { // response is not okay
				return false;
			}
		}
	}

/**
 * Deletes the event and the associated Attributes from another Server
 * TODO move this to a component
 *
 * @return bool true if success, error message if failed
 */
	public function deleteEventFromServer($uuid, $server, $HttpSocket=null) {
		// TODO private and delete(?)

		$url = $server['Server']['url'];
		$authkey = $server['Server']['authkey'];
		if (null == $HttpSocket) {
			App::uses('HttpSocket', 'Network/Http');
			$HttpSocket = new HttpSocket();
		}
		$request = array(
				'header' => array(
						'Authorization' => $authkey,
						'Accept' => 'application/xml',
						'Content-Type' => 'application/xml',
						//'Connection' => 'keep-alive' // LATER followup cakephp ticket 2854 about this problem http://cakephp.lighthouseapp.com/projects/42648-cakephp/tickets/2854
				)
		);
		$uri = $url . '/events/0?uuid=' . $uuid;

		// LATER validate HTTPS SSL certificate
		$this->Dns = ClassRegistry::init('Dns');
		if ($this->Dns->testipaddress(parse_url($uri, PHP_URL_HOST))) {
			// TODO NETWORK for now do not know how to catch the following..
			// TODO NETWORK No route to host
			$response = $HttpSocket->delete($uri, array(), $request);
			// TODO REST, DELETE, some responce needed
		}
	}

/**
 * Download a specific event from a Server
 * TODO move this to a component
 * @return array|NULL
 */
	public function downloadEventFromServer($eventId, $server, $HttpSocket=null) {
		$url = $server['Server']['url'];
		$authkey = $server['Server']['authkey'];
		if (null == $HttpSocket) {
			App::uses('HttpSocket', 'Network/Http');
			$HttpSocket = new HttpSocket();
		}
		$request = array(
				'header' => array(
						'Authorization' => $authkey,
						'Accept' => 'application/xml',
						'Content-Type' => 'application/xml',
						//'Connection' => 'keep-alive' // LATER followup cakephp ticket 2854 about this problem http://cakephp.lighthouseapp.com/projects/42648-cakephp/tickets/2854
				)
		);
		$uri = $url . '/events/' . $eventId;
		// LATER validate HTTPS SSL certificate
		$response = $HttpSocket->get($uri, $data = '', $request);
		if ($response->isOk()) {
			$xmlArray = Xml::toArray(Xml::build($response->body));
			return $xmlArray['response'];
		} else {
			// TODO parse the XML response and keep the reason why it failed
			return null;
		}
	}

/**
 * Get an array of event_ids that are present on the remote server
 * TODO move this to a component
 * @return array of event_ids
 */
	public function getEventIdsFromServer($server, $HttpSocket=null) {
		$url = $server['Server']['url'];
		$authkey = $server['Server']['authkey'];

		if (null == $HttpSocket) {
			App::uses('HttpSocket', 'Network/Http');
			$HttpSocket = new HttpSocket();
		}
		$request = array(
				'header' => array(
						'Authorization' => $authkey,
						'Accept' => 'application/xml',
						'Content-Type' => 'application/xml',
						//'Connection' => 'keep-alive' // LATER followup cakephp ticket 2854 about this problem http://cakephp.lighthouseapp.com/projects/42648-cakephp/tickets/2854
				)
		);
		$uri = $url . '/events/index/sort:id/direction:desc/limit:999'; // LATER verify if events are missing because we only selected the last 999
		$this->Dns = ClassRegistry::init('Dns');
		if ($this->Dns->testipaddress(parse_url($uri, PHP_URL_HOST))) {
			$response = $HttpSocket->get($uri, $data = '', $request);

			if ($response->isOk()) {
				//debug($response->body);
				$xml = Xml::build($response->body);
				$eventArray = Xml::toArray($xml);

				// correct $eventArray if just one event
				if (is_array($eventArray['response']['Event']) && isset($eventArray['response']['Event']['id'])) {
					$tmp = $eventArray['response']['Event'];
					unset($eventArray['response']['Event']);
					$eventArray['response']['Event'][0] = $tmp;
				}

				$eventIds = array();
				foreach ($eventArray['response']['Event'] as &$event) {
					if (1 != $event['published']) {
						continue; // do not keep non-published events
					}
					$eventIds[] = $event['id'];
				}
				return $eventIds;
			}
		}
		// error, so return null
		return null;
	}

}
