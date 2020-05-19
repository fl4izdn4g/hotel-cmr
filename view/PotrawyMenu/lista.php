<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'PotrawyMenu', 'action' => 'dodaj', 'menu_id' => View::pobierz('menu_id'))); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Nazwa</th>
						<th class="element-count-column">Cena</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$potrawy = View::pobierz('potrawy');
						if(!empty($potrawy)): 
					?>
						<?php foreach($potrawy as $p): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $p['menxpot_id']; ?></th> 
							<td><?php echo $p['pot_nazwa']; ?></td>
							<td class="price-column"><?= $p['menxpot_cena_netto']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'PotrawyMenu', 'action' => 'edytuj', 'id' => $p['menxpot_id'], 'menu_id' => View::pobierz('menu_id'))); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'PotrawyMenu', 'action' => 'usun', 'id' => $p['menxpot_id'], 'menu_id' => View::pobierz('menu_id'))); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
