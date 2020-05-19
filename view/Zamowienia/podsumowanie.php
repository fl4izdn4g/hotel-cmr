<?php 
	$form_link = array(
		'controller' => 'Zamowienia', 
		'action' => 'podsumowanie',
		'z[potrawy]' => View::pobierz('z[potrawy]'),
		'z[osoba]' => View::pobierz('z[osoba]'),
		'z[adres]' => View::pobierz('z[adres]'),
		'z[faktura]' => View::pobierz('z[faktura]'),
	);



?>


<form name="zpFormularz" action="<?= Router::utworz_link($form_link) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<p class="lead">Zamówione potrawy</p>
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
		</div>		
		<div class="row">
			<div class="col-md-3">		             
				<p class="lead">Zamawiający</p>
				<dl>
					<?php 
						$zamawiajacy = View::pobierz('osoba');
						$adres = View::pobierz('adres');
					?>
					<dt>Zamawiający</dt>
	                <dd><?= $zamawiajacy['uzy_imie']; ?> <?= $zamawiajacy['uzy_nazwisko']; ?></dd>
	                <dt>Email</dt>
	                <dd><?= $zamawiajacy['kuz_email']; ?></dd>
	            </dl>
	        </div>
	        <div class="col-md-3">
	            <p class="lead">Adres dostawy</p>
	            <dl>
	                <dt>Ulica</dt>
	                <dd><?= $adres['adr_ulica'] ?> <?= $adres['adr_numer_domu'] ?></dd>
	                <dt>Miejscowość</dt>
	                <dd><?= $adres['adr_kod_pocztowy'] ?> <?= $adres['adr_miejscowosc'] ?></dd>
				</dl>
			</div>
			<div class="col-md-3">
	            <p class="lead">Faktura</p>
	            <dl>
	                <dt>Wystawić fakturę</dt>
	                <dd><?= View::pobierz('faktura') ?></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="zpFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>