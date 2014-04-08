<?php 

class DraftBehavior extends ModelBehavior {

    public $settings = array();

    public $conditions = array();

	public $defaults = array(
    	'conditions' => array(
    		'is_active' => -1,
	    )
	);

    public function setup(Model $model, $settings = array()) {
    	if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->defaults;
		}
		if (!is_array($settings)) {
			$settings = (array) $settings;
		}
		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
    }

    public function getDraftId(Model $model, $conditions = array()) {
    	$this->conditions[$model->alias]['conditions'] = array_merge($this->settings[$model->alias]['conditions'], $conditions);        
        $result = $model->find('first', array(
        	'fields' => $model->primaryKey,
        	'conditions' => $this->conditions[$model->alias]['conditions'],
        	'callbacks' => false,
        ));
        if (!empty($result)) {
        	return $result[$model->alias][$model->primaryKey];
        } else {
        	$data[$model->alias]['is_active'] = $this->conditions[$model->alias]['conditions'][$model->alias . '.is_active'];
        	$data[$model->alias]['user_id'] = $this->conditions[$model->alias]['conditions'][$model->alias . '.user_id'];
        	$model->create($data);
        	$model->save(null, false);
        	return $model->id;
        }
    }

    public function cleanDrafts() {
    	return $model->deleteAll(array('is_active' => -1));
    }
}