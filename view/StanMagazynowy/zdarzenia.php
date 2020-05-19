<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'zdarzenie', 'state_id' => View::pobierz('state_id'))); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
		<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy')); ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-arrow-left"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th>Typ</th>
					<th>Data</th>
					<th>Ilość</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$zdarzenia = View::pobierz('zdarzenia');
					
					if(!empty($zdarzenia)): 
				?>
					<?php foreach($zdarzenia as $a): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $a['zdm_id']; ?></th> 
						<td><?= $a['zdm_typ']; ?></td>
						<td><?= $a['zdm_data_wystapienia']; ?></td>
						<td><?= $a['zdm_ilosc']; ?></td>
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