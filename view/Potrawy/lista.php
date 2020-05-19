<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Potrawy', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th class="photo-column">Zdjęcie</th>
						<th>Nazwa</th>
						<th class="bool-column">Wegetariańska</th>
						<th class="bool-column">Bezglutenowa</th>						
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$potrawy = View::pobierz('potrawy');
 						if(!empty($potrawy)): 
					?>
						<?php foreach($potrawy as $potrawa): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $potrawa['pot_id']; ?></th> 
							<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($potrawa['pot_zdjecie'], 'Potrawy', 'small'); ?>" alt="" /></td>
							<td><?= $potrawa['pot_nazwa']; ?></td>
							<td class="bool-column"><?= $potrawa['pot_wegetarianska']; ?></td>
							<td class="bool-column"><?= $potrawa['pot_bezglutenowa']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Potrawy', 'action' => 'edytuj', 'id' => $potrawa['pot_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Potrawy', 'action' => 'usun', 'id' => $potrawa['pot_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
