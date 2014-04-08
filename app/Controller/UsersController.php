<?php 

App::uses('CakeEmail', 'Network/Email');
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	
	public $name = 'Users';

	public $plugin = null;

	public $components = array(
		'RememberMe',
	);

	public $presetVars = true;

	public function isAuthorized($user = null) {
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

		if (!Configure::read('App.defaultEmail')) {
			Configure::write('App.defaultEmail', 'noreply@' . env('HTTP_HOST'));
		}
	}

	protected function _setupAdminPagination() {
		$this->Paginator->settings = array(
			'limit' => 20,
			'order' => array(
				$this->modelClass . '.created' => 'desc'
			)
		);
	}

	protected function _setupPagination() {
		$this->Paginator->settings = array(
			'limit' => 12,
			'conditions' => array(
				$this->modelClass . '.active' => 1,
				$this->modelClass . '.email_verified' => 1,
				$this->modelClass . '.role !=' => 'admin',
			)
		);
	}

	protected function _setupAuth() {
		if (Configure::read('Users.disableDefaultAuth') === true) {
			return;
		}

		$this->Auth->allow('index', 'register', 'reset', 'verify', 'logout', 'view', 'reset_password', 'login', 'resend_verification', 'checkUsername');

		if (!is_null(Configure::read('Users.allowRegistration')) && !Configure::read('Users.allowRegistration')) {
			$this->Auth->deny('add');
		}

		if ($this->request->action == 'register') {
			$this->Components->disable('Auth');
		}
	}

	public function register() {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__d('users', 'Vous êtes déjà inscrits et connectés!'), 'notif', array('type' => 'danger'));
			$this->redirect('/');
		}

		if (!empty($this->request->data)) {

			App::uses('UserListener', 'Event');

			$this->User->getEventManager()->attach(new UserListener());

			$user = $this->User->register($this->request->data);

			if ($user !== false) {
				//$this->_sendVerificationEmail($this->User->data);

				$this->Session->setFlash(__d('users', 'Votre compte a été crée. Vous devrez recevoir très prochainement un mail pour autentifier votre compte dans les 24 heures. Une fois authentifié, vous pourrez vous connecter.'), 'notif');
				$this->redirect(array('action' => 'login'));
			} else {
				unset($this->request->data[$this->modelClass]['password']);
				unset($this->request->data[$this->modelClass]['temppassword']);
				$this->Session->setFlash(__d('users', 'Votre compte ne peut être crée. Merci de réessaayez.'), 'notif', array('type' => 'danger'));
			}
		}

		$civilites = Configure::read('Users.civilites');
		$types = Configure::read('Users.types');

		$this->set(compact('civilites', 'types'));
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));

				if($this->Auth->user('role') == 'admin'){
					$this->Auth->loginRedirect = array('admin' => true, 'controller' => 'ads', 'action' => 'index');
				} else {
					$this->Auth->loginRedirect = array('admin' => false, 'controller' => 'ads', 'action' => 'index');
				}

				$this->Session->setFlash(sprintf(__d('users', '%s vous êtes connectés.'), $this->Auth->user('firstname') . ' ' .$this->Auth->user('lastname')), 'notif');

				if (!empty($this->request->data)) {
					$data = $this->request->data[$this->modelClass];
					if (empty($this->request->data[$this->modelClass]['remember_me'])) {
						$this->RememberMe->destroyCookie();
					} else {
						$this->_setCookie();
					}
				}

				if (empty($data[$this->modelClass]['return_to'])) {
					$data[$this->modelClass]['return_to'] = null;
				}

				// Checking for 2.3 but keeping a fallback for older versions
				if (method_exists($this->Auth, 'redirectUrl')) {
					$this->redirect($this->Auth->redirectUrl($data[$this->modelClass]['return_to']));
				} else {
					$this->redirect($this->Auth->redirect($data[$this->modelClass]['return_to']));
				}
			} else {
				unset($this->request->data[$this->modelClass]['password']);
				$this->Session->setFlash(__d('users', 'E-mail et/ou mot de passe incorrect(s) ou encore le compte n\'a pas encore été validé. Merci de réessaayez.'), 'notif', array('type' => 'danger'));
			}
		}
		if (isset($this->request->params['named']['return_to'])) {
			$this->set('return_to', urldecode($this->request->params['named']['return_to']));
		} else {
			$this->set('return_to', false);
		}

		if ($this->Auth->user()) {
            $this->Session->setFlash(__d('users', 'Vous êtes déjà connectés!'), 'notif', array('type' => 'danger'));
            $this->redirect($this->Auth->redirect());
        }

		$allowRegistration = Configure::read('Users.allowRegistration');
		$this->set('allowRegistration', (is_null($allowRegistration) ? true : $allowRegistration));
	}

	protected function _sendVerificationEmail($userData, $options = array()) {

		$defaults = array(
			'from' => Configure::read('App.defaultEmail'),
			'subject' => __d('users', 'Validation Compte Cakephp-App'),
			'template' => $userData[$this->modelClass]['type'] == 'pro' ? 'account_verification_pro' : 'account_verification_par',
			'layout' => 'default',
			'emailFormat' => 'html'
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

	public function edit() {
		try {
			$result = $this->User->edit($this->Auth->user('id'), $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('users', 'Vos informations ont été sauvegardées.'), 'notif');

				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->User->read(null, $this->Auth->user('id'));
		}

		$civilites = Configure::read('Users.civilites');
		$types = Configure::read('Users.types');

		$this->set(compact('civilites', 'types'));
	}

	protected function getUserForEditing($userId = null, $options = array()) {
		$defaults = array(
			'conditions' => array($this->modelClass . '.id' => $userId));
		$options = Set::merge($defaults, $options);

		$user = $this->User->find('first', $options);

		if (empty($user)) {
			throw new NotFoundException(__d('users', 'Utilisateur non trouvé.'));
		}

		return $user;
	}

	protected function _getMailInstance() {
		$emailConfig = Configure::read('Users.emailConfig');
		if ($emailConfig) {
			return new CakeEmail($emailConfig);
		} else {
			return new CakeEmail('default');
		}
	}

	public function reset_password($token = null, $user = null) {
		if (empty($token)) {
			$admin = false;
			if ($user) {
				$this->request->data = $user;
				$admin = true;
			}
			$this->_sendPasswordReset($admin);
		} else {
			$this->_resetPassword($token);
		}
	}

	protected function _sendPasswordReset($admin = null, $options = array()) {
		$defaults = array(
			'from' => Configure::read('App.defaultEmail'),
			'subject' => __d('users', 'Réinitialisation de mot de passe'),
			'template' => 'password_reset_request',
			'emailFormat' => 'html',
			'layout' => 'default'
		);

		$options = array_merge($defaults, $options);

		if (!empty($this->request->data)) {
			$user = $this->User->passwordReset($this->request->data);

			if (!empty($user)) {

				$Email = $this->_getMailInstance();
				$Email->to($user[$this->modelClass]['email'])
					->from($options['from'])
					->emailFormat($options['emailFormat'])
					->subject($options['subject'])
					->template($options['template'], $options['layout'])
					->viewVars(array(
					'model' => $this->modelClass,
					'user' => $this->User->data,
						'token' => $this->User->data[$this->modelClass]['password_token']))
					->send();

				if ($admin) {
					$this->Session->setFlash(sprintf(
						__d('users', 'Un email avec les instructions a été envoyé à %s pour réinitialiser son mot de passe.'),
						$user[$this->modelClass]['email']), 'notif');
					$this->redirect(array('action' => 'index', 'admin' => true));
				} else {
					$this->Session->setFlash(__d('users', 'Vous devrez recevoir un email avec les instructions pour réinitialiser votre mot de passe.'), 'notif');
					$this->redirect(array('action' => 'login'));
				}
			} else {
				$this->Session->setFlash(__d('users', 'Aucun utilsateur correspondant à cet email.'), 'notif', array('type' => 'danger'));
				$this->redirect($this->referer('/'));
				$this->redirect($this->referer('/'));
			}
		}
		$this->render('request_password_change');
	}

	protected function _resetPassword($token) {
		$user = $this->User->checkPasswordToken($token);
		if (empty($user)) {
			$this->Session->setFlash(__d('users', 'Jeton de réinitialisation de mot de passe invalide. Merci de réessayer.'), 'notif', array('type' => 'danger'));
			$this->redirect(array('action' => 'reset_password'));
		}

		if (!empty($this->request->data) && $this->User->resetPassword(Set::merge($user, $this->request->data))) {
			if ($this->RememberMe->cookieIsSet()) {
				$this->Session->setFlash(__d('users', 'Mot de passe modifié.'), 'notif');
				$this->_setCookie();
			} else {
				$this->Session->setFlash(__d('users', 'Mot de passe modifié, vous pouvez maintenant vous connecter avec votre nouveau mot de passe.'), 'notif');
				$this->redirect($this->Auth->loginAction);
			}
		}

		$this->set('token', $token);
	}

	public function verify($type = 'email', $token = null) {
		if ($type == 'reset') {
			$this->request_new_password($token);
		}

		try {
			$this->User->verifyEmail($token);
			$this->Session->setFlash(__d('users', 'Votre email a été validé.'), 'notif');
			return $this->redirect(array('action' => 'login'));
		} catch (RuntimeException $e) {
			$this->Session->setFlash($e->getMessage());
			return $this->redirect('/');
		}
	}

	public function request_new_password($token = null) {
		if (Configure::read('Users.sendPassword') !== true) {
			throw new NotFoundException();
		}

		$data = $this->User->verifyEmail($token);

		if (!$data) {
			$this->Session->setFlash(__d('users', 'L\'url à laquelle vous essayez d\'accéder n\'est plus valide.'), 'notif', array('type' => 'danger'));
			return $this->redirect('/');
		}

		$email = $data[$this->modelClass]['email'];
		unset($data[$this->modelClass]['email']);

		if ($this->User->save($data, array('validate' => false))) {
			$this->_sendNewPassword($data);
			$this->Session->setFlash(__d('users', 'Votre mot de passe a été envoyé à l\'adresse email associée à votre compte.'), 'notif');
			$this->redirect(array('action' => 'login'));
		}

		$this->Session->setFlash(__d('users', 'Erreur survenue. Merci de verifier votre email et réessayer.'), 'notif', array('type' => 'danger'));
		$this->redirect('/');
	}

	protected function _sendNewPassword($userData) {
		$Email = $this->_getMailInstance();
		$Email->from(Configure::read('App.defaultEmail'))
			->to($userData[$this->modelClass]['email'])
			->replyTo(Configure::read('App.defaultEmail'))
			->return(Configure::read('App.defaultEmail'))
			->subject(env('HTTP_HOST') . ' ' . __d('users', 'Réinitialisation de mot de passe'))
			->template('new_password')
			->viewVars(array(
				'model' => $this->modelClass,
				'userData' => $userData))
			->send();
	}

	public function resend_verification() {
		if ($this->request->is('post')) {
			try {
				if ($this->User->checkEmailVerification($this->request->data)) {
					$this->_sendVerificationEmail($this->User->data);
					$this->Session->setFlash(__d('users', 'The email was resent. Please check your inbox.'), 'notif');
					$this->redirect('login');
				} else {
					$this->Session->setFlash(__d('users', 'The email could not be sent. Please check errors.'), 'notif', array('type' => 'danger'));
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}
	}

	public function logout() {
		$user = $this->Auth->user();
		$this->Session->destroy();
		if (isset($_COOKIE[$this->Cookie->name])) {
			$this->Cookie->destroy();
		}
		$this->RememberMe->destroyCookie();
		$this->Session->setFlash(sprintf(__d('users', '%s vous êtes déconnectés.'), $user[$this->User->displayField]), 'notif');
		$this->redirect($this->Auth->logout());
	}

	public function change_password() {
		if ($this->request->is('post')) {
			$this->request->data[$this->modelClass]['id'] = $this->Auth->user('id');
			if ($this->User->changePassword($this->request->data)) {
				$this->Session->setFlash(__d('users', 'Mot de passe changé.'), 'notif');
				$this->RememberMe->destroyCookie();
				$this->redirect('/');
			} else {
				unset($this->request->data[$this->modelClass]['old_password']);
				unset($this->request->data[$this->modelClass]['new_password']);
				unset($this->request->data[$this->modelClass]['confirm_password']);
			}
		}
	}

	public function index() {

		/**
		* Penser à optimiser la requête contain => array()
		*/

		$this->set('users', $this->Paginator->paginate($this->modelClass));
	}

	public function view($id = null, $slug = null) {
		try {
			$this->set('user', $this->User->view($id, $slug));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'danger'));
			$this->redirect('/');
		}
	}

	public function admin_index() {
		$this->Prg->commonProcess();
		unset($this->User->validate['username']);
		unset($this->User->validate['email']);
		$this->User->data[$this->modelClass] = $this->passedArgs;

		if ($this->User->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->User->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = $parsedConditions;

		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

	public function admin_view($id = null) {
		try {
			$user = $this->User->view($id, 'id');
		} catch (NotFoundException $e) {
			$this->Session->setFlash(__d('users', 'Invalid User.'));
			$this->redirect(array('action' => 'index'));
		}

		$this->set('user', $user);
	}

	public function admin_add() {
		if (!empty($this->request->data)) {
			$this->request->data[$this->modelClass]['tos'] = true;
			$this->request->data[$this->modelClass]['email_verified'] = true;

			if ($this->User->add($this->request->data)) {
				$this->Session->setFlash(__d('users', 'L\'utilisateur a été sauvegardé.'), 'notif');
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('roles', Configure::read('Users.roles'));
	}

	public function admin_edit($userId = null) {
		try {
			$result = $this->User->edit($userId, $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('users', 'L\'utilisateur a été sauvegardé.'), 'notif');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'danger'));
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->User->read(null, $userId);
		}

		$civilites = Configure::read('Users.civilites');
		$types = Configure::read('Users.types');

		$this->set(compact('civilites', 'types'));
	}

	public function admin_delete($userId = null) {
		if ($this->User->delete($userId)) {
			$this->Session->setFlash(__d('users', 'Utilisateur supprimé.'), 'notif');
		} else {
			$this->Session->setFlash(__d('users', 'Utilisateur non trouvé.'), 'notif', array('type' => 'danger'));
		}

		$this->redirect(array('action' => 'index'));
	}


/* Partie supplémentaire à revoir */
	public function admin_search() {
		$this->search();
	}

	public function admin_change_password() {
		if ($this->request->is('post')) {
			$this->request->data[$this->modelClass]['id'] = $this->Auth->user('id');
			if ($this->User->changePassword($this->request->data)) {
				$this->Session->setFlash(__d('users', 'Mot de passe changé.'), 'notif');
				$this->RememberMe->destroyCookie();
				$this->redirect('/');
			} else {
				unset($this->request->data[$this->modelClass]['old_password']);
				unset($this->request->data[$this->modelClass]['new_password']);
				unset($this->request->data[$this->modelClass]['confirm_password']);
			}
		}
	}

	public function search() {
		$this->_pluginLoaded('Search');

		$searchTerm = '';
		$this->Prg->commonProcess($this->modelClass);

		$by = null;
		if (!empty($this->request->params['named']['search'])) {
			$searchTerm = $this->request->params['named']['search'];
			$by = 'any';
		}
		if (!empty($this->request->params['named']['firstname'])) {
			$searchTerm = $this->request->params['named']['firstname'];
			$by = 'firstname';
		}
		if (!empty($this->request->params['named']['lastname'])) {
			$searchTerm = $this->request->params['named']['lastname'];
			$by = 'lastname';
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
		$this->set('searchTerm', $searchTerm);
	}

	protected function _setLanguages($viewVar = 'languages') {
		$this->_pluginLoaded('Utils');

		$Languages = new Languages();
		$this->set($viewVar, $Languages->lists('locale'));
	}
}