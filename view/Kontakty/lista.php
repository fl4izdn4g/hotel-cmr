<div class="box box-solid">
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th> 
						<th>Tytuł</th>
						<th>Kategoria</th>
						<th>Email</th>
						<th>Data dodania</th>
						<th class="action-column">Akcje</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$kontakty = View::pobierz('kontakty');
						$kategorie = View::pobierz('kategorie');
						if(!empty($kontakty)): 
					?>
						<?php foreach($kontakty as $k): ?>
						<tr> 
							<?php 
	                      		$color = '#666';
	                      		if(!empty($k['kon_data_przeczytania'])) {
	                      			$color = '#ddd';
	                      		}
	                      	?>
						
							<th scope="row" class="id-column">    <span class="fa fa-fw fa-envelope" style="font-size: 20px; color: <?= $color; ?> "> </span></th> 
							<td><?php echo $k['kon_tytul']; ?></td>
							<td><?= $kategorie[$k['kon_kategoria']]; ?></td>
							<td><?= $k['kon_email']; ?></td>
							<td><?= $k['kon_data_dodania']; ?></td>
							<td class="action-column">
								<a href="<?= Router::utworz_link(array('controller' => 'Kontakty', 'action' => 'wyswietl', 'id' => $k['kon_id'], )); ?>" class="btn btn-info"><span class="glyphicon glyphicon-eye-open"></span></a>
								<a href="<?= Router::utworz_link(array('controller' => 'Kontakty', 'action' => 'odpowiedz', 'id' => $k['kon_id'], )); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-comment"></span></a>
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
