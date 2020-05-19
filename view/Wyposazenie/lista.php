<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Wyposazenie', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
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
						$wyposazenie = View::pobierz('wyposazenie');
						if(!empty($wyposazenie)): 
					?>
						<?php foreach($wyposazenie as $w): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $w['wyp_id']; ?></th> 
							<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($w['wyp_ikona'], 'Wyposazenie', 'small'); ?>" alt="" /></td>
							<td><?php echo $w['wyp_nazwa']; ?></td>
							<td><?= $w['wyp_opis']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Wyposazenie', 'action' => 'edytuj', 'id' => $w['wyp_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Wyposazenie', 'action' => 'usun', 'id' => $w['wyp_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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
