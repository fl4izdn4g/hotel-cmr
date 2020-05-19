<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th>Produkt</th>
					<th>Aktualny stan</th>
					<th>Status uzupełnienia</th>
					<th>Ostatnie uzupełnienie</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$stany = View::pobierz('stany');
					$produkty = View::pobierz('produkty');
					
					$statusy = array(
						'OK' => 'fa fa-fw fa-check-circle color-success',
						'WARNING' => 'fa fa-fw fa-exclamation-triangle color-warning',
						'ERROR' => 'fa fa-fw fa-minus-square color-error',
					);
					
					if(!empty($stany)): 
				?>
					<?php foreach($stany as $a): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $a['stm_id']; ?></th> 
						<td><?= $produkty[$a['stm_prod_id']]; ?></td>
						<td><?= $a['stm_aktualny_stan']; ?></td>
						<td><i class="<?= $statusy[$a['stm_ilosc_status']]; ?>" style="font-size: 20px"></i></td>
						<td><?= $a['stm_data_ostatniego_uzupelnienia']; ?></td>
						<td class="action-column" style="width: 150px">
							<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'zdarzenia', 'state_id' => $a['stm_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'edytuj', 'id' => $a['stm_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'usun', 'id' => $a['stm_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php 
					endif;
				?>
			</tbody>
		</table>
		</div><!-- /.table-responsive -->
	</div>
</div>