<div class="box box-solid">
	<div class="box-header with-border">
		<?php 
			$parent_menu = View::pobierz('parent_menu');
			if(!empty($parent_menu) && in_array($parent_menu, array('kuchnia', 'restauracja'))) {
				$controller = 'UzytkownicyRestauracji';		   	
			}
			else {
				$controller = 'Uzytkownicy';
			}
		
			$return_link = array(
				'controller' => $controller, 
				'action' => 'edytuj',
				'id' => View::pobierz('user_id')
			);
			
			$add_link = array(
				'controller' => 'AdresyUzytkownika', 
				'action' => 'dodaj', 
				'user_id' => View::pobierz('user_id')
			);
			
			if(!empty($parent_menu)) {
				$return_link['parent'] = $parent_menu;
				$add_link['parent'] = $parent_menu;
			}
			
			
		?>
		
		<a href="<?= Router::utworz_link($return_link); ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-arrow-left"></span></a>
		<a href="<?= Router::utworz_link($add_link); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th>
					<th>Ulica</th> 
					<th>Numer domu</th>
					<th>Numer mieszkania</th>
					<th>Kod pocztowy</th>
					<th>Miejscowość</th>
					<th>Domyślny</th>
					<th class="action-column">Akcje</th>
				</tr> 
			</thead>
			<tbody>
				<?php 
					$adresy = View::pobierz('adresy');
					if(!empty($adresy)): 
				?>
					<?php foreach($adresy as $a): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $a['adr_id']; ?></th>
						<td><?= $a['adr_ulica'] ?></td>
						<td><?= $a['adr_numer_domu'] ?></td>
						<td><?= $a['adr_numer_mieszkania']; ?></td> 
						<td><?= $a['adr_kod_pocztowy']; ?></td>
						<td><?= $a['adr_miejscowosc']; ?></td>
						<td><?= $a['uxa_domyslny']; ?></td>
						<td class="action-column">
							<?php 
								$edit_link = array(
									'controller' => 'AdresyUzytkownika',
									'action' => 'edytuj',
									'user_id' => View::pobierz('user_id'),
									'id' => $a['adr_id']
								);
								
								$delete_link = array(
									'controller' => 'AdresyUzytkownika',
									'action' => 'usun',
									'user_id' => View::pobierz('user_id'),
									'id' => $a['adr_id']
								);
								
								if(!empty($parent_menu)) {
									$edit_link['parent'] = $parent_menu;
									$delete_link['parent'] = $parent_menu;
								}
							
							?>
						
							<a href="<?= Router::utworz_link($edit_link) ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= Router::utworz_link($delete_link); ?>" class="btn btn-danger can-delete"><span class="glyphicon glyphicon-remove"></span></a>
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