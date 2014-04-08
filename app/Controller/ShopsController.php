<?php 

App::uses('CakeEmail', 'Network/Email');
App::uses('AppController', 'Controller');

class ShopsController extends AppController {
	
	public $name = 'Shops';

	public $plugin = null;

	public $presetVars = true;

	protected function _reInitControllerName() {
		$name = substr(get_class($this), 0, -10);
		if ($this->name === null) {
			$this->name = $name;
		} elseif ($name !== $this->name) {
			$this->name = $name;
		}
	}

	protected function _pluginDot() {
		if (is_string($this->plugin)) {
			return $this->plugin . '.';
		}
		return $this->plugin;
	}

	protected function _setupComponents() {
		if ($this->_pluginLoaded('Search', false)) {
			$this->components[] = 'Search.Prg';
		}
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
		$this->set('model', $this->modelClass);
	}

	protected function _setupPagination() {
		$this->Paginator->settings = array(
			'limit' => 12,
			'conditions' => array(
				$this->modelClass . '.active' => 1,
				$this->modelClass . '.shop_expires <=' => date('Y-m-d H:i:s'),
			)
		);
	}

	protected function _setupAdminPagination() {
		$this->Paginator->settings = array(
			'limit' => 20,
			'order' => array(
				$this->modelClass . '.created' => 'desc'
			)
		);
	}

	public function index() {
		$this->set('shops', $this->Paginator->paginate($this->modelClass));
	}

	public function view($slug = null) {
		/*try {
			$this->set('user', $this->{$this->modelClass}->view($slug));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}*/
	}

	public function add() {
		if (!empty($this->request->data)) {
			$shop = $this->{$this->modelClass}->add($this->request->data);
			if ($shop !== false) {
				$this->Session->write('Auth.User.has_shop', 1);
				//$this->_sendVerificationEmail($this->User->data);
				$this->Session->setFlash(__d('users', 'Votre boutique a été crée. Elle sera active une fois vous que vous vous serez acquité des frais d\'oouverture.'), 'notif');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('users', 'Votre boutique ne peut être créee. Merci de réessaayez.'), 'notif', array('type' => 'danger'));
			}
		}

		$durations = Configure::read('Shops.duration');
		$this->set(compact('durations'));
	}

	public function edit() {
		try {
			$result = $this->Shop->edit($this->Auth->user('id'), $this->request->data);

			if ($result === true) {
				$this->Session->setFlash(__d('shops', 'Vos informations ont été sauvegardées.'), 'notif');

				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
	}

	public function admin_index() {
		/*$this->Prg->commonProcess();
		unset($this->{$this->modelClass}->validate['username']);
		unset($this->{$this->modelClass}->validate['email']);
		$this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

		if ($this->{$this->modelClass}->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = $parsedConditions;
		$this->set('users', $this->Paginator->paginate());*/
	}

	public function admin_view($id = null) {
		/*try {
			$user = $this->{$this->modelClass}->view($id, 'id');
		} catch (NotFoundException $e) {
			$this->Session->setFlash(__d('users', 'Invalid User.'));
			$this->redirect(array('action' => 'index'));
		}

		$this->set('user', $user);*/
	}

	public function admin_edit($userId = null) {
		/*try {
			$result = $this->{$this->modelClass}->edit($userId, $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('users', 'User saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->{$this->modelClass}->read(null, $userId);
		}
		$this->set('roles', Configure::read('Users.roles'));*/
	}

	public function admin_delete($userId = null) {
		/*if ($this->{$this->modelClass}->delete($userId)) {
			$this->Session->setFlash(__d('users', 'User deleted'));
		} else {
			$this->Session->setFlash(__d('users', 'Invalid User'));
		}

		$this->redirect(array('action' => 'index'));*/
	}

	public function admin_search() {
		//$this->search();
	}

	public function search() {
		/*$this->_pluginLoaded('Search');

		$searchTerm = '';
		$this->Prg->commonProcess($this->modelClass);

		$by = null;
		if (!empty($this->request->params['named']['search'])) {
			$searchTerm = $this->request->params['named']['search'];
			$by = 'any';
		}
		if (!empty($this->request->params['named']['username'])) {
			$searchTerm = $this->request->params['named']['username'];
			$by = 'username';
		}
		if (!empty($this->request->params['named']['email'])) {
			$searchTerm = $this->request->params['named']['email'];
			$by = 'email';
		}
		$this->request->data[$this->modelClass]['search'] = $searchTerm;

		$this->Paginator->settings = array(
			'search',
			'limit' => 12,
			'by' => $by,
			'search' => $searchTerm,
			'conditions' => array(
					'AND' => array(
						$this->modelClass . '.active' => 1,
						$this->modelClass . '.email_verified' => 1)));

		$this->set('users', $this->Paginator->paginate($this->modelClass));
		$this->set('searchTerm', $searchTerm);*/
	}

	protected function _sendVerificationEmail($userData, $options = array()) {
		$defaults = array(
			'from' => Configure::read('App.defaultEmail'),
			'subject' => __d('users', 'Account verification'),
			'template' => $this->_pluginDot() . 'account_verification',
			'layout' => 'default',
			'emailFormat' => CakeEmail::MESSAGE_TEXT
		);

		$options = array_merge($defaults, $options);

		$Email = $this->_getMailInstance();
		$Email->to($userData[$this->modelClass]['email'])
			->from($options['from'])
			->emailFormat($options['emailFormat'])
			->subject($options['subject'])
			->template($options['template'], $options['layout'])
			->viewVars(array(
			'model' => $this->modelClass,
				'user' => $userData
			))
			->send();
	}

	protected function _getMailInstance() {
		$emailConfig = Configure::read('Users.emailConfig');
		if ($emailConfig) {
			return new CakeEmail($emailConfig);
		} else {
			return new CakeEmail('default');
		}
	}

	public function deleteThumb($id = null){
		if (!$id) {
	        throw new NotFoundException(__d('shops', 'Boutique invalide.'));
	    }

	    if(in_array('Thumbnail', $this->Shop->Behaviors->loaded())) {
    		if($this->Shop->hasField('shop_thumb')) {
    			$file = $this->Shop->field(
	    			'shop_thumb',
    				array('id' => $id)
	    		);
	    		$info = pathinfo($file);
				foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg') as $v){
					unlink($v);
				}
				foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'.'.$info['extension']) as $v){
					unlink($v);
				}
				$this->Shop->id = $id;
				$this->Shop->saveField('shop_thumb', '', array('validate' => false, 'callbacks' => false));
		    	$this->Session->setFlash(__d('shops', 'Image supprimée.'), 'notif');
				$this->redirect($this->referer());
    		}
    	} else {
    		throw new CakeException(__d('thumbnail','Le model \'%s\' n\'a pas le comportement \'Thumbnail\'', $this->modelClass));
    	}
    }

}