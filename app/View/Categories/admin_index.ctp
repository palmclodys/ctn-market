<div class="row">
	<div class="col-md-3">		
		<?php echo $this->element("admin_sidebar"); ?>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo __d('categories', 'Catégories'); ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3><?php echo __d('categories', 'Recherche'); ?></h3>
				<?php 
					echo $this->Form->create($modelName, array(
						'action' => 'index',
						'class' => 'form-inline',
						'role' => 'form'
					));

					echo $this->Form->input('name', array(
						'type' => 'text',
						'div' => array(
					        'class' => 'form-group',
					    ),
						'label' => false,
						'PlaceHolder' => __d('categories', 'Nom'),
						'class' => 'form-control',
					));

					echo $this->Form->end(array(
						'label' => __d('categories', 'Rechercher'),
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
							<th><?php echo $this->Paginator->sort('id', '#'); ?></th>
							<th><?php echo $this->Paginator->sort('name', 'Intitulé'); ?></th>
							<th><?php echo $this->Paginator->sort('created', 'Date de création'); ?></th>
							<th class="actions"><?php echo __d('categories', 'Actions'); ?></th>
						</tr>	
					</thead>
					<tbody>
						<?php foreach ($categories as $category): ?>
						<tr>
							<td>
								<?php echo $category[$modelName]['id']; ?>
							</td>
							<td>
								<?php echo $category[$modelName]['name']; ?>
							</td>
							<td>
								<?php echo $category[$modelName]['created']; ?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__d('categories', 'View'), array('action'=>'Voir', $category[$modelName]['id'])); ?>
								<?php echo $this->Html->link(__d('categories', 'Modifier'), array('action'=>'edit', $category[$modelName]['id'])); ?>
								<?php echo $this->Html->link(__d('categories', 'Supprimer'), array('action'=>'delete', $category[$modelName]['id']), null, sprintf(__d('categories', 'Etes vous sûr de vouloir supprimer la catégorie "%s"?'), $category[$modelName]['name'])); ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>