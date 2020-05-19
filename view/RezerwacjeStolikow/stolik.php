<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th>
						<th>Sala</th> 
						<th>Numer stolika</th>
						<th>Liczba miejsc</th>
						<th>Opis</th>
						<th>Cena netto</th>
						<th>Cena brutto</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$stoliki = View::pobierz('stoliki');
						if(!empty($stoliki)): 
					?>
						<?php foreach($stoliki as $s): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $s['sto_id']; ?></th>
							<td><?= $s['sar_nazwa'] ?></td>
							<td><?= $s['sto_numer'] ?></td>
							<td><?= $s['sto_liczba_miejsc']; ?></td> 
							<td><?= $s['sto_polozenie']; ?></td>
							<td><?= $s['sto_cena_netto']; ?></td>
							<td><?= $s['sto_cena_brutto']; ?></td>
							<td class="action-column">
								<?php 
									$link = array(
										'controller' => 'RezerwacjeStolikow',
										'action' => 'osoba',
										'r[data]' => View::pobierz('r[data]'),
										'r[sala]' => View::pobierz('r[sala]'),
										'r[stolik]' => $s['sto_id']
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

