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
						'action' => 'change_password',
						'admin' => true,
						'class' => 'form-horizontal'
					));

					echo $this->Form->input('old_password', array(
						'div' => array(
					        'class' => 'form-group',
					    ),
					    'type' => 'password',
						'label' => false,
						'PlaceHolder' => __d('users', 'Votre ancien mot de passe'),
						'class' => 'form-control',
						'between' => '<div class="col-sm-10">',
						'after' => '</div>'
					));

					echo $this->Form->input('new_password', array(
						'div' => array(
					        'class' => 'form-group',
					    ),
					    'type' => 'password',
						'label' => false,
						'PlaceHolder' => __d('users', 'Votre nouveau mot de passe'),
						'class' => 'form-control',
						'between' => '<div class="col-sm-10">',
						'after' => '</div>'
					));

					echo $this->Form->input('confirm_password', array(
						'div' => array(
					        'class' => 'form-group',
					    ),
					    'type' => 'password',
						'label' => false,
						'PlaceHolder' => __d('users', 'Confirmer le mot de passe'),
						'class' => 'form-control',
						'between' => '<div class="col-sm-10">',
						'after' => '</div>'
					));

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
	</div>
</div>