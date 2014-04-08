<?php $sizes = getimagesize(WWW_ROOT.trim($media['file'], '/'));  ?>

<div class="item <?php if($thumbID && $media['id'] === $thumbID): ?>thumbnail<?php endif; ?>">

		<input type="hidden" value="<?php echo $media['position']; ?>" name="data[Media][<?php echo $media['id']; ?>]">

		<div class="visu"><?php echo $this->Html->image($media['icon']); ?></div>
		<?php echo basename($media['file']); ?>

		<div class="actions">
			<?php if($thumbID !== false && $media['id'] !== $thumbID && $media['type'] == 'pic'): ?>
				<?php echo $this->Html->link(__d("media", "Mettre en image à la une"),array('action'=>'thumb',$media['id'])); ?> -
			<?php endif; ?>
			<?php echo $this->Html->link(__d('media',"Supprimer"),array('action'=>'delete',$media['id']),array('class'=>'del')); ?>
		</div>
</div>