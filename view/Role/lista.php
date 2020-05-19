<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Role', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Kod</th>
						<th>Nazwa</th>
						<th>Opis</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$role = View::pobierz('role');
						if(!empty($role)): 
					?>
						<?php foreach($role as $rola): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $rola['rol_id']; ?></th>
							<td><?= $rola['rol_kod']; ?></td> 
							<td><?= $rola['rol_nazwa']; ?></td>
							<td><?= $rola['rol_opis']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Role', 'action' => 'edytuj', 'id' => $rola['rol_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Role', 'action' => 'usun', 'id' => $rola['rol_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php 
						endif;
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

