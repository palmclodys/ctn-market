<?php

App::uses('ModelBehavior', 'Model');

class ThumbnailBehavior extends ModelBehavior{

	public $settings = array(
		'path'    => 'img/uploads/%y/%m/%f',
		'extensions' => array('jpg','png','jpeg'),
		'maxSize' => 2097152,
		'max_width' => 0,
		'max_height' => 0,
		'mode' => 0777,
	);

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings['field'])) {
	        $this->settings['field'] = strtolower($Model->name. '_thumb');
	    }
	    $this->settings = array_merge($this->settings, $settings);

	    if($Model->hasField('user_id')){
			$Model->belongsTo['User'] = array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'counterCache'=> false
			);
		}
	}

	public function beforeSave(Model $Model, $options = array()){

		if($Model->hasField($this->settings['field'])) {
			if (isset($Model->data[$Model->alias][$this->settings['field'] . '_file']['tmp_name']) && !empty($Model->data[$Model->alias][$this->settings['field'] . '_file']['tmp_name']) && is_array($Model->data[$Model->alias][$this->settings['field'] . '_file'])) {
				$path 		= $this->settings['path'];
				$pathinfo 	= pathinfo($Model->data[$Model->alias][$this->settings['field'] . '_file']['name']);
				$extension  = strtolower($pathinfo['extension']) == 'jpeg' ? 'jpg' : $pathinfo['extension'];

				if(!in_array($extension, $this->settings['extensions'])){
					$this->error = __d('media','Vous ne pouvez pas uploader ce type de fichier (%s seulement)', implode(', ', $this->settings['extensions']));
					return false;
				}

				if (in_array($extension, $this->settings['extensions']) && ($this->settings['max_width'] > 0 || $this->settings['max_height'] > 0 )) {
					list($width,$height) = getimagesize($Model->data[$Model->alias][$this->settings['field'] . '_file']['tmp_name']);
					if ($this->settings['max_width'] > 0 && $width > $this->settings['max_width']) {
						$this->error = __d('media', "La largeur maximum autorisée est de %dpx", $this->settings['max_width']);
						return false;
					}
					if ($this->settings['max_height'] > 0 && $height > $this->settings['max_height']) {
						$this->error = __d('media', "La hauteur maximum autorisée est de %dpx", $this->settings['max_height']);
						return false;
					}
				}

				if ($this->settings['maxSize'] > 0 && floor($Model->data[$Model->alias][$this->settings['field'] . '_file']['size'] / 1024) > $this->settings['maxSize']) {
					$humanSize		= $this->settings['maxSize'] > 1024 ? round($this->settings['maxSize']/1024,1).' Mo' : $this->settings['maxSize'].' Ko';
					$this->error	= __d('media', "Vous ne pouvez pas envoyer un fichier supérieur à %s", $humanSize);
					return false;
				}

				$filename 	= Inflector::slug($pathinfo['filename'],'-');
				$search 	= array('/', '%y', '%m', '%f');
				$replace 	= array(DS, date('Y'), date('m'), Inflector::slug($filename));
				$file  		= str_replace($search, $replace, $path) . '.' . $extension;

				$this->testDuplicate($file);
				if(!file_exists(dirname(WWW_ROOT.$file))){
					mkdir(dirname(WWW_ROOT.$file), $this->settings['mode'],true);
				}
				$this->move_uploaded_file($Model->data[$Model->alias][$this->settings['field'] . '_file']['tmp_name'], WWW_ROOT.$file);
				chmod(WWW_ROOT.$file, $this->settings['mode']);
				$Model->data[$Model->alias][$this->settings['field']] = '/' . trim(str_replace(DS, '/', $file), '/');
			}
		}
		return true;
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

	public function move_uploaded_file($filename, $destination){
		return move_uploaded_file($filename, $destination);
	}

	public function beforeDelete(Model $Model, $cascade = true) {
		$file = $Model->field($Model->data[$Model->alias][$this->settings['field']]);
		$info = pathinfo($file);
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg') as $v){
			unlink($v);
		}
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'.'.$info['extension']) as $v){
			unlink($v);
		}
		return true;
	}

	public function afterSave(Model $Model, $created, $options = array()) {
		if ($created) {
			if($Model->hasField('user_id')) {
				if (isset($Model->data[$Model->alias]['user_id']) && !empty($Model->data[$Model->alias]['user_id'])) {
					$Model->User->save(array(
						'id' => $Model->data[$Model->alias]['user_id'],
						'has_shop' => 1,
						'shop_id' => $Model->data[$Model->alias]['id'],
					), false);
				}
			}
		}
	}
}