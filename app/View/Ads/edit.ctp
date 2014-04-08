<div class="row">
	<div class="col-md-12">
		<h2><?php echo __d('ads', 'Compte App-Name'); ?></h2>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="info-box">
			<h1 class="small"><?php echo __('Suivez l\'actualité de vos contacts'); ?></h1>
			<p><?php echo __d('ads', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
			<?php echo $this->Html->link(__('En savoir plus'), array('controller' => '', 'action' => ''), array('id' => 'button')); ?>
		</div>
	</div>
	<div class="col-md-4">
		<?php
			echo $this->Form->create($model, array(
				'action' => 'edit',
				'class' => 'form-horizontal'
			));

			if (isset($logged_user) && !empty($logged_user)) {
				echo $this->Form->input('user_id', array(
					'type' => 'hidden',
					'value' => $logged_user['id']
				));
				echo $this->Form->input('user_type', array(
					'type' => 'hidden',
					'value' => $logged_user['type']
				));
				echo $this->Form->input('id');
			}

			if (!empty($categories)) {
				echo $this->Form->input('category_id', array_merge(
					$categories,
					array(
						'type' => 'select',
						'class' => 'form-control',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => array(
							'text' => __d('ads', 'Catégorie')
						),
						'before' => '<div class="col-sm-10">',
				    	'after' => '</div>',
					))
				);
			}

			if (!empty($types)) {
				echo $this->Form->input('type', array(
					'type' => 'select',
					'class' => 'form-control',
					'div' => array(
				        'class' => 'form-group',
				    ),
					'label' => array(
						'text' => __d('ads', 'Type d\'annonce')
					),
					'values' => $types,
					'before' => '<div class="col-sm-10">',
			    	'after' => '</div>',
				));
			}

			echo $this->Form->input('name', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('ads', 'Titre de l\'annonce'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			if (!empty($states)) {
				echo $this->Form->input('state', array(
					'type' => 'select',
					'class' => 'form-control',
					'div' => array(
				        'class' => 'form-group',
				    ),
					'label' => array(
						'text' => __d('ads', 'Etat du produit')
					),
					'values' => $states,
					'before' => '<div class="col-sm-10">',
			    	'after' => '</div>',
				));
			}

			echo $this->Form->input('content', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('ads', 'Contenu de l\'annonce'),
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
						'text' => __d('ads', 'Nombre de jours de validité')
					),
					'values' => $durations,
					'before' => '<div class="col-sm-10">',
			    	'after' => '</div>',
				));
			}

			echo $this->Form->input('price', array(
				'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
			    'type' => 'text',
				'PlaceHolder' => __d('ads', 'Prix'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Media->iframe('Ad', $this->data[$model]['id']);

			echo $this->Form->end(array(
				'label' => __d('ads', 'Soumettre'),
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