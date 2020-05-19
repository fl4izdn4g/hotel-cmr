<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'GrupyProduktow', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th class="photo-column">Ikona</th>
					<th>Nazwa</th>
					<th>Opis</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$grupy_produktow = View::pobierz('grupy_produktow');
					if(!empty($grupy_produktow)): 
				?>
					<?php foreach($grupy_produktow as $grupa): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $grupa['grpp_id']; ?></th> 
						<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($grupa['grpp_ikona'], 'GrupyProduktow', 'small'); ?>" alt="" /></td>
						<td><?php echo $grupa['grpp_nazwa']; ?></td>
						<td><?= $grupa['grpp_opis']; ?></td>
						<td class="action-column">
							<a href="<?= Router::utworz_link(array('controller' => 'GrupyProduktow', 'action' => 'edytuj', 'id' => $grupa['grpp_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'GrupyProduktow', 'action' => 'usun', 'id' => $grupa['grpp_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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