<div class="row">
	<div class="col-md-3">		
		<?php echo $this->element("admin_sidebar"); ?>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo __d('categories', 'Modifier la catégorie'); ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="info-box">
					<h1 class="small"><?php echo __('Suivez l\'actualité de vos contacts'); ?></h1>
					<p><?php echo __d('categories', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
					<?php echo $this->Html->link(__('En savoir plus'), array('controller' => '', 'action' => ''), array('id' => 'button')); ?>
				</div>
			</div>
			<div class="col-md-6">
				<?php
					echo $this->Form->create($modelName, array(
						'class' => 'form-horizontal',
						'type' => 'file',
					));

					echo $this->Form->input('id');

					if (!empty($categories)) {
						echo $this->Form->input('category_id', array(
							'empty' => true,
							'type' => 'select',
							'class' => 'form-control',
							'div' => array(
						        'class' => 'form-group',
						    ),
							'label' => array(
								'text' => __d('categories', 'Categorie parent')
							),
							'values' => $categories,
							'before' => '<div class="col-sm-10">',
					    	'after' => '</div>',
						));
					}

					echo $this->Form->input('name', array(
					    'div' => array(
					        'class' => 'form-group',
					    ),
					    'label' => false,
						'PlaceHolder' => __d('categories', 'Nom de la catégorie'),
						'class' => 'form-control',
						'between' => '<div class="col-sm-10">',
						'after' => '</div>',
						'error' => array(
					        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
					    )
					));

					echo $this->Form->input('description', array(
						'div' => array(
					        'class' => 'form-group',
					    ),
					    'label' => false,
					    'type' => 'textarea',
						'PlaceHolder' => __d('categories', 'Description'),
						'class' => 'form-control',
						'between' => '<div class="col-sm-10">',
						'after' => '</div>',
						'error' => array(
					        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
					    )
					));

					if (isset($this->data[$modelName]['category_thumb']) && !empty($this->data[$modelName]['category_thumb'])) {
						echo $this->Media->image($this->data[$modelName]['category_thumb'], 325, 255);
						echo $this->Html->link(__d('categories', 'Supprimer l\'image'), array('controller' => 'categories', 'action' => 'deleteThumb', $this->data[$modelName]['id']), array(), 'Etes vous sûr de vouloir supprimer cette image ?');
					} else {
						echo $this->Form->input('category_thumb_file', array(
							'div' => array(
						        'class' => 'form-group',
						    ),
						    'label' => __d('categories', 'Images'),
						    'type' => 'file',
						    'before' => '<div class="col-sm-10">',
					    	'after' => '<p class="help-block">Au format jpg ou png.</p></div>',
						));
					}

					echo $this->Form->end(array(
						'label' => __d('categories', 'Modifier'),
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
	</div>
</div>