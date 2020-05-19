<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Sala</th>
						<th>Numer stolika</th>
						<th>Zamawiający</th>
						<th>Data rezerwacji</th>
						<th>Anulowana</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$rezerwacje = View::pobierz('rezerwacje');
						if(!empty($rezerwacje)): 
					?>
						<?php foreach($rezerwacje as $r): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $r['rs_id']; ?></th>
							<td><?= $r['sar_nazwa']; ?></td> 
							<td><?= $r['sto_numer']; ?></td>
							<td><?= $r['uzy_imie']; ?> <?= $r['uzy_nazwisko']; ?></td>
							<td><?= date('Y-m-d',strtotime($r['rs_data_rezerwacji'])); ?></td>
							<td><?= $r['rs_anulowano']; ?></td>
							<td class="action-column">
								<?php if($r['rs_anulowano'] == 'tak'): ?>
									<a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'podglad', 'id' => $r['rs_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></a>
								<?php else: ?>
									<a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'podglad', 'id' => $r['rs_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></a>
									<a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'anuluj', 'id' => $r['rs_id'])); ?>" class="btn btn-danger can-cancel-reservation"><span class="glyphicon glyphicon-remove"></span></a>
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

