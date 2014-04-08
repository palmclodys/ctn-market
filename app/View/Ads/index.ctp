<?php //debug($ads); ?>

<div class="row">
	<div class="col-md-12">
		<h3><?php echo __d('ads', 'Recherche'); ?></h3>
		<?php 
			echo $this->Form->create($model, array(
				'action' => 'index',
				'class' => 'form-inline',
				'role' => 'form'
			));

			echo $this->Form->input('id', array(
				'type' => 'text',
				'div' => array(
			        'class' => 'form-group',
			    ),
				'label' => false,
				'PlaceHolder' => __d('ads', 'Reference'),
				'class' => 'form-control',
			));

			echo $this->Form->input('name', array(
				'type' => 'text',
				'div' => array(
			        'class' => 'form-group',
			    ),
				'label' => false,
				'PlaceHolder' => __d('ads', 'Titre'),
				'class' => 'form-control',
			));

			if (!empty($categories)) {
				echo $this->Form->input('category_id', array_merge(
					$categories,
					array(
						'type' => 'select',
						'class' => 'form-control',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'before' => '<div class="col-sm-10">',
				    	'after' => '</div>',
					))
				);
			}

			echo $this->Form->end(array(
				'label' => __d('ads', 'Rechercher'),
				'class' => 'btn btn-default',
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'before' => '<div class="col-sm-10">',
			    'after' => '</div>'
			));
		?>
	</div>
</div>

<div class="row ad-listing">
<?php foreach ($ads as $ad): ?>
	<div class="col-md-12 item-list tie_thumb tie_soundcloud tie_video">
		<div class="col-md-2 ad-thumbnail">
			<a href="<?php echo $this->Html->url($ad[$model]['link']); ?>">
			<?php echo $this->Media->image($ad[$model]['thumb'], 180, 100); ?>
			</a>
		</div>
		<div class="col-md-7 ad-meta">
			<h2 class="item-box-title"><?php echo $this->Html->link($ad[$model]['name'], $ad[$model]['link']); ?></h2>
			<p class="item-box-resume"><?php echo $ad[$model]['resume']; ?></p>
			<p class="item-box-author"><?php echo $this->Date->time_elapsed_string($ad[$model]['validated_date']); ?></p>
			<div class="item-other-details">
			<p></p>
			</div>
		</div>
		<div class=" col-md-3 mini-share-ad">
			<p><?php echo $ad['User']['phone']; ?></p>
			<p><?php echo $ad['User']['email']; ?></p>
			<p><?php echo $ad[$model]['id']; ?></p>
		</div>
	</div>
<?php endforeach; ?>
</div>