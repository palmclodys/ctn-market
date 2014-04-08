<div class="row">
	<div class="col-md-12">
		<h2><?php echo __d('users', 'Compte App-Name'); ?></h2>
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
				'action' => 'register',
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

			if (!empty($types)) {
				echo $this->Form->input('type', array(
					'type' => 'select',
					'class' => 'form-control',
					'div' => array(
				        'class' => 'form-group',
				    ),
					'label' => array(
						'text' => __d('users', 'Statut')
					),
					'values' => $types,
					'before' => '<div class="col-sm-10">',
			    	'after' => '</div>',
				));
			}

			echo $this->Form->input('email', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('users', 'xyz@exemple.com'),
				'error' => array(
					'isValid' => __d('users', 'L\'adresse email n\'est pas valide'),
					'isUnique' => __d('users', 'Cet email déjà pris'),
				),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('password', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'type' => 'password',
			    'label' => false,
				'PlaceHolder' => __d('users', 'Votre mot de passe'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
			));

			echo $this->Form->input('temppassword', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'type' => 'password',
			    'label' => false,
				'PlaceHolder' => __d('users', 'Confirmez votre mot de passe'),
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

			$tosLink = $this->Html->link(__d('users', 'Termes d\'utilisation'), array('controller' => 'pages', 'action' => 'tos', 'plugin' => null));

			echo $this->Form->input('tos', array(
				'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => array(
			    	'text' => __d('users', 'J\'accepte les ' . $tosLink),
			    	'class' => 'checkbox-inline',
				),
			    'before' => '<div class="col-sm-10">',
			    'after' => '</div>',
			    'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->end(array(
				'label' => __d('users', 'M\'inscrire'),
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