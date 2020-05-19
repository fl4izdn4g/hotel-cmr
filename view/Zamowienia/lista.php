<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Zamawiający</th>
						<th>Data zamówienia</th>
						<th>Adres</th>
						<th>Wartość zamówienia</th>
						<th>Anulowana</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$zamowienia = View::pobierz('zamowienia');
						if(!empty($zamowienia)): 
					?>
						<?php foreach($zamowienia as $z): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $z['zp_id']; ?></th>
							<td><?= $z['zp_zamawiajacy']; ?></td> 
							<td><?= $z['zp_data_zamowienia']; ?></td>
							<td><?= $z['zp_adres']; ?></td>
							<td><?= $z['zp_cena_brutto']; ?></td>
							<td><?= $z['zp_anulowano']; ?></td>
							<td class="action-column">
								<?php if($z['zp_anulowano'] == 'tak'): ?>
									<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia', 'action' => 'podglad', 'id' => $z['zp_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></a>
								<?php else: ?>
									<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia', 'action' => 'anuluj', 'id' => $z['zp_id'])); ?>" class="btn btn-danger can-cancel-order"><span class="glyphicon glyphicon-remove"></span></a>
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

