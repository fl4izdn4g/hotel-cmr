<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Stoliki', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Sala restauracyjna</th>
						<th>Numer</th>
						<th>Liczba miejsc</th>
						<th>Cena</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$stoliki = View::pobierz('stoliki');
						$wszystkie_sale = View::pobierz('wszystkie_sale');
						if(!empty($stoliki)): 
					?>
						<?php foreach($stoliki as $stolik): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $stolik['sto_id']; ?></th> 
							<td><?= $wszystkie_sale[$stolik['sto_sar_id']]; ?></td>
							<td><?= $stolik['sto_numer']; ?></td>
							<td><?= $stolik['sto_liczba_miejsc']; ?></td>
							<td><?= $stolik['sto_cena_netto']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Stoliki', 'action' => 'edytuj', 'id' => $stolik['sto_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Stoliki', 'action' => 'usun', 'id' => $stolik['sto_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
