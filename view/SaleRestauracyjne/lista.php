<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'SaleRestauracyjne', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th class="photo-column">Zdjęcie</th>
						<th>Nazwa</th>
						<th>Opis</th>
						<th class="bool-column">Dla palących</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$sale = View::pobierz('sale');
						if(!empty($sale)): 
					?>
						<?php foreach($sale as $sala): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $sala['sar_id']; ?></th> 
							<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($sala['sar_zdjecie'], 'SaleRestauracyjne', 'small'); ?>" alt="" /></td>
							<td><?php echo $sala['sar_nazwa']; ?></td>
							<td><?= $sala['sar_opis']; ?></td>
							<td class="bool-column"><?= $sala['sar_dla_palacych']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'SaleRestauracyjne', 'action' => 'edytuj', 'id' => $sala['sar_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'SaleRestauracyjne', 'action' => 'usun', 'id' => $sala['sar_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
