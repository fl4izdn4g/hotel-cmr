<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'ProduktyPotrawy', 'action' => 'dodaj', 'meal_id' => View::pobierz('meal_id'))); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Nazwa produktu</th>
						<th class="big-element-count-column">Wykorzystywana ilość</th>
						<th class="quantity-column">Jednostka</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$produkty = View::pobierz('produkty');
						$jednostki = View::pobierz('wszystkie_jednostki');

						if(!empty($produkty)): 
					?>
						<?php foreach($produkty as $p): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $p['potxprod_id']; ?></th> 
							<td><?php echo $p['prod_nazwa']; ?></td>
							<td class="big-element-count-column"><?= $p['potxprod_wykorzystywana_ilosc']; ?></td>
							<td class="quantity-column"><?= $jednostki[$p['prod_jednostka']]; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'ProduktyPotrawy', 'action' => 'edytuj', 'id' => $p['potxprod_id'], 'meal_id' => View::pobierz('meal_id'))); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'ProduktyPotrawy', 'action' => 'usun', 'id' => $p['potxprod_id'], 'meal_id' => View::pobierz('meal_id'))); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
