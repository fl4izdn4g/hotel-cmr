<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th class="photo-column">Zdjęcie</th>
					<th>Imię</th>
					<th>Nazwisko</th>
					<th>Stanowisko</th>
					<th>Email</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$administatorzy = View::pobierz('administratorzy');
					if(!empty($administatorzy)): 
				?>
					<?php foreach($administatorzy as $a): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $a['adm_id']; ?></th> 
						<td class="photo-column"><img src="<?= Router::poprawny_url_obrazka($a['adm_zdjecie'], 'Administratorzy', 'small'); ?>" alt="" /></td>
						<td><?= $a['uzy_imie']; ?></td>
						<td><?= $a['uzy_nazwisko']; ?></td>
						<td><?= $a['adm_stanowisko']; ?></td>
						<td><?= $a['kuz_email']; ?></td>
						<td class="action-column">
							<?php if(!empty($a['adm_id'])): ?>
								<a href="<?= Router::utworz_link(array('controller' => 'Administratorzy', 'action' => 'edytuj', 'id' => $a['adm_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Administratorzy', 'action' => 'usun', 'id' => $a['adm_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
							<?php else: ?>
								<a href="<?= Router::utworz_link(array('controller' => 'Administratorzy', 'action' => 'dodaj', 'user_id' => $a['uzy_id'])); ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></a>
							<?php endif; ?>
							
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