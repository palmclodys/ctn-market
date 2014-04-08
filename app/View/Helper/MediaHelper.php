<?php
class MediaHelper extends AppHelper{

	public $helpers = array('Html','Form');
	public $javascript = false;
	public $explorer = false;

	public function image($image, $width, $height, $options = array()){
		$options['width'] = $width;
		$options['height'] = $height;
		return $this->Html->image($this->resizedUrl($image, $width, $height), $options);
	}

	public function resizedUrl($image, $width, $height){
		$image = trim($image, '/');
		$pathinfo = pathinfo($image);
		$dest = sprintf(str_replace(".{$pathinfo['extension']}", '_%sx%s.jpg', $image), $width, $height);
		$image_file = WWW_ROOT . $image;
		$dest_file = WWW_ROOT . $dest;

		// On a déjà le fichier redimensionné ?
		if (!file_exists($dest_file)) {

			require_once 'phar://' . APP . 'Vendor' . DS . 'imagine.phar';
			
			$imagine = new Imagine\Gd\Imagine();
			try{
				//$angle = $this->__getRotation( $image_file ); // This method doesn't work for everyone
				$angle = 0;
				$imagine->open($image_file)->rotate( $angle )->thumbnail(new Imagine\Image\Box($width, $height), Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND)->save($dest_file, array('quality' => 90));
			} catch (Imagine\Exception\Exception $e) {
				$alternates = glob(str_replace(".{$pathinfo['extension']}",".*", $image_file));
				if(empty($alternates)){
					return '/img/error.jpg';
				}else{
					try{
						$imagine->open($alternates[0])->thumbnail(new Imagine\Image\Box($width, $height), Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND)->save($dest_file, array('quality' => 90));
					} catch (Imagine\Exception\Exception $e) {
						return '/img/error.jpg';
					}
				}
			}
		}
		return '/' . $dest;
	}


	/**
	 * Check the orientation in order to rotate
	 * the picture and display it normally.
	 * Fix the problem related to picture taken from mobile device (Android, iOS)
	 * or by camera
	 *
	 * @param string $filename Full path to the picture
	 * @return int $angle Return the rotation level
	 */
	private function __getRotation( $filename ){
		$extension = @end(explode('.', $filename));
		$angle    = 0;
		if( $extension == 'jpg' ){
			$exif   = @exif_read_data( $filename );
			if(!empty($exif['Orientation'])) {
	    		switch($exif['Orientation']) {
	        		case 8:
	            		$angle = -90;
	            	break;
	        		case 3:
	            		$angle = 180;
	            	break;
	        		case 6:
	            		$angle = 90;
	            	break;
	    		}
			}else if(!empty($exif) && empty($exif['Orientation'])){
				$angle = 0;
			}else if(empty($exif)){
				$angle = 90;
			}
		}
		return $angle;
	}

	public function iframe($ref,$ref_id){
		return '<iframe src="' . $this->Html->url("/medias/index/$ref/$ref_id") . '" style="width:100%;" id="medias-' . $ref . '-' . $ref_id . '"></iframe>';
	}
}
