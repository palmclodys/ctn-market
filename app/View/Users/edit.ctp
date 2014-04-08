<div class="row">
	<div class="col-md-12 page-header">
		<h2><?php echo __d('users', 'Modifiez votre mot de passe'); ?></h2>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="info-box">
			<h1 class="small"><?php echo __('Suivez l\'actualité de vos contacts'); ?></h1>
			<p><?php echo __d('users', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
			<?php echo $this->Html->link(__('En savoir plus'), array('controller' => '', 'action' => ''), array('id' => 'button')); ?>
		</div>
	</div>
	<div class="col-md-4">
		<?php 
			echo $this->Form->create($model, array(
				'action' => 'edit',
				'class' => 'form-horizontal'
			));

			if (!empty($civilites)) {
				echo $this->Form->input('civilite', array(
					'type' => 'select',
					'class' => 'form-control',
					'div' => array(
				        'class' => 'form-group',
				    ),
					'label' => array(
						'text' => __d('users', 'Civilité')
					),
					'values' => $civilites,
					'before' => '<div class="col-sm-10">',
			    	'after' => '</div>',
				));
			}

			echo $this->Form->input('firstname', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('users', 'Votre nom'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('lastname', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('users', 'Votre prénom'),
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
				'PlaceHolder' => __d('users', '77xxxxxxx'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('show_my_phone', array(
				'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => array(
			    	'text' => __d('users', 'Afficher mon numéro'),
			    	'class' => 'checkbox-inline',
				),
			    'before' => '<div class="col-sm-10">',
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
			    'type' => 'textarea',
				'PlaceHolder' => __d('users', 'Adresse'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo '<div class="form-group"><div class="col-sm-10">' . $this->Html->link(__d('users', 'Modifiez votre mot de passe'), array('action' => 'change_password')) . '</div></div>';

			echo $this->Form->end(array(
				'label' => __d('users', 'Modifier'),
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