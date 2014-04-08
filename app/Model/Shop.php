<?php 

App::uses('AppModel', 'Model');

class Shop extends AppModel {

	public $name = 'Shop';

    public $findMethods = array(
		'search' => true
	);

	public $filterArgs = array(
		'name' => array('type' => 'like'),
	);

	public $displayField = 'name';

	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Vueillez entrer un nom pour la boutique.'),
			'unique_name' => array(
				'rule' => array('isUnique', 'name'),
				'message' => 'Ce nom est déjà pris.'),
			'unique' => array(
				'rule' => array('checkUnique'),
				'message' => 'Vous disposez déjà d\'une boutique.')),

		'about' => array(
			'rule' => array('notEmpty'),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Vueillez entrer une description pour la boutique.'),
		'facebook_url' => array(
			'rule' => 'url',
			'message' => 'Veuillez entrer une adresse url valide.'),

		'website_url' => array(
			'rule' => 'url',
			'message' => 'Veuillez entrer une adresse url valide.'));

	public function __construct($id = false, $table = null, $ds = null) {
		$this->_setupBehaviors();
		parent::__construct($id, $table, $ds);
	}

	protected function _setupBehaviors() {
		App::uses('SearchableBehavior', 'Search.Model/Behavior');
		if (class_exists('SearchableBehavior')) {
			$this->actsAs[] = 'Search.Searchable';
		}

		App::uses('SluggableBehavior', 'Utils.Model/Behavior');
		if (class_exists('SluggableBehavior')) {
			$this->actsAs['Utils.Sluggable'] = array(
				'label' => 'name',
				'separator' => '-',
				'method' => 'multibyteSlug');
		}

		$this->actsAs['Thumbnail'] = array(
			'path' => 'img/shops/%y/%m/%f');
	}

	function checkUnique() {
		return ($this->find('count', array('conditions' => array(
			$this->alias . '.user_id' => $this->data[$this->alias]['user_id']))) == 0);
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['about']) && !empty($this->data[$this->alias]['about'])) {
			$this->data[$this->alias]['meta_description'] = String::truncate(
                $this->data[$this->alias]['about'],
                200,
                array(
                    'ellipsis' => '...',
                    'exact' => false
                )
            );
		}
		return true;
	}

	/*public function view($slug = null, $field = 'slug') {
		$shop = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'OR' => array(
					$this->alias . '.' . $field => $slug,
					$this->alias . '.' . $this->primaryKey => $slug),
				$this->alias . '.active' => 1,
				$this->alias . '.email_verified' => 1)));

		if (empty($user)) {
			throw new NotFoundException(__d('users', 'The user does not exist.'));
		}

		return $user;
	}*/

	public function view($id = null, $slug = null) {

		if (!$id) {
			throw new NotFoundException(__d('shops', 'Aucune page correspondante'));
		}

		$shop = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $id,
				$this->alias . '.active' => 1)));

		if (!$shop) {
			throw new NotFoundException(__d('shops', 'Aucune page correspondante'));
		}
		if ($slug != $page['Post']['slug']) {
			$this->redirect($page['Post']['link'], 301);
		}
		$this->set(compact('page'));
	}

	protected function _beforeRegistration($postData = array()) {
		
		$postData[$this->alias]['active'] = 0;
		
		if (isset($postData[$this->alias]['duration'])) {
			$postData[$this->alias]['shop_expires'] = $this->ShopExpirationTime($postData[$this->alias]['duration']);
		}
		return $postData;
	}

	protected function ShopExpirationTime($days) {
		return date('Y-m-d H:i:s', time() + ($days*24*3600));
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as $k => $v) {
			if (isset($v[$this->alias]['id']) && isset($v[$this->alias]['slug'])) {
				$v[$this->alias]['link'] = array(
					'controller' => 'shops',
					'action' => 'view',
					'id' => $v[$this->alias]['id'],
					'slug' => $v[$this->alias]['slug']
				);
			}
			$results[$k] = $v;
		}
		return $results;
	}

	public function add($postData = array(), $options = array()) {
		$defaults = array(
			'returnData' => true);
		extract(array_merge($defaults, $options));

		$postData = $this->_beforeRegistration($postData);

		$this->set($postData);
		if ($this->validates()) {
			$this->create();
			$this->data = $this->save($postData, false);
			$this->data[$this->alias]['id'] = $this->id;

			if ($returnData) {
				return $this->data;
			}
			return true;
		}
		return false;
	}

	public function edit($userId = null, $postData = null) {
		$shop = $this->getShopForEditing($userId);
		if (empty($shop)) {
			throw new NotFoundException(__d('shops', 'Boutique non valide'));
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
		return $shop;
	}

	public function getShopForEditing($userId = null, $options = array()) {
		$defaults = array(
			'conditions' => array($this->alias . '.user_id' => $userId));
		$options = Set::merge($defaults, $options);

		$shop = $this->find('first', $options);

		if (empty($shop)) {
			throw new NotFoundException(__d('shops', 'Boutique non trouvée, vous disposez pas sans doute de boutique.'));
		}

		return $shop;
	}
}