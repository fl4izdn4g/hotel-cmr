<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th class="photo-column">Nazwa</th>
					<th>Opis</th>
					<th class="price-column">Widoczna</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$galerie = View::pobierz('galerie');
					if(!empty($galerie)): 
				?>
					<?php foreach($galerie as $g): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $g['gal_id']; ?></th> 
						<td><?= $g['gal_nazwa']; ?></td>
						<td><?= $g['gal_opis']; ?></td>
						<td><?= $g['gal_widoczna']; ?></td>
						<td class="action-column">
							<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'edytuj', 'id' => $g['gal_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'usun', 'id' => $g['gal_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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