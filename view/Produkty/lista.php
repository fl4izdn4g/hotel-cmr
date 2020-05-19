<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Produkty', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th class="photo-column">Ikona</th>
						<th>Nazwa</th>
						<th>Grupa</th>
						<th class="price-column">Cena</th>
						<th>Jednostka</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$produkty = View::pobierz('produkty');
						$wszystkie_grupy = View::pobierz('wszystkie_grupy_produktow');
						$wszystkie_jednostki = View::pobierz('wszystkie_jednostki');
 						if(!empty($produkty)): 
					?>
						<?php foreach($produkty as $produkt): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $produkt['prod_id']; ?></th> 
							<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($produkt['prod_ikona'], 'Produkty', 'small'); ?>" alt="" /></td>
							<td><?= $produkt['prod_nazwa']; ?></td>
							<td><?= $wszystkie_grupy[$produkt['prod_grpp_id']]; ?></td>
							<td class="price-column"><?= $produkt['prod_cena_jednostkowa_netto']; ?></td>
							<td><?= $wszystkie_jednostki[$produkt['prod_jednostka']]; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Produkty', 'action' => 'edytuj', 'id' => $produkt['prod_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Produkty', 'action' => 'usun', 'id' => $produkt['prod_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
