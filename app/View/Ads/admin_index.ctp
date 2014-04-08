<div class="row">
	<div class="col-md-3">		
		<?php echo $this->element("admin_sidebar"); ?>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo __d('ads', 'Annonces'); ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3><?php echo __d('ads', 'Recherche'); ?></h3>
				<?php 
					echo $this->Form->create($model, array(
						'action' => 'index',
						'class' => 'form-inline',
						'role' => 'form'
					));

					echo $this->Form->input('id', array(
						'type' => 'text',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'PlaceHolder' => __d('ads', 'Reference'),
						'class' => 'form-control',
					));

					echo $this->Form->input('name', array(
						'type' => 'text',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'PlaceHolder' => __d('ads', 'Titre'),
						'class' => 'form-control',
					));

					echo $this->Form->end(array(
						'label' => __d('ads', 'Rechercher'),
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
							<th><?php echo $this->Paginator->sort('id', 'Ref'); ?></th>
							<th><?php echo $this->Paginator->sort('name', 'Titre'); ?></th>
							<th><?php echo $this->Paginator->sort('user_type', 'Type'); ?></th>
							<th><?php echo $this->Paginator->sort('category_id', 'Categorie'); ?></th>
							<th><?php echo $this->Paginator->sort('ip_address', 'Adresse Ip'); ?></th>
							<th><?php echo $this->Paginator->sort('modified', 'Soumise le'); ?></th>
							<th class="actions"><?php echo __d('ads', 'Actions'); ?></th>
						</tr>	
					</thead>
					<tbody>
						<?php foreach ($ads as $ad): ?>
						<tr>
							<td>
								<?php echo $ad[$model]['id']; ?>
							</td>
							<td>
								<?php echo $ad[$model]['name']; ?>
							</td>
							<td>
								<?php echo $ad[$model]['user_type']; ?>
							</td>
							<td>
								<?php echo $ad[$model]['category_id']; ?>
							</td>
							<td>
								<?php echo $ad[$model]['ip_address']; ?>
							</td>
							<td>
								<?php echo $ad[$model]['modified']; ?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__d('ads', 'Voir'), array('action'=>'view', $ad[$model]['id'])); ?>
								<?php echo $this->Html->link(__d('ads', 'Modifier'), array('action'=>'edit', $ad[$model]['id'])); ?>
								<?php echo $this->Html->link(__d('ads', 'Supprimer'), array('action'=>'delete', $ad[$model]['id']), null, sprintf(__d('ads', 'Etes vous sûr de vouloir supprimer %s?'), $ad[$model]['name'])); ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>