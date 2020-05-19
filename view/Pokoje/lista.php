<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Pokoje', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th class="photo-column">Zdjęcie</th>
						<th>Grupa</th>
						<th>Numer</th>
						<th>Liczba osób</th>
						<th>Piętro</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$pokoje = View::pobierz('pokoje');
						$wszystkie_grupy = View::pobierz('wszystkie_grupy');
						if(!empty($pokoje)): 
					?>
						<?php foreach($pokoje as $pokoj): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $pokoj['pok_id']; ?></th> 
							<td><img src="<?= Router::poprawny_url_obrazka($pokoj['pok_zdjecie'], 'Pokoje', 'small'); ?>" alt="" /></td>
							<td><?= $wszystkie_grupy[$pokoj['pok_grp_id']]; ?></td>
							<td><?php echo $pokoj['pok_numer']; ?></td>
							<td><?= $pokoj['pok_liczba_osob']; ?></td>
							<td><?= $pokoj['pok_pietro']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Pokoje', 'action' => 'edytuj', 'id' => $pokoj['pok_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Pokoje', 'action' => 'usun', 'id' => $pokoj['pok_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php 
						endif;
					?>
				</tbody>
			</table>
		</div><!-- /.table-responsive -->
	</div><!-- /.box-body -->
</div><!-- /.box -->
