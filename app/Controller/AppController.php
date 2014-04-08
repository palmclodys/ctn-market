<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $components = array(
		'DebugKit.Toolbar',
		'Auth',
		'Session',
		'Cookie',
		'Paginator',
		'RequestHandler',
	);

	public $helpers = array(
		'Html',
		'Form',
		'Session',
		'Time',
		'Text',
		'Date',
	);

	public function beforeFilter() {
		$this->Auth->authenticate = array(
			'Form' => array(
				'fields' => array(
					'username' => 'email',
					'password' => 'password'),
				'userModel' => 'User',
				'scope' => array(
					$this->modelClass . '.active' => 1,
					$this->modelClass . '.email_verified' => 1
				)
			)
		);
		
		$this->Auth->authorize = 'Controller';

		$this->Auth->authError = __('Vous devez vous être connectés ou disposer des droits necessaires pour accéder à cet adresse.');

		$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');

		$this->Auth->logoutRedirect = array('admin' => false, 'controller' => 'users', 'action' => 'login');

		$this->Auth->autoRedirect = false;

		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
            $this->layout = 'admin';
        }

		if ($this->request->is('ajax')) {
            Configure::write('debug', 0);
        }

	}

	public function beforeRender() {
	    $logged_user = $this->Auth->user();
	    $isAdmin = (bool) (($logged_user['is_admin']) == true);
	    $isPro = (bool) (($logged_user['type']) == 'pro');
	    $isPar = (bool) (($logged_user['type']) == 'par');
	    $enableFeatures = (bool) (($logged_user['enable_features']) == true);

	    $allowRegistration = Configure::read('Users.allowRegistration');
		$allowRegistration = (is_null($allowRegistration) ? true : $allowRegistration);

		$menu = array();
		if($this->layout == 'default') {
			$menu_items = ClassRegistry::init('Category')->generateCatMenu();
		}

	    $this->set(compact('logged_user', 'isAdmin', 'allowRegistration', 'menu_items'));

	    $this->response->disableCache();
	}

	public function isAuthorized($user = null) {
		if (empty($this->request->params['admin'])) {
			return true;
		}
		if (isset($this->request->params['admin'])) {
			return (bool)($user['role'] === 'admin');
		}
		return parent::isAuthorized($user);
	}

	public function canUploadMedias($model, $id) {
		return true;
	}

	protected function _getMailInstance() {
		$emailConfig = Configure::read('Users.emailConfig');
		if ($emailConfig) {
			return new CakeEmail($emailConfig);
		} else {
			return new CakeEmail('default');
		}
	}
}