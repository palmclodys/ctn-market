<div class="row">
	<div class="col-md-12 page-header">
		<h2><?php echo __d('users', 'Connexion'); ?></h2>
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
		<?php echo $this->Session->flash('auth', array('element' => 'notif', 'params' => array('type' => 'danger')));?>
		<?php
			echo $this->Form->create($model, array(
				'action' => 'login',
				'class' => 'form-horizontal'));

			echo $this->Form->input('email', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('users', 'xyz@exemple.com'),
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
			    'label' => false,
				'PlaceHolder' => __d('users', 'Mot de passe'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));
 
			echo $this->Form->input('remember_me', array(
				'type' => 'checkbox',
				'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => array(
			    	'text' => __d('users', 'Se rappeler de moi'),
			    	'class' => 'checkbox-inline',
				),
			    'before' => '<div class="col-sm-10">',
			    'after' => '</div>',
			));

			echo '<div class="form-group"><div class="col-sm-10">' . $this->Html->link(__d('users', 'J\'ai oublié mon mot de passe'), array('action' => 'reset_password')) . '</div></div>';
			echo $this->Form->hidden('User.return_to', array(
				'value' => $return_to));

			echo $this->Form->end(array(
				'label' => __d('users', 'Se connecter'),
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