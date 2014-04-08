<div class="bs-sidebar hidden-print" role="complementary">
    <ul class="nav bs-sidenav">
		<li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_index') && ($this->params['controller']=='users'))?'active' :'' ?>">
			<?php echo $this->Html->link(__('Utilisateurs'), array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?>
			<ul class="nav">
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_list_pro') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Professionnels'), array('controller' => 'users', 'action' => 'list_pro', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_list_par') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Particuliers'), array('controller' => 'users', 'action' => 'list_par', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_list_admins') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Adminitrateurs'), array('controller' => 'users', 'action' => 'list_admins', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_add') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Ajouter un utilisateur'), array('controller' => 'users', 'action' => 'add', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_new_users') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Nouveaux utilisateurs'), array('controller' => 'users', 'action' => 'new_users', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_inactive_users') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Utilisateurs inactifs'), array('controller' => 'users', 'action' => 'inactive_users', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_deleted_users') && ($this->params['controller']=='users'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Comptes supprimés'), array('controller' => 'users', 'action' => 'deleted_users', 'admin' => true)); ?>
			    </li>
			</ul>
		</li>

		<li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_index') && ($this->params['controller']=='categories'))?'active' :'' ?>">
			<?php echo $this->Html->link(__('Catégories'), array('controller' => 'categories', 'action' => 'index', 'admin' => true)); ?>
			<ul class="nav">
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_add') && ($this->params['controller']=='categories'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Ajouter une catégorie'), array('controller' => 'categories', 'action' => 'add', 'admin' => true)); ?>
			    </li>
			</ul>
		</li>

		<li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_index') && ($this->params['controller']=='ads'))?'active' :'' ?>">
			<?php echo $this->Html->link(__('Annonces'), array('controller' => 'ads', 'action' => 'index', 'admin' => true)); ?>
			<ul class="nav">
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_news') && ($this->params['controller']=='ads'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Liste des nouvelles annonces'), array('controller' => 'ads', 'action' => 'news', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_available') && ($this->params['controller']=='ads'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Liste des annonces valides'), array('controller' => 'ads', 'action' => 'available', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_closed') && ($this->params['controller']=='ads'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Liste des annonces clôturées'), array('controller' => 'ads', 'action' => 'closed', 'admin' => true)); ?>
			    </li>
			    <li class="<?php echo (!empty($this->params['action']) && ($this->params['action']=='admin_archived') && ($this->params['controller']=='ads'))?'active' :'' ?>">
			    	<?php echo $this->Html->link(__('Liste des annonces archivées'), array('controller' => 'ads', 'action' => 'archived', 'admin' => true)); ?>
			    </li>
			</ul>
		</li>
	</ul>
</div>