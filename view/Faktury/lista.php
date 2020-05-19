<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped ">
			<thead>
				<tr> 
					<th class="id-column">#</th> 
					<th>Numer identyfikacyjny</th>
					<th>Data wystawienia</th>
					<th>Odbiorca</th>
					<th>Cena netto</th>
					<th>Cena brutto</th>	
					<th>Anulowana</th>				
					<th class="action-column">Akcje</th>
				</tr> 
			</thead> 
			<tbody>
				<?php 
					$faktury = View::pobierz('faktury');
					if(!empty($faktury)): 
				?>
					<?php foreach($faktury as $f): ?>
					<tr> 
						<th scope="row" class="id-column"><?php echo $f['fak_id']; ?></th> 
						<td><?= $f['fak_numer_identyfikacyjny']; ?></td>
						<td><?= $f['fak_data_wystawienia']; ?></td>
						<td><?= $f['fak_odbiorca']; ?></td>
						<td><?= $f['fak_naleznosc_ogolem_netto']; ?></td>
						<td><?= $f['fak_naleznosc_ogolem_brutto']; ?></td>
						<td><?= $f['fak_anulowana']; ?></td>
						<td class="action-column">
							<a href="<?= Router::utworz_link(array('controller' => 'Faktury', 'action' => 'podglad', 'id' => $f['fak_id'])); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-list-alt"></span></a>
							<a href="<?= Router::utworz_link(array('controller' => 'Faktury', 'action' => 'pdf', 'id' => $f['fak_id'])); ?>" class="btn btn-danger"><span class="fa fw fa-file-pdf-o"></span></a>
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