<?php

App::uses('Model', 'Model');

class AppModel extends Model {

	public function exists($id = null) {
	    if ($this->Behaviors->attached('SoftDelete')) {
	        return $this->existsAndNotDeleted($id);
	    } else {
	        return parent::exists($id);
	    }
	}

	public function delete($id = null, $cascade = true) {
	    $result = parent::delete($id, $cascade);
	    if ($result === false && $this->Behaviors->enabled('SoftDelete')) {
	       return (bool)$this->field('deleted', array('deleted' => 1));
	    }
	    return $result;
	}

	public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		if (isset($extra['type']) && isset($this->findMethods[$extra['type']])) {
			$extra['operation'] = 'count';
			return $this->find($extra['type'], array_merge($parameters, $extra));
		}
		return $this->find('count', array_merge($parameters, $extra));
	}
}