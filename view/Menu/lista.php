<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Menu', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th>Nazwa</th>
					<th>Ważne od</th>
					<th>Ważne do</th>
					<th class="bool-column">Aktualne</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$menu = View::pobierz('menu');
					if(!empty($menu)): 
				?>
					<?php foreach($menu as $m): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $m['men_id']; ?></th> 
						<td><?= $m['men_nazwa']; ?></td>
						<td><?= date('Y-m-d', strtotime($m['men_wazne_od'])); ?></td>
						<td><?= date('Y-m-d', strtotime($m['men_wazne_do'])); ?></td>
						<td><?= $m['men_czy_aktualne']; ?></td>
						<td class="action-column">
							<a href="<?= Router::utworz_link(array('controller' => 'Menu', 'action' => 'edytuj', 'id' => $m['men_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'Menu', 'action' => 'usun', 'id' => $m['men_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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