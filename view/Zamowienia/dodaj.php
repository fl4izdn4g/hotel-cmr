<div class="box box-solid">
	<div class="box-header with-border">
		<?php 
	
			$potrawa_link = array(
				'controller' => 'Zamowienia',
				'action' => 'potrawa',
			);
			
			if(!empty(View::pobierz('z[potrawy]'))) {
				$potrawa_link['z[potrawy]'] = View::pobierz('z[potrawy]'); 
			}
		?>
	
	
		<a href="<?= Router::utworz_link($potrawa_link); ?>" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr> 
						<th class="id-column">#</th>
						<th class="photo-column">Zdjęcie</th> 
						<th>Nazwa</th>
						<th style="width: 120px">Cena za sztukę</th>
						<th style="width: 50px">Ilość</th>
						<th style="width: 120px">Cena łącznie</th>
					</tr> 
				</thead> 
				<tbody>
					<?php 
						$potrawy = View::pobierz('potrawy');
						if(!empty($potrawy)): 
					?>
						<?php foreach($potrawy as $p): ?>
						<tr> 
							<th scope="row" class="id-column"><?php echo $p['pot_id']; ?></th>
							<td><img src="<?= Router::poprawny_url_obrazka($p['pot_zdjecie'], 'Potrawy', 'small'); ?>" alt="" /></td>
							<td><?= $p['pot_nazwa']; ?></td> 
							<td><?= $p['pot_cena']; ?></td>
							<td><?= $p['pot_ilosc']; ?></td>
							<td><?= $p['pot_cena_lacznie']; ?></td>
						</tr>
						<?php endforeach; ?>
					<?php 
						endif;
					?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td colspan="3"><span class="lead">Razem: <strong><?= empty(View::pobierz('cena_lacznie')) ? '0' : View::pobierz('cena_lacznie'); ?></strong></span></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="box-footer">
		<?php if(!empty(View::pobierz('z[potrawy]'))): ?>
			<?php 
				$osoba_link = array(
					'controller' => 'Zamowienia',
					'action' => 'osoba',
					'z[potrawy]' => View::pobierz('z[potrawy]'),
				);
			?>
			<a href="<?= Router::utworz_link($osoba_link); ?>" class="btn btn-success">Dalej</a>
		<?php endif; ?>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>

