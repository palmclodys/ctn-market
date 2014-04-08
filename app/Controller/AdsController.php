<?php 

App::uses('AppController', 'Controller');

class AdsController extends AppController {
	
	public $name = 'Ads';

	public $plugin = null;

	public $components = array();

	public $presetVars = true;

	public function isAuthorized($user = null) {
	    if ($this->action === 'add') {
	        return true;
	    }
	    if (in_array($this->action, array('edit', 'delete'))) {
	    	if (isset($this->request->params['pass']) && !empty($this->request->params['pass'])) {
	    		$adId = $this->request->params['pass'][0];
	    		if ($this->Ad->isOwnedBy($adId, $user['id'])) {
		            return true;
		        }
	    	}
	    }
	    return parent::isAuthorized($user);
	}

	public function __construct($request, $response) {
		$this->_setupComponents();
		parent::__construct($request, $response);
		$this->_reInitControllerName();
	}

	protected function _reInitControllerName() {
		$name = substr(get_class($this), 0, -10);
		if ($this->name === null) {
			$this->name = $name;
		} elseif ($name !== $this->name) {
			$this->name = $name;
		}
	}

	protected function _pluginLoaded($plugin, $exception = true) {
		$result = CakePlugin::loaded($plugin);
		if ($exception === true && $result === false) {
			throw new MissingPluginException(array('plugin' => $plugin));
		}
		return $result;
	}

	protected function _setupComponents() {
		if ($this->_pluginLoaded('Search', false)) {
			$this->components[] = 'Search.Prg';
		}
	}

	public function beforeRender() {
		parent::beforeRender();
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_setupAuth();
		$this->_setupPagination();

		$this->set('model', $this->modelClass);
	}

	protected function _setupAdminPagination() {
		$this->Paginator->settings = array(
			'limit' => 20,
			'order' => array(
				$this->modelClass . '.created' => 'desc'
			),
		);
	}

	protected function _setupPagination() {
		$this->Paginator->settings = array(
			'limit' => 20,
			'order' => array(
				$this->modelClass . '.created' => 'desc'
			),
		);
	}

	protected function _setupAuth() {
		$this->Auth->allow('index', 'view', 'abusive', 'suggest', 'contact_owner');
	}

	public function index() {
		$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['name']);

		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = array_merge($parsedConditions, array($this->modelClass . '.is_active' => 1));

		$this->Ad->contain(array('User', 'Thumb', 'Media'));

		$categories = $this->Ad->Category->generateList();
		array_unshift($categories, 'Choisissez une catégorie');

		$ads = $this->Paginator->paginate($this->modelClass);

		$this->set(compact('ads', 'categories'));
	}

	public function edit($id = null) {
		if (!empty($this->request->data)) {
			$ad = $this->Ad->add($this->_beforePostData($this->request->data));

			if ($ad !== false) {
				$this->Session->setFlash(__d('categories', 'Félicitations! Votre annonce a été soumise. Elle sera validée dans les prochaines 24 heures.'), 'notif');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('categories', 'Votre annonce ne peut être crée. Merci de réessaayez.'), 'notif', array('type' => 'danger'));
			}
		}

		if (!$id) {
			$id = $this->Ad->getDraftId(array('Ad.user_id' => $this->Auth->user('id')));
		}

		$this->request->data = $this->Ad->findById($id);
		if ($this->request->data[$this->modelClass]['is_active'] == -1) {
			if (in_array('Media', $this->Ad->getAssociated('hasMany'))) {

				$ids = $this->Ad->Media->find('all', array(
					'fields' => "DISTINCT Media.id",
					'order' => false,
					'recursive' => 0,
					'conditions' => array('Media.ref_id' => $id, 'Media.ref' => 'Ad'))
				);
				if (!empty($ids)) {
					$ids = Hash::extract($ids, "{n}.Media.id");
					foreach ($ids as $id) {
						$this->Ad->Media->delete($id);
					}
				}
			}
		}
		if ($this->request->data[$this->modelClass]['price'] == 0) {
			unset($this->request->data[$this->modelClass]['price']);
		}

		$categories = $this->Ad->Category->generateList();
		array_unshift($categories, 'Choisissez une catégorie');
		$types = Configure::read('Ads.types');
		$states = Configure::read('Ads.states');
		$durations = Configure::read('Ads.duration');
		$this->set(compact('categories', 'types', 'states', 'durations'));
	}

	protected function _beforePostData($postData = array()) {
		$postData[$this->modelClass]['ip_address'] = $this->get_client_ip();
		$postData[$this->modelClass]['enable_slug'] = 0;
		return $postData;
	}

	protected function get_client_ip() {
		return $this->request->clientIp(); 
	}

	public function view($id = null, $slug = null) {
		try {
			$this->set('ad', $this->{$this->modelClass}->view($id, $slug, array($this->modelClass . '.is_active' => 1)));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			$this->redirect(array('controller' => 'ads', 'action' => 'index'));
		}
	}

	public function viewUserAd() {
		try {
			$this->set('ads', $this->{$this->modelClass}->viewUserAd($this->Auth->user('id')));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			$this->redirect(array('controller' => 'ads', 'action' => 'index'));
		}
	}

	public function admin_index() {
		$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['name']);

		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = array_merge($parsedConditions, array($this->modelClass . '.is_active <>' => '-1'));

		$this->Ad->recursive = 0;

		$this->set('ads', $this->Paginator->paginate());
	}

	public function admin_news() {
		$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['name']);

		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = array_merge($parsedConditions, array(
			$this->modelClass . '.is_active' => 0,
			$this->modelClass . '.is_archived' => 0,
			$this->modelClass . '.is_closed' => 0,
		));

		$this->Ad->recursive = 0;

		$this->set('ads', $this->Paginator->paginate());
	}

	public function admin_available() {
		$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['name']);

		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = array_merge($parsedConditions, array(
			$this->modelClass . '.is_active' => 1,
			$this->modelClass . '.is_archived' => 0,
			$this->modelClass . '.is_closed' => 0,
			$this->modelClass . '.validated_date <=' => date('Y-m-d H:i:s'),
			$this->modelClass . '.closing_date >=' => date('Y-m-d H:i:s'),
		));

		$this->Ad->recursive = 0;

		$this->set('ads', $this->Paginator->paginate());
	}

	public function admin_closed() {
		$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['name']);

		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = array_merge($parsedConditions, array(
			$this->modelClass . '.is_active <>' => -1,
			$this->modelClass . '.is_closed' => 1
		));

		$this->Ad->recursive = 0;

		$this->set('ads', $this->Paginator->paginate());
	}

	public function admin_archived() {
		$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['name']);

		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = array_merge($parsedConditions, array(
			$this->modelClass . '.is_active <>' => -1,
			$this->modelClass . '.is_archived' => 1
		));

		$this->Ad->recursive = 0;

		$this->set('ads', $this->Paginator->paginate());
	}

	public function admin_view($id = null) {
		/*try {
			$user = $this->User->view($id, 'id');
		} catch (NotFoundException $e) {
			$this->Session->setFlash(__d('users', 'Invalid User.'));
			$this->redirect(array('action' => 'index'));
		}

		$this->set('user', $user);*/
	}

	public function admin_edit($id = null) {
		try {
			$result = $this->{$this->modelClass}->publish($id, $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('ads', 'Félicitations! L\'annonce a bien été validée.'), 'notif');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->{$this->modelClass}->read(null, $id);
		}

		$categories = $this->Ad->Category->generateList();
		array_unshift($categories, 'Choisissez une catégorie');

		$types = Configure::read('Ads.types');
		$states = Configure::read('Ads.states');
		$durations = Configure::read('Ads.duration');

		$this->set(compact('categories', 'types', 'states', 'durations'));
	}

	public function admin_delete($adsId = null) {
		if ($this->Ad->delete($adsId)) {
			$this->Session->setFlash(__d('ads', 'Annonce supprimée.'), 'notif');
		} else {
			$this->Session->setFlash(__d('ads', 'Annonce introuvable.'), 'notif', array('type' => 'danger'));
		}
		$this->redirect(array('action' => 'index'));
	}
}