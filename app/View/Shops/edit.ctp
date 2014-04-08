<div class="row">
	<div class="col-md-12">
		<h2><?php echo __d('shops', 'Ouverture boutique'); ?></h2>
	</div>
</div>

<?php if (isset($logged_user) && !empty($logged_user)): ?>

<div class="row">
	<div class="col-md-8">
		<div class="info-box">
			<h1 class="small"><?php echo __('Suivez l\'actualité de vos contacts'); ?></h1>
			<p><?php echo __d('shops', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
			<?php echo $this->Html->link(__('En savoir plus'), array('controller' => '', 'action' => ''), array('id' => 'button')); ?>
		</div>
	</div>
	<div class="col-md-4">
		<?php
			echo $this->Form->create($model, array(
				'class' => 'form-horizontal',
				'type' => 'file',
			));

			echo $this->Form->input('id');

			echo $this->Form->input('name', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('ads', 'Nom de la boutique'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('about', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('ads', 'Description de la boutique'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('adress', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('ads', 'Adresse de la boutique'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('facebook_url', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('shops', 'Page facebook de la boutique'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('website_url', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('shops', 'Site web de la boutique'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('phone', array(
				'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
			    'type' => 'text',
				'PlaceHolder' => __d('shops', '77xxxxxxx'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			if (!empty($durations)) {
				echo $this->Form->input('duration', array(
					'type' => 'select',
					'class' => 'form-control',
					'div' => array(
				        'class' => 'form-group',
				    ),
					'label' => array(
						'text' => __d('ads', 'Durée de validité de la boutique')
					),
					'values' => $durations,
					'before' => '<div class="col-sm-10">',
			    	'after' => '</div>',
				));
			}

			if (isset($this->data[$model]['shop_thumb']) && !empty($this->data[$model]['shop_thumb'])) {
				echo $this->Media->image($this->data[$model]['shop_thumb'], 325, 255);
				echo $this->Html->link(__d('shops', 'Supprimer l\'image'), array('controller' => 'shops', 'action' => 'deleteThumb', $this->data[$model]['id']), array(), 'Etes vous sûr de vouloir supprimer cette image ?');
			} else {
				echo $this->Form->input('shop_thumb_file', array(
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
				'label' => __d('shops', 'Créer ma boutique'),
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

<?php else: ?>

<?php endif; ?>