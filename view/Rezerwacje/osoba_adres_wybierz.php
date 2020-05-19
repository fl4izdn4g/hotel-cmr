<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th>
						<th>Ulica</th> 
						<th>Numer domu</th>
						<th>Numer mieszkania</th>
						<th>Kod pocztowy</th>
						<th>Miejscowość</th>
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
							<td class="action-column">
								<?php 
									$link = array(
										'controller' => 'Rezerwacje',
										'action' => 'podsumowanie',
										'r[grupa]' => View::pobierz('r[grupa]'),
										'r[od]' => View::pobierz('r[od]'),
										'r[do]' => View::pobierz('r[do]'),
										'r[pokoj]' => View::pobierz('r[pokoj]'),
										'r[osoba]' => View::pobierz('r[osoba]'),
										'r[liczba]' => View::pobierz('r[liczba]'),
										'r[faktura]' => View::pobierz('r[faktura]'),
										'r[adres]' => $a['adr_id']
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

