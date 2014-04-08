<div class="row">
	<div class="col-md-3">		
		<?php echo $this->element("admin_sidebar"); ?>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo __d('users', 'Ajouter un utilisateur'); ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="info-box">
					<h1 class="small"><?php echo __('Suivez l\'actualité de vos contacts'); ?></h1>
					<p><?php echo __d('users', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
					<?php echo $this->Html->link(__('En savoir plus'), array('controller' => '', 'action' => ''), array('id' => 'button')); ?>
				</div>
			</div>
			<div class="col-md-6">
				<?php
					echo $this->Form->create($model, array(
						'class' => 'form-horizontal'
					));

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

					if (!empty($roles)) {
						echo $this->Form->input('role', array(
							'type' => 'select',
							'class' => 'form-control',
							'div' => array(
						        'class' => 'form-group',
						    ),
							'label' => array(
								'text' => __d('users', 'Role')
							),
							'values' => $roles,
							'before' => '<div class="col-sm-10">',
					    	'after' => '</div>',
						));
					}
					echo $this->Form->input('is_admin', array(
						'type' => 'checkbox',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => array(
							'text' => __d('users', 'Administrateur'),
							'class' => 'checkbox-inline'
						),
						'before' => '<div class="col-sm-10">',
					    'after' => '</div>',
					));

					echo $this->Form->input('active', array(
						'type' => 'checkbox',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => array(
							'text' => __d('users', 'Active'),
							'class' => 'checkbox-inline'
						),
						'before' => '<div class="col-sm-10">',
					    'after' => '</div>',
					));

					echo $this->Form->end(array(
						'label' => __d('users', 'Ajouter'),
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