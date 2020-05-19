<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Grupa pokoi</th>
						<th>Numer pokoju</th>
						<th>Piętro</th>
						<th>Od</th>
						<th>Do</th>
						<th>Anulowana</th>
						<th class="action-column" style="width: 150px">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$rezerwacje = View::pobierz('rezerwacje');
						if(!empty($rezerwacje)): 
					?>
						<?php foreach($rezerwacje as $r): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $r['rp_id']; ?></th>
							<td><?= $r['grp_nazwa']; ?></td> 
							<td><?= $r['pok_numer']; ?></td>
							<td><?= $r['pok_pietro']; ?></td>
							<td><?= $r['rp_data_od']; ?></td>
							<td><?= $r['rp_data_do']; ?></td>
							<td><?= $r['rp_anulowano']; ?></td>
							<td class="action-column">
								<?php if($r['rp_anulowano'] == 'tak'): ?>
									<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'podglad', 'id' => $r['rp_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></a>
								<?php else: ?>
									<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'podglad', 'id' => $r['rp_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></a>
									<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'anuluj', 'id' => $r['rp_id'])); ?>" class="btn btn-danger can-cancel-reservation"><span class="glyphicon glyphicon-remove"></span></a>
								<?php endif; ?>
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

