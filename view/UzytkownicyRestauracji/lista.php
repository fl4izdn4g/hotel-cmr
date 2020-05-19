<div class="box box-solid">
	<div class="box-header with-border">
		<?php 
			$add_link = array(
				'controller' => 'UzytkownicyRestauracji',
				'action' => 'dodaj',
			);
			
			if(!empty(View::pobierz('parent_menu'))) {
				$add_link['parent'] = View::pobierz('parent_menu');
			}
		?>
	
		<a href="<?= Router::utworz_link($add_link); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
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
						<td class="action-column">
							<?php 
								$edit_link = array(
									'controller' => 'UzytkownicyRestauracji',
									'action' => 'edytuj',
									'id' => $uzytkownik['uzy_id'],
								);
								
								if(!empty(View::pobierz('parent_menu'))) {
									$edit_link['parent'] = View::pobierz('parent_menu');
								}
							?>
							<a href="<?= Router::utworz_link($edit_link); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
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