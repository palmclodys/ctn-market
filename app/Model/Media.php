<?php
class Media extends AppModel{

	public $useTable = 'medias';
	public $order    = 'position ASC';
	private $pictures = array('jpg','png','gif','bmp');

	public function beforeDelete($cascade = true){
		$this->Ad = ClassRegistry::init('Ad');
		$thumb = $this->Ad->find('first', array(
			'conditions' => array('media_id' => $this->id),
		));
		if (!empty($thumb)) {
			$this->Ad->id = $thumb['Ad']['id'];
			$this->Ad->saveField('media_id', '');
		}
		$file = $this->field('file');
		$info = pathinfo($file);
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg') as $v){
			unlink($v);
		}
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'.'.$info['extension']) as $v){
			unlink($v);
		}
		return true;
	}

	public function deleteAll($conditions, $cascade = true, $callbacks = false) {
		if (empty($conditions)) {
			return false;
		}
		$db = $this->getDataSource();
		if (!$cascade && !$callbacks) {
			return $db->delete($this, $conditions);
		}

		$ids = $this->find('all', array_merge(array(
			'fields' => "DISTINCT {$this->alias}.{$this->primaryKey}",
			'order' => false,
			'recursive' => 0), compact('conditions'))
		);

		if ($ids === false || $ids === null) {
			return false;
		}

		$ids = Hash::extract($ids, "{n}.{$this->alias}.{$this->primaryKey}");
		if (empty($ids)) {
			return true;
		}

		if ($callbacks) {
		$_id = $this->id;
		$result = true;
		foreach ($ids as $id) {
			$result = $result && $this->delete($id, $cascade);
		}

		$this->id = $_id;
			return $result;
		}

		foreach ($ids as $id) {
			$this->_deleteLinks($id);
			if ($cascade) {
				$this->_deleteDependent($id, $cascade);
			}
		}

		return $db->delete($this, array($this->alias . '.' . $this->primaryKey => $ids));
	}

	public function afterFind($results, $primary = false){
		foreach($results as $k => $result){
			if(isset($result[$this->alias]) && is_array($result)){
				$media = $result[$this->alias];
				if(isset($media['file'])){
					$pathinfo = pathinfo($media['file']);
					$extension= $pathinfo['extension'];

					if(!in_array($extension, $this->pictures)){
						$results[$k][$this->alias]['type'] = $extension;
						$results[$k][$this->alias]['icon'] = 'Media.' . $extension . '.png';
					}else{
						$results[$k][$this->alias]['type'] = 'pic';
						$results[$k][$this->alias]['icon'] = $media['file'];
					}
				}
			}
		}
		return $results;
	}

	public function beforeSave($options = array()){
		if( isset($this->data[$this->alias]['ref'])){
			$ref = $this->data['Media']['ref'];
			$model = ClassRegistry::init($ref);
        	if(!in_array('Media', $model->Behaviors->loaded())){
        		throw new CakeException(__d('media',"Le model '%s' n'a pas le comportement 'Media'", $ref));
        	}
		}
		if( isset($this->data['Media']['file']) && is_array($this->data['Media']['file']) && isset($this->data['Media']['ref']) ){
			$model 		= ClassRegistry::init($this->data['Media']['ref']);
			$path 		= $model->medias['path'];
			$ref_id 	= $this->data['Media']['ref_id'];
			$pathinfo 	= pathinfo($this->data['Media']['file']['name']);
			$extension  = strtolower($pathinfo['extension']) == 'jpeg' ? 'jpg' : $pathinfo['extension'];
			if(!in_array($extension, $model->medias['extensions'])){
				$this->error = __d('media','Vous ne pouvez pas uploader ce type de fichier (%s seulement)', implode(', ', $model->medias['extensions']));
				return false;
			}
			$filename 	= Inflector::slug($pathinfo['filename'],'-');
			$search 	= array('/', '%id', '%mid', '%cid', '%y', '%m', '%f');
			$replace 	= array(DS, $ref_id, ceil($ref_id/1000), ceil($ref_id/100), date('Y'), date('m'), Inflector::slug($filename));
			$file  		= str_replace($search, $replace, $path) . '.' . $extension;
			$this->testDuplicate($file);
			if(!file_exists(dirname(WWW_ROOT.$file))){
				mkdir(dirname(WWW_ROOT.$file),$model->medias['mode'],true);
			}
			$this->move_uploaded_file($this->data['Media']['file']['tmp_name'], WWW_ROOT.$file);
			chmod(WWW_ROOT.$file,$model->medias['mode']);
			$this->data['Media']['file'] = '/' . trim(str_replace(DS, '/', $file), '/');
		}
		return true;
	}

	public function move_uploaded_file($filename, $destination){
		if (is_uploaded_file($filename)) {
			return move_uploaded_file($filename, $destination);
		} else {
			return rename($filename, $destination);
		}
	}

	public function testDuplicate(&$dir,$count = 0){
		$file = $dir;
		if($count > 0){
			$pathinfo = pathinfo($dir);
			$file = $pathinfo['dirname'].'/'.$pathinfo['filename'].'-'.$count.'.'.$pathinfo['extension'];
		}
		if(!file_exists(WWW_ROOT.$file)){
			$dir = $file;
		}else{
			$count++;
			$this->testDuplicate($dir,$count);
		}
	}
}