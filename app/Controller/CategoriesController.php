<?php 

App::uses('AppController', 'Controller');

class CategoriesController extends AppController {
	
	public $name = 'Categories';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('getCatMenu');
		//$this->Auth->allow('*');
		$this->set('modelName', $this->modelClass); 
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

	protected function _setupAdminPagination() {
		$this->Paginator->settings = array(
			'limit' => 20,
			'order' => array(
				$this->modelClass . '.created' => 'desc'
			)
		);
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

	/*public function index() {
		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate()); 
	}

	public function view($slug = null) {
		try {
			$category = $this->Category->view($slug);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('category')); 
	}*/

	public function admin_index() {
		$this->Prg->commonProcess();
		unset($this->Category->validate['name']);
		$this->Category->data[$this->modelClass] = $this->passedArgs;

		if ($this->Category->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->Category->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}

		$this->_setupAdminPagination();
		$this->Paginator->settings[$this->modelClass]['conditions'] = $parsedConditions;

		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate()); 
	}

	public function admin_view($slug = null) {
		try {
			$category = $this->Category->view($slug);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('category')); 
	}

	public function admin_add($category_id = null) {
		if (!empty($this->request->data)) {

			try {
				$result = $this->Category->add($this->data);
				if ($result !== false) {
					$this->Session->setFlash(sprintf(__d('categories', 'La catégorie %s a été sauvegardée'), $result['Category'][$this->Category->displayField]), 'notif');
					$this->redirect(array('action' => 'index'));
				}
			} catch (OutOfBoundsException $e) {
				$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
				$this->redirect(array('action' => 'index'));
			}
			if (!empty($this->data) && !empty($category_id)) {
				$this->data[$this->Category->alias]['category_id'] = $category_id;
			}
		}

		$this->set('categories', $this->Category->find('list', array(
       		'conditions' => array('category_id' => null)
	       )));
	}

	public function admin_edit($id = null) {
		try {
			$result = $this->Category->edit($id, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('categories', 'Catégorie sauvegardée'), 'notif');
				/**$this->redirect(array('action' => 'view', $this->Category->data[$this->Category->alias]['slug']));*/
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage(), 'notif', array('type' => 'error'));
		}

		$this->set('categories', $this->Category->find('list', array(
       		'conditions' => array('category_id' => null)
	       ))); 
	}

	public function admin_delete($id = null) {
        $this->Category->id = $id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__d('categories', 'Catégorie non valide'));
        }
        if ($this->Category->delete()) {
            $this->Session->setFlash(__d('categories', 'Catégorie supprimée.'), 'notif');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__d('categories', 'La catégorie ne peut être supprimée ou n\'est vide.'), 'notif', array('type' => 'error'));
        $this->redirect(array('action' => 'index'));
	}

	/*public function admin_tree() {
		$this->Category->recursive = 0;
		$this->helpers[] = 'Utils.Tree';
		$this->set('categories', $this->Category->find('all', array('order' => $this->Category->alias . '.lft')));
	}*/

	public function admin_deleteThumb($id = null){
		if (!$id) {
	        throw new NotFoundException(__d('categories', 'Catégorie invalide.'));
	    }

	    if(in_array('Thumbnail', $this->Category->Behaviors->loaded())) {
    		if($this->Category->hasField('category_thumb')) {
    			$file = $this->Category->field(
	    			'category_thumb',
    				array('id' => $id)
	    		);
	    		$info = pathinfo($file);
				foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg') as $v){
					unlink($v);
				}
				foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'.'.$info['extension']) as $v){
					unlink($v);
				}
				$this->Category->id = $id;
				$this->Category->saveField('category_thumb', '', array('validate' => false, 'callbacks' => false));
		    	$this->Session->setFlash(__d('categories', 'Image supprimée.'), 'notif');
				$this->redirect($this->referer());
    		}
    	} else {
    		throw new CakeException(__d('thumbnail','Le model \'%s\' n\'a pas le comportement \'Thumbnail\'', $this->modelClass));
    	}
    }

	public function getCatMenu() {
		return $data = $this->Category->generateCatMenu();
	}
}