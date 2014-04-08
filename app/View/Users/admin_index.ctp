<div class="row">
	<div class="col-md-3">		
		<?php echo $this->element("admin_sidebar"); ?>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo __d('users', 'Utilisateurs'); ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3><?php echo __d('users', 'Recherche'); ?></h3>
				<?php 
					echo $this->Form->create($model, array(
						'action' => 'index',
						'class' => 'form-inline',
						'role' => 'form'
					));

					echo $this->Form->input('firstname', array(
						'type' => 'text',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'PlaceHolder' => __d('users', 'Nom'),
						'class' => 'form-control',
					));

					echo $this->Form->input('lastname', array(
						'type' => 'text',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'PlaceHolder' => __d('users', 'Prénom'),
						'class' => 'form-control',
					));

					echo $this->Form->input('email', array(
						'type' => 'text',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'PlaceHolder' => __d('users', 'xyz@exemple.com'),
						'class' => 'form-control',
					));

					echo $this->Form->end(array(
						'label' => __d('users', 'Rechercher'),
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
		<div class="row">
			<div class="col-md-12">
				<table class="table table-hover">
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort('firstname', 'Nom'); ?></th>
							<th><?php echo $this->Paginator->sort('lastname', 'Prénom(s)'); ?></th>
							<th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
							<th><?php echo $this->Paginator->sort('email_verified'); ?></th>
							<th><?php echo $this->Paginator->sort('active'); ?></th>
							<th><?php echo $this->Paginator->sort('created', 'Inscript le'); ?></th>
							<th class="actions"><?php echo __d('users', 'Actions'); ?></th>
						</tr>	
					</thead>
					<tbody>
						<?php foreach ($users as $user): ?>
						<tr>
							<td>
								<?php echo $user[$model]['firstname']; ?>
							</td>
							<td>
								<?php echo $user[$model]['lastname']; ?>
							</td>
							<td>
								<?php echo $user[$model]['email']; ?>
							</td>
							<td>
								<?php echo $user[$model]['email_verified'] == 1 ? __d('users', 'Yes') : __d('users', 'No'); ?>
							</td>
							<td>
								<?php echo $user[$model]['active'] == 1 ? __d('users', 'Yes') : __d('users', 'No'); ?>
							</td>
							<td>
								<?php echo $user[$model]['created']; ?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__d('users', 'Voir'), array('action'=>'view', $user[$model]['id'])); ?>
								<?php echo $this->Html->link(__d('users', 'Modifier'), array('action'=>'edit', $user[$model]['id'])); ?>
								<?php echo $this->Html->link(__d('users', 'Supprimer'), array('action'=>'delete', $user[$model]['id']), null, sprintf(__d('users', 'Etes vous sûr de vouloir supprimer %s?'), $user[$model]['firstname'] .' ' . $user[$model]['lastname'])); ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>