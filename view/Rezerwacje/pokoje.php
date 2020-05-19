<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th>
						<th class="photo-column">Zdjęcie</th> 
						<th>Grupa pokoi</th>
						<th>Numer pokoju</th>
						<th>Piętro</th>
						<th>Liczba osób</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$pokoje = View::pobierz('pokoje');
						if(!empty($pokoje)): 
					?>
						<?php foreach($pokoje as $p): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $p['pok_id']; ?></th>
							<td><img src="<?= Router::poprawny_url_obrazka($p['pok_zdjecie'], 'Pokoje', 'small'); ?>" alt="" /></td>
							<td><?= $p['grp_nazwa']; ?></td> 
							<td><?= $p['pok_numer']; ?></td>
							<td><?= $p['pok_pietro']; ?></td>
							<td><?= $p['pok_liczba_osob']; ?></td>
							<td class="action-column">
								<?php 
									$link = array(
										'controller' => 'Rezerwacje',
										'action' => 'osoba',
										'r[grupa]' => View::pobierz('r[grupa]'),
										'r[od]' => View::pobierz('r[od]'),
										'r[do]' => View::pobierz('r[do]'),
										'r[pokoj]' => $p['pok_id']
									);
								?>							
								<a href="<?= Router::utworz_link($link); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span></a>
							</td>
						</tr>
						<?php endforeach; ?>
					<?php 
						endif;
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

