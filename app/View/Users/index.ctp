<div class="row">
	<div class="col-md-3">		
		<div class="bs-sidebar hidden-print" role="complementary">
            <ul class="nav bs-sidenav">
				<li>
					<a href="#overview">Overview</a>
					<ul class="nav">
					    <li><a href="#overview-doctype">HTML5 doctype</a></li>
					    <li><a href="#overview-mobile">Mobile first</a></li>
					    <li><a href="#overview-responsive-images">Responsive images</a></li>
					    <li><a href="#overview-type-links">Typography and links</a></li>
					    <li><a href="#overview-normalize">Normalize</a></li>
					    <li><a href="#overview-container">Containers</a></li>
					</ul>
				</li>
				<li>
					<a href="#grid">Grid system</a>
					<ul class="nav">
						<li><a href="#grid-intro">Introduction</a></li>
						<li><a href="#grid-media-queries">Media queries</a></li>
						<li><a href="#grid-options">Grid options</a></li>
						<li><a href="#grid-example-basic">Ex: Stacked-to-horizonal</a></li>
						<li><a href="#grid-example-mixed">Ex: Mobile and desktops</a></li>
						<li><a href="#grid-example-mixed-complete">Ex: Mobile, tablet, desktops</a></li>
						<li><a href="#grid-responsive-resets">Responsive column resets</a></li>
						<li><a href="#grid-offsetting">Offsetting columns</a></li>
						<li><a href="#grid-nesting">Nesting columns</a></li>
						<li><a href="#grid-column-ordering">Column ordering</a></li>
						<li><a href="#grid-less">LESS mixins and variables</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
				<h2><?php echo __d('users', 'Utilisateurs'); ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-hover">
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort('firstname'); ?></th>
							<th><?php echo $this->Paginator->sort('lastname'); ?></th>
							<th><?php echo $this->Paginator->sort('created'); ?></th>
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
								<?php echo $user[$model]['created']; ?>
							</td>
							<td class="actions">
								<?php echo $this->Html->link(__d('users', 'Voir'), array('action'=>'view', $user[$model]['id'])); ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>