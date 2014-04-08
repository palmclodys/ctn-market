<?php

App::uses('AppModel', 'Model');

class User extends AppModel {
	
	public $name = 'User';

	public $findMethods = array(
		'search' => true
	);

	public $filterArgs = array(
		'firstname' => array('type' => 'like'),
		'lastname' => array('type' => 'like'),
		'email' => array('type' => 'value')
	);

	public $displayField = 'firstname';

	public $emailTokenExpirationTime = 86400;

	public $validate = array(
		'email' => array(
			'isValid' => array(
				'rule' => 'email',
				'required' => true,
				'message' => 'Entrez un email valide.'),
			'isUnique' => array(
				'rule' => array('isUnique', 'email'),
				'message' => 'Ce email est déjà pris.')),
		'password' => array(
			'too_short' => array(
				'rule' => array('minLength', '6'),
				'message' => 'Le mot de passe doit compter au moins 6 lettres.'),
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Entrez le mot de passe.')),
		'temppassword' => array(
			'rule' => 'confirmPassword',
			'message' => 'Les mots de passe correspondent pas, réessayez.'),
		'tos' => array(
			'rule' => array('custom','[1]'),
			'message' => 'Vous devez accepter les termes d\'utilisation.'),
	);

	public $hasOne = array(
		'Credit' => array(
            'className' => 'Credit',
            'foreignKey' => 'user_id',
            'conditions' => array('Credit.active' => '1'),
            'dependent' => true
        ),
       'Shop' => array(
            'className' => 'Shop',
            'foreignKey' => 'user_id',
            'dependent' => true
        )
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->_setupBehaviors();
		$this->_setupValidation();
		parent::__construct($id, $table, $ds);
	}

	protected function _setupBehaviors() {		
		App::uses('SearchableBehavior', 'Search.Model/Behavior');
		if (class_exists('SearchableBehavior')) {
			$this->actsAs[] = 'Search.Searchable';
		}

		App::uses('SluggableBehavior', 'Utils.Model/Behavior');
		if (class_exists('SluggableBehavior') && Configure::read('Users.disableSlugs') !== true) {
			$this->actsAs['Utils.Sluggable'] = array(
				'label' => 'firstname',
				'method' => 'multibyteSlug');
		}

		App::uses('SoftDeleteBehavior', 'Utils.Model/Behavior');
		if (class_exists('SoftDeleteBehavior')) {
			$this->actsAs[] = 'Utils.SoftDelete';
		}
	}

	protected function _setupValidation() {
		$this->validatePasswordChange = array(
			'new_password' => $this->validate['password'],
			'confirm_password' => array(
				'required' => array('rule' => array('compareFields', 'new_password', 'confirm_password'), 'required' => true, 'message' => __d('users', 'Les mots de passe correspondent pas.'))),
			'old_password' => array(
				'to_short' => array('rule' => 'validateOldPassword', 'required' => true, 'message' => __d('users', 'Mot de passe incorrect.'))));
	}

	public function confirmPassword($password = null) {
		if ((isset($this->data[$this->alias]['password']) && isset($password['temppassword']))
			&& !empty($password['temppassword'])
			&& ($this->data[$this->alias]['password'] === $password['temppassword'])) {
			return true;
		}
		return false;
	}

	public function register($postData = array(), $options = array()) {
		$Event = new CakeEvent(
			'Users.Model.User.beforeRegister',
			$this,
			array(
				'data' => $postData,
				'options' => $options
			)
		);

		$this->getEventManager()->dispatch($Event);
		if ($Event->isStopped()) {
			return $Event->result;
		}

		if (is_bool($options)) {
			$options = array('emailVerification' => $options);
		}

		$defaults = array(
			'emailVerification' => true,
			'removeExpiredRegistrations' => true,
			'returnData' => true);
		extract(array_merge($defaults, $options));

		$postData = $this->_beforeRegistration($postData, $emailVerification);

		if ($removeExpiredRegistrations) {
			$this->_removeExpiredRegistrations();
		}

		$this->set($postData);
		if ($this->validates()) {
			$postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['password']);
			$this->create();
			$this->data = $this->save($postData, false);
			$this->data[$this->alias]['id'] = $this->id;

			$Event = new CakeEvent(
				'Model.User.afterRegister',
				$this,
				array(
					'data' => $this->data,
					'options' => $options
				)
			);

			$this->getEventManager()->dispatch($Event);

			if ($Event->isStopped()) {
				return $Event->result;
			}

			if ($returnData) {
				return $this->data;
			}
			return true;
		}
		return false;
	}

	protected function _beforeRegistration($postData = array(), $useEmailVerification = true) {
		if ($useEmailVerification == true) {
			$postData[$this->alias]['email_token'] = $this->generateToken();
			$postData[$this->alias]['email_token_expires'] = date('Y-m-d H:i:s', time() + 86400);
		} else {
			$postData[$this->alias]['email_verified'] = 1;
		}
		$postData[$this->alias]['active'] = 1;
		$defaultRole = Configure::read('Users.defaultRole');
		if ($defaultRole) {
			$postData[$this->alias]['role'] = $defaultRole;
		} else {
			$postData[$this->alias]['role'] = 'registered';
		}
		return $postData;
	}

	public function generateToken($length = 10) {
		$possible = '0123456789abcdefghijklmnopqrstuvwxyz';
		$token = "";
		$i = 0;

		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
			if (!stristr($token, $char)) {
				$token .= $char;
				$i++;
			}
		}
		return $token;
	}

	public function add($postData = null) {
		if (!empty($postData)) {
			$this->data = $postData;
			if ($this->validates()) {
				if (empty($postData[$this->alias]['role'])) {
					if (empty($postData[$this->alias]['is_admin'])) {
						$defaultRole = Configure::read('Users.defaultRole');
						if ($defaultRole) {
							$postData[$this->alias]['role'] = $defaultRole;
						} else {
							$postData[$this->alias]['role'] = 'registered';
						}
					} else {
						$postData[$this->alias]['role'] = 'admin';
					}
				}
				$postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['password']);
				$this->create();
				$result = $this->save($postData, false);
				if ($result) {
					$result[$this->alias][$this->primaryKey] = $this->id;
					$this->data = $result;
					return true;
				}
			}
		}
		return false;
	}

	public function edit($userId = null, $postData = null) {
		$user = $this->getUserForEditing($userId);
		$this->set($user);
		if (empty($user)) {
			throw new NotFoundException(__d('users', 'Utilisateur non valide.'));
		}

		if (!empty($postData)) {
			$this->set($postData);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $postData;
			}
		}
	}

	public function getUserForEditing($userId = null, $options = array()) {
		$defaults = array(
			'conditions' => array($this->alias . '.id' => $userId));
		$options = Set::merge($defaults, $options);

		$user = $this->find('first', $options);

		if (empty($user)) {
			throw new NotFoundException(__d('users', 'Utilisateur non trouvé.'));
		}

		return $user;
	}

	protected function _removeExpiredRegistrations() {
		$this->deleteAll(array(
			$this->alias . '.email_verified' => 0,
			$this->alias . '.email_token_expires <' => date('Y-m-d H:i:s')));
	}

	public function hash($string) {
		return AuthComponent::password($string);
	}

	public function passwordReset($postData = array()) {
		$user = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.active' => 1,
				$this->alias . '.email' => $postData[$this->alias]['email'])));

		if (!empty($user) && $user[$this->alias]['email_verified'] == 1) {
			$sixtyMins = time() + 43000;
			$token = $this->generateToken();
			$user[$this->alias]['password_token'] = $token;
			$user[$this->alias]['email_token_expires'] = date('Y-m-d H:i:s', $sixtyMins);
			$user = $this->save($user, false);
			$this->data = $user;
			return $user;
		} elseif (!empty($user) && $user[$this->alias]['email_verified'] == 0) {
			$this->invalidate('email', __d('users', 'Cette adresse email existe mais n\'a encore été validée.'));
		} else {
			$this->invalidate('email', __d('users', 'Cette adresse email n\'existe pas.'));
		}

		return false;
	}

	public function verifyEmail($token = null) {
		$user = $this->checkEmailVerfificationToken($token);

		if ($user === false) {
			throw new RuntimeException(__d('users', 'Jeton invalide, merci de verifier le lien dans l\'email qui vous a été envoyé.'));
		}

		$expires = strtotime($user[$this->alias]['email_token_expires']);
		if ($expires < time()) {
			throw new RuntimeException(__d('users', 'Le jeton est expiré.'));
		}

		$data[$this->alias]['active'] = 1;
		$user[$this->alias]['email_verified'] = 1;
		$user[$this->alias]['email_token'] = null;
		$user[$this->alias]['email_token_expires'] = null;

		$user = $this->save($user, array(
			'validate' => false,
			'callbacks' => false));
		$this->data = $user;
		return $user;
	}

	public function checkEmailVerfificationToken($token = null) {
		$result = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.email_verified' => 0,
				$this->alias . '.email_token' => $token),
			'fields' => array(
				'id', 'email', 'email_token_expires', 'role')));

		if (empty($result)) {
			return false;
		}

		return $result;
	}

	public function checkPasswordToken($token = null) {
		$user = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.active' => 1,
				$this->alias . '.password_token' => $token,
				$this->alias . '.email_token_expires >=' => date('Y-m-d H:i:s'))));
		if (empty($user)) {
			return false;
		}
		return $user;
	}

	public function setUpResetPasswordValidationRules() {
		return array(
			'new_password' => $this->validate['password'],
			'confirm_password' => array(
				'required' => array(
					'rule' => array('compareFields', 'new_password', 'confirm_password'),
					'message' => __d('users', 'Les mots de passe ne correspondent pas.'))));
	}

	public function resetPassword($postData = array()) {
		$result = false;

		$tmp = $this->validate;
		$this->validate = $this->setUpResetPasswordValidationRules();

		$this->set($postData);
		if ($this->validates()) {
			$this->data[$this->alias]['password'] = $this->hash($this->data[$this->alias]['new_password'], null, true);
			$this->data[$this->alias]['password_token'] = null;
			$result = $this->save($this->data, array(
				'validate' => false,
				'callbacks' => false));
		}

		$this->validate = $tmp;
		return $result;
	}

	public function changePassword($postData = array()) {
		$this->validate = $this->validatePasswordChange;

		$this->set($postData);
		if ($this->validates()) {
			$this->data[$this->alias]['password'] = $this->hash($this->data[$this->alias]['new_password'], null, true);
			$this->save($postData, array(
				'validate' => false,
				'callbacks' => false));
			return true;
		}
		return false;
	}

	public function compareFields($field1, $field2) {
		if (is_array($field1)) {
			$field1 = key($field1);
		}

		if (isset($this->data[$this->alias][$field1]) && isset($this->data[$this->alias][$field2]) &&
			$this->data[$this->alias][$field1] == $this->data[$this->alias][$field2]) {
			return true;
		}
		return false;
	}

	public function validateOldPassword($password) {
		if (!isset($this->data[$this->alias]['id']) || empty($this->data[$this->alias]['id'])) {
			if (Configure::read('debug') > 0) {
				throw new OutOfBoundsException(__d('users', '$this->data[\'' . $this->alias . '\'][\'id\'] ne doit pas être vide.'));
			}
		}

		$currentPassword = $this->field('password', array($this->alias . '.id' => $this->data[$this->alias]['id']));
		return $currentPassword === $this->hash($password['old_password'], null, true);
	}

	public function view($id = null, $slug = null) {
		$user = $this->find('first', array(
			'contain' => array(
				'UserDetail'),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $id,
				$this->alias . '.slug' => $slug,
				$this->alias . '.active' => 1,
				$this->alias . '.email_verified' => 1)));

		if (empty($user)) {
			throw new OutOfBoundsException(__d('users', 'Cet utilisateur n\'existe pas.'));
		}

		return $user;
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as $k => $v) {
			if (isset($v[$this->alias]['id']) && isset($v[$this->alias]['slug']) && isset($v[$this->alias]['created']) && isset($v[$this->alias]['modified'])) {
				$v[$this->alias]['link'] = array(
					'controller' => 'users',
					'action' => 'view',
					'slug' => $v[$this->alias]['slug'],
					'id' => $v[$this->alias]['id'],
				);
			}
			$results[$k] = $v;
		}
		return $results;
	}

	protected function _findSearch($state, $query, $results = array()) {
		if (!class_exists('SearchableBehavior')) {
			throw new MissingPluginException(array('plugin' => 'Utils'));
		}

		if ($state == 'before') {
			$this->Behaviors->load('Containable', array(
				'autoFields' => false)
			);
			$results = $query;

			if (empty($query['search'])) {
				$query['search'] = '';
			}

			$by = $query['by'];
			$like = '%' . $query['search'] . '%';

			switch ($by) {
				case 'username':
					$results['conditions'] = Set::merge(
						$query['conditions'],
						array($this->alias . '.username LIKE' => $like));
					break;
				case 'email':
					$results['conditions'] = Set::merge(
						$query['conditions'],
						array($this->alias . '.email LIKE' => $like));
					break;
				case 'any':
					$results['conditions'] = Set::merge(
						$query['conditions'],
						array('OR' => array(
							array($this->alias . '.username LIKE' => $like),
							array($this->alias . '.email LIKE' => $like))));
					break;
				case '' :
					$results['conditions'] = $query['conditions'];
					break;
				default :
					$results['conditions'] = Set::merge(
						$query['conditions'],
						array($this->alias . '.username LIKE' => $like));
					break;
			}

			if (isset($query['operation']) && $query['operation'] == 'count') {
				$results['fields'] = array('COUNT(DISTINCT ' . $this->alias . '.id)');
			}

			return $results;
		} elseif ($state == 'after') {
			if (isset($query['operation']) && $query['operation'] == 'count') {
				if (isset($query['group']) && is_array($query['group']) && !empty($query['group'])) {
					return count($results);
				}
				return $results[0][0]['COUNT(DISTINCT ' . $this->alias . '.id)'];
			}
			return $results;
		}
	}

	public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		if (isset($extra['type']) && isset($this->findMethods[$extra['type']])) {
			$extra['operation'] = 'count';
			return $this->find($extra['type'], array_merge($parameters, $extra));
		} else {
			return $this->find('count', array_merge($parameters, $extra));
		}
	}

    public function fileExtension($check, $extensions, $allowEmpty = true){
        $file = current($check);
        if($allowEmpty && empty($file['tmp_name'])){
            return true;
        }
        $extension = strtolower(pathinfo($file['name'] , PATHINFO_EXTENSION));
        return in_array($extension, $extensions);
    }

    public function saveAvatar($userId, $postData = array()){
 
        foreach($this->settings['fields'] as $field => $path){
           if(isset($postData[$this->alias][$field . '_file'])){           		
                $file = $postData[$this->alias][$field . '_file'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $path = $this->getUploadPath($path, $extension, $userId);
                $dirname = dirname($path);

                if(!file_exists(WWW_ROOT . $dirname)){
                    mkdir(WWW_ROOT . $dirname, 0777, true);
                }
                $this->deleteOldUpload($field);
                $this->move_uploaded_file(
                    $file['tmp_name'],
                    WWW_ROOT . $path
                );
                chmod(WWW_ROOT . $path, 0777);
                $this->id = $userId;
                $this->saveField($field, $path);
           }
        }
    }

    public function move_uploaded_file($source, $destination){
        move_uploaded_file($source, $destination);
    }

    private function getUploadPath($path, $extension, $userId){
        $path = trim($path, '/');
        $replace = array(
            '%id1000'  => ceil($this->id / 1000),
            '%id100'   => ceil($this->id / 100),
            '%id'       => $userId,
            '%y'        => date('Y'),
            '%m'        => date('m')
        );
        $path = strtr($path, $replace) . '.' . $extension;
        return $path;
    }

    public function deleteOldUpload($field){
        $file = $this->field($field);
        if(empty($file)){
            return true;
        }
        $info = pathinfo($file);
        $subfiles = glob(WWW_ROOT . $info['dirname'] . DS . $info['filename'] . '_*x*.*');
        if(file_exists(WWW_ROOT . $file)){
            unlink(WWW_ROOT . $file);
        }
        foreach($subfiles as $file){
            unlink($file);
        }
    }

/* Partie supplémentaire à revoir au besoin */
	public function confirmEmail($email = null) {
		if ((isset($this->data[$this->alias]['email']) && isset($email['confirm_email']))
			&& !empty($email['confirm_email'])
			&& (strtolower($this->data[$this->alias]['email']) === strtolower($email['confirm_email']))) {
				return true;
		}
		return false;
	}

	public function updateLastActivity($userId = null, $field = 'last_action') {
		if (!empty($userId)) {
			$this->id = $userId;
		}
		if ($this->exists()) {
			return $this->saveField($field, date('Y-m-d H:i:s', time()));
		}
		return false;
	}

	public function findByEmail($email = null) {
		return $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.email' => $email,
			)
		));
	}

	public function checkEmailVerification($postData = array(), $renew = true) {
		$user = $this->findByEmail($postData[$this->alias]['email']);

		if (empty($user)) {
			$this->invalidate('email', __d('users', 'Invalid Email address.'));
			return false;
		}

		if ($user[$this->alias]['email_verified'] == 1) {
			$this->invalidate('email', __d('users', 'This email is already verified.'));
			return false;
		}

		if ($user[$this->alias]['email_verified'] == 0) {
			if ($renew === true) {
				$user[$this->alias]['email_token_expires'] = $this->emailTokenExpirationTime();
				$this->save($user, array(
					'validate' => false,
					'callbacks' => false,
				));
			}
			$this->data = $user;
			return true;
		}
	}

	public function resendVerification($postData = array()) {
		if (!isset($postData[$this->alias]['email']) || empty($postData[$this->alias]['email'])) {
			$this->invalidate('email', __d('users', 'Please enter your email address.'));
			return false;
		}

		$user = $this->findByEmail($postData[$this->alias]['email']);

		if (empty($user)) {
			$this->invalidate('email', __d('users', 'The email address does not exist in the system'));
			return false;
		}

		if ($user[$this->alias]['email_verified'] == 1) {
			$this->invalidate('email', __d('users', 'Your account is already authenticaed.'));
			return false;
		}

		if ($user[$this->alias]['active'] == 0) {
			$this->invalidate('email', __d('users', 'Your account is disabled.'));
			return false;
		}

		$user[$this->alias]['email_token'] = $this->generateToken();
		$user[$this->alias]['email_token_expires'] = $this->emailTokenExpirationTime();

		return $this->save($user, false);
	}

	public function emailTokenExpirationTime() {
		return date('Y-m-d H:i:s', time() + $this->emailTokenExpirationTime);
	}

	public function generatePassword($length = 10) {
		srand((double)microtime() * 1000000);
		$password = '';
		$vowels = array("a", "e", "i", "o", "u");
		$cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
							"cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");
		for ($i = 0; $i < $length; $i++) {
			$password .= $cons[mt_rand(0, 31)] . $vowels[mt_rand(0, 4)];
		}
		return substr($password, 0, $length);
	}
}