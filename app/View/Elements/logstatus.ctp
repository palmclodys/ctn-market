<?php if (isset($logged_user) && !empty($logged_user)): ?>
	<li class="dropdown">
		<a href="#" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">Bonjour <?php echo $logged_user['firstname'] . ' ' . $logged_user['lastname']; ?> <b class="caret"></b></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">

        <?php if(!$this->Session->read('Auth.User.is_admin')) : ?>
            <li role="presentation"><?php echo $this->Html->link(__d('users', 'Voir mon compte'), array('controller' => 'users', 'action' => 'view', 'id' => $logged_user['id'], 'slug' => $logged_user['slug'])); ?></li>
            <li role="presentation"><?php echo $this->Html->link(__d('users', 'Modifier mon profil'), array('controller' => 'users', 'action' => 'edit')); ?></li>
            <?php if($this->Session->read('Auth.User.has_shop')): ?>
            	 <li role="presentation"><?php echo $this->Html->link(__d('users', 'Modifier ma boutique'), array('controller' => 'shops', 'action' => 'edit')); ?></li>
            <?php endif; ?>
            <li role="presentation"><?php echo $this->Html->link(__d('ads', 'Gerer mes annonces'), array('controller' => 'ads', 'action' => 'viewUserAd')); ?></li>
            <li role="presentation" class="divider"></li>
        <?php endif; ?>
			<li role="presentation"><?php echo $this->Html->link(__d('users', 'Changer mon mot de passe'), array('controller' => 'users', 'action' => 'change_password')); ?></li>
		</ul>
	</li>

	<li>
		<?php echo $this->Html->link("Deconnexion", array('controller' => 'users', 'action' => 'logout', 'admin' => false)); ?>
	</li>
<?php else: ?>
	<li>
		<?php 
		if (!empty($allowRegistration) && $allowRegistration)  :
		echo $this->Html->link("Nouveau ? Inscrivez-vous", array('controller' => 'users', 'action' => 'register', 'admin' => false)); 
		endif;
		?>
	</li>
	<li>
		<?php echo $this->Html->link("Se connecter", array('controller' => 'users', 'action' => 'login', 'admin' => false)); ?>
	</li>
<?php endif; ?>