<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'edytuj', 'id' => View::pobierz('gallery_id'))); ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-arrow-left"></span></a>
		<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'zdjecie_dodaj', 'gallery_id' => View::pobierz('gallery_id'))); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th class="photo-column">Zdjęcie</th>
					<th>Nazwa</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$zdjecia = View::pobierz('zdjecia');
					if(!empty($zdjecia)): 
				?>
					<?php foreach($zdjecia as $z): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $z['zdj_id']; ?></th> 
						<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($z['zdj_plik'], 'Galerie', 'small'); ?>" alt="" /></td>
						<td><?php echo $z['zdj_tytul']; ?></td>
						<td class="action-column">
							<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 
																   'action' => 'zdjecie_edytuj', 
																   'id' => $z['zdj_id'],
																   'gallery_id' => View::pobierz('gallery_id')
							)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'Galerie', 
																   'action' => 'zdjecie_usun', 
																    'id' => $z['zdj_id'], 
																	'gallery_id' => View::pobierz('gallery_id'))) ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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