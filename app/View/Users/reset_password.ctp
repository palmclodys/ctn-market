<div class="row">
	<div class="col-md-12 page-header">
		<h2><?php echo __d('users', 'Réinitialisez votre mot de passe'); ?></h2>
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
				'url' => array(
					'action' => 'reset_password',
					$token
				),
				'class' => 'form-horizontal'
			));
					
			echo $this->Form->input('new_password', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('users', 'Nouveau mot de passe'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
			));

			echo $this->Form->input('confirm_password', array(
			    'div' => array(
			        'class' => 'form-group',
			    ),
			    'label' => false,
				'PlaceHolder' => __d('users', 'Confirmez le mot de passe'),
				'class' => 'form-control',
				'between' => '<div class="col-sm-10">',
				'after' => '</div>',
				'error' => array(
			        'attributes' => array('wrap' => 'div', 'class' => 'error-message col-sm-10')
			    )
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