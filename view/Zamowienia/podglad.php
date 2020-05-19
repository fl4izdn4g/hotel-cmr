<div class="box box-solid">
	<div class="box-header">
		<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia')); ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-arrow-left"></span></a>
		 
        <?php if(!empty(View::pobierz('faktura_id'))): ?>
        	<a href="<?= Router::utworz_link(array('controller' => 'Faktury', 'action' => 'pdf', 'id' => View::pobierz('faktura_id'))); ?>" class="btn btn-danger pull-right"><span class="fa fw fa-file-pdf-o"></span></a>
		<?php endif; ?>
				
	</div>
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
								<th style="width: 50px">Ilość</th>
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
									<td><?= $p['zxp_liczba_sztuk']; ?></td>
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
	                <dd><?= $adres['adr_ulica'] ?></dd>
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
			<div class="col-md-3">
	            <p class="lead">Należności</p>
	            <dl>
	                <dt>Łącznie netto</dt>
	                <dd><?= View::pobierz('cena_lacznie_netto') ?></dd>
	                <dt>Łącznie brutto</dt>
	                <dd><?= View::pobierz('cena_lacznie_brutto') ?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>