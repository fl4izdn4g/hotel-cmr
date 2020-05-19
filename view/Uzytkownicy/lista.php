<div class="box box-solid">
	<div class="box-header with-border">
		<a href="<?= Router::utworz_link(array('controller' => 'Uzytkownicy', 'action' => 'dodaj')); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th>Nazwisko</th>
					<th>Imię</th>
					<th>Email</th>
					<th>Status</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$uzytkownicy = View::pobierz('uzytkownicy');
					$wszystkie_statusy = View::pobierz('wszystkie_statusy');
					if(!empty($uzytkownicy)): 
				?>
					<?php foreach($uzytkownicy as $uzytkownik): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $uzytkownik['uzy_id']; ?></th> 
						<td><?= $uzytkownik['uzy_nazwisko']; ?></td>
						<td><?= $uzytkownik['uzy_imie']; ?></td>
						<td><?= $uzytkownik['kuz_email']; ?></td>
						<td><?= $wszystkie_statusy[$uzytkownik['kuz_status_konta']]; ?></td>
						<td >
							<a href="<?= Router::utworz_link(array('controller' => 'Uzytkownicy', 'action' => 'edytuj', 'id' => $uzytkownik['uzy_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'Uzytkownicy', 'action' => 'usun', 'id' => $uzytkownik['uzy_id'])); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
							<?php if($uzytkownik['kuz_status_konta'] == 'NOWE'): ?>
								<a href="<?= Router::utworz_link(array('controller' => 'Uzytkownicy', 'action' => 'aktywuj', 'id' => $uzytkownik['uzy_id'])); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span></a>
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