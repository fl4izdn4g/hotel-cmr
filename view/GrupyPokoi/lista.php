<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'GrupyPokoi', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th class="photo-column">Ikona</th>
					<th>Nazwa</th>
					<th class="price-column">Cena netto</th>
					<th>Opis</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$grupy_pokoi = View::pobierz('grupy_pokoi');
					if(!empty($grupy_pokoi)): 
				?>
					<?php foreach($grupy_pokoi as $grupa): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $grupa['grp_id']; ?></th> 
						<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($grupa['grp_ikona'], 'GrupyPokoi', 'small'); ?>" alt="" /></td>
						<td><?php echo $grupa['grp_nazwa']; ?></td>
						<td class="price-column"><?= $grupa['grp_cena_netto']; ?></td>
						<td><?= $grupa['grp_opis']; ?></td>
						<td class="action-column">
							<a href="<?= Router::utworz_link(array('controller' => 'GrupyPokoi', 'action' => 'edytuj', 'id' => $grupa['grp_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'GrupyPokoi', 'action' => 'usun', 'id' => $grupa['grp_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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