<?php

App::uses('AppModel', 'Model');

class Category extends AppModel {

	public $name = 'Category';

	public $displayField = 'name';

	public $filterArgs = array(
		'name' => array('type' => 'like'),
	);

	public $validate = array();

	public $belongsTo = array(
		'ParentCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''));

	public $hasMany = array(
		'ChildCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'dependent' => false));

	public function __construct($id = false, $table = null, $ds = null) {
		$this->_setupBehaviors();		
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'name' => array(
				'required' => array(
					'rule' => array('notEmpty'),
					'required' => true,
					'allowEmpty' => false,
					'message' => __d('categories', 'Veuillez entrer un nom de Catégorie')
				)
			),
		);
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

		App::uses('SoftDeleteBehavior', 'Utils.Model/Behavior');
		if (class_exists('SoftDeleteBehavior')) {
			$this->actsAs[] = 'Utils.SoftDelete';
		}

		$this->actsAs['Tree'] = array(
			'parent' => 'category_id'
		);

		$this->actsAs['Thumbnail'] = array(
			'path' => 'img/categories/%y/%m/%f');
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as $k => $v) {
			if (isset($v[$this->alias]['id']) && isset($v[$this->alias]['slug'])) {
				$v[$this->alias]['link'] = array(
					'controller' => 'categories',
					'action' => 'view',
					'id' => $v[$this->alias]['id'],
					'slug' => $v[$this->alias]['slug']
				);
			}
			$results[$k] = $v;
		}
		return $results;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['description']) && !empty($this->data[$this->alias]['description'])) {
			$this->data[$this->alias]['meta_description'] = String::truncate(
                $this->data[$this->alias]['description'],
                200,
                array(
                    'ellipsis' => '...',
                    'exact' => false
                )
            );
		}
		$this->data[$this->alias]['model'] = 'Ad';
		return true;
	}

	public function add($data = null, $options = array()) {
		$defaults = array(
            'returnData' => true);
        extract(array_merge($defaults, $options));
		if (!empty($data)) {
			$this->create($data);
			$result = $this->save($data);
			if ($result !== false) {
				$this->data = array_merge($data, $result);
				if ($returnData) {
					return $this->data;
				}
				return true;
			} else {
				throw new OutOfBoundsException(__d('categories', 'Impossible de sauvegarder la catégorie, veuillez vérifier les données.'));
			}
			return $result;
		}
	}

	public function edit($id = null, $data = null) {
		$conditions = array("{$this->alias}.{$this->primaryKey}" => $id);

		$category = $this->find('first', array(
			'contain' => array('ParentCategory'),
			'conditions' => $conditions));

		if (empty($category)) {
			throw new OutOfBoundsException(__d('categories', 'Invalid Category'));
		}
		$this->set($category);

		if (!empty($data)) {
			$this->set($data);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $category;
		}
	}

	public function view($slug = null) {
		$category = $this->find('first', array(
			'contain' => array('ParentCategory'),
			'conditions' => array(
				'or' => array(
				$this->alias . '.id' => $slug,
				$this->alias . '.slug' => $slug))));

		if (empty($category)) {
			throw new OutOfBoundsException(__d('categories', 'Invalid Category'));
		}

		return $category;
	}

	public function delete($id = null, $cascade = true) {
        $result = parent::delete($id, $cascade);
        if ($result === false && $this->Behaviors->enabled('SoftDelete')) {
            return $this->field('deleted', array('deleted' => 1));
        }
        return $result;
    }

    public function beforeDelete($cascade = true) {
    	$count = $this ->Ad->find("count" , array(
	    	'conditions' => array(
		    	'category_id' => $this->id
		    )
		));
		if ($count == 0) {
			return true;
		} else {
			return false ;
		}
    }

    public function parent() {
    	return $this->find('list', array(
	    	'conditions' => array(
		    	'category_id' => null,
		    ),
	    ));
    }

    public function generateList() {
    	$parents = $this->parent();
    	$stack = array();
    	if (!empty($parents)) {
    		foreach ($parents as $k => $v) {
    			$children = $this->children($k, true, array('id', 'name'));
	    		$stack[$v] = Hash::combine($children, '{n}.Category.id', '{n}.Category.name');
	    	}
	    	return $stack;
    	}
    	return array();
    }

    public function generateCatMenu() {
    	$parents = $this->parent();
    	$stack = array();
    	if (!empty($parents)) {
    		foreach ($parents as $k => $v) {
    			$children = $this->children($k, true, array('id', 'name', 'slug', 'ads_published'));
	    		$stack[$k][$v] = Hash::combine($children, '{n}.Category.id', '{n}.Category');
	    	}
	    	return $stack;
    	}
    	return array();
    }
}