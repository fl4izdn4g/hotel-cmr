<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'WyposazenieGrupyPokoi', 'action' => 'dodaj', 'group_id' => View::pobierz('group_id'))); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Nazwa</th>
						<th class="element-count-column">Ilość</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$wyposazenie = View::pobierz('wyposazenie');
						if(!empty($wyposazenie)): 
					?>
						<?php foreach($wyposazenie as $w): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $w['gxw_id']; ?></th> 
							<td><?php echo $w['wyp_nazwa']; ?></td>
							<td class="element-count-column"><?= $w['gxw_ilosc_wyposazenia']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'WyposazenieGrupyPokoi', 'action' => 'edytuj', 'id' => $w['gxw_id'], 'group_id' => View::pobierz('group_id'))); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'WyposazenieGrupyPokoi', 'action' => 'usun', 'id' => $w['gxw_id'], 'group_id' => View::pobierz('group_id'))); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
