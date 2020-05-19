<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Dostep', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Typ obiektu</th>
						<th>Obiekt</th>
						<th>Rola</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$typy_obiektow = View::pobierz('wszystkie_typy');
						$role = View::pobierz('wszystkie_role');
						
						$dostepy = View::pobierz('dostepy');
						if(!empty($dostepy)): 
					?>
						<?php foreach($dostepy as $dostep): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $dostep['dos_id']; ?></th> 
							<td><?= $typy_obiektow[$dostep['dos_typ']]; ?></td>
							<td><?= $dostep['dos_obiekt']; ?></td>
							<td><?= $role[$dostep['dos_rol_id']]; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Dostep', 'action' => 'edytuj', 'id' => $dostep['dos_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Dostep', 'action' => 'usun', 'id' => $dostep['dos_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

