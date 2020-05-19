<?php 
	$form_link = array(
		'controller' => 'Rezerwacje', 
		'action' => 'podsumowanie',
		'r[grupa]' => View::pobierz('r[grupa]'),
		'r[od]' => View::pobierz('r[od]'),
		'r[do]' => View::pobierz('r[do]'),
		'r[pokoj]' => View::pobierz('r[pokoj]'),
		'r[osoba]' => View::pobierz('r[osoba]'),
		'r[liczba]' => View::pobierz('r[liczba]'),
		'r[faktura]' => View::pobierz('r[faktura]'),
		'r[adres]' => View::pobierz('r[adres]'),
	);



?>


<form name="rpFormularz" action="<?= Router::utworz_link($form_link) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<p class="lead">Informacje o rezerwacji</p>
				<dl>
					<dt>Rozpoczęcie pobytu</dt>
	                <dd><?= View::pobierz('data_od'); ?></dd>
	                <dt>Zakończenie pobytu</dt>
	                <dd><?= View::pobierz('data_do'); ?></dd>
	                <dt>Liczba dni</dt>
	                <dd><?= View::pobierz('liczba_dni'); ?></dd>
				</dl>
              
				<p class="lead">Zarezerwowany pokój</p>
				<dl>
					<?php 
						$grupa = View::pobierz('grupa');
						$pokoj = View::pobierz('pokoj');
					?>
					<dt>Grupa</dt>
	                <dd><?= $grupa['grp_nazwa']; ?></dd>
	                <dt>Numer</dt>
	                <dd><?= $pokoj['pok_numer']; ?></dd>
	                <dt>Piętro</dt>
	                <dd><?= $pokoj['pok_pietro'] ?></dd>
				</dl>
						
				<p class="lead">Rezerwujący</p>
				<dl>
					<?php 
						$osoba = View::pobierz('osoba');
					?>
					<dt>Imię i nazwisko</dt>
	                <dd><?= $osoba['uzy_imie'].' '.$osoba['uzy_nazwisko']; ?></dd>
	                <dt>Email</dt>
	                <dd><?= $osoba['kuz_email']; ?></dd>
	                <dt>PESEL</dt>
	                <dd><?= (empty($osoba['gh_pesel'])) ? 'brak' : $osoba['gh_pesel'];  ?></dd>
				</dl>
			
			</div>
			<div class="col-md-6">	
				<p class="lead">Faktura</p>
				<dl>
					<dt>Wygenerować fakturę</dt>
	                <dd><?= View::pobierz('faktura'); ?></dd>
	            </dl>
	            
	            <?php if(View::pobierz('faktura') == 'Tak'): ?>
	            	<?php 
	            		$adres = View::pobierz('adres');
	            	?>
			 		<p class="lead">Adres do faktury</p>
					<dl>
						<dt>Ulica</dt>
						<?php $mieszkanie = (empty($adres['numer_mieszkania'])) ? '' : '/'.$adres['numer_mieszkania']; ?>
		                <dd><?= $adres['adr_ulica'].' '.$adres['adr_numer_domu'].$mieszkanie; ?></dd>
		                <dt>Miejscowość</dt>
		                <dd><?= $adres['adr_kod_pocztowy'].' '.$adres['adr_miejscowosc']; ?></dd>
		            </dl>
				<?php endif; ?>
				
				
				<p class="lead">Należności</p>
				<dl>
					<dt>Cena netto</dt>
	                <dd><?= View::pobierz('cena_netto'); ?></dd>
	                <dt>Cena brutto</dt>
	                <dd><?= View::pobierz('cena_brutto'); ?></dd>
				</dl>
			 	
			 	<?php if(!empty(View::pobierz('cena_netto_dostawka'))): ?>
			 		<p class="lead">Dostawka</p>
			 		<dl>
						<dt>Cena netto</dt>
		                <dd><?= View::pobierz('cena_netto_dostawka'); ?></dd>
		                <dt>Cena brutto</dt>
		                <dd><?= View::pobierz('cena_brutto_dostawka'); ?></dd>
					</dl>
			 	<?php endif; ?>
			 				 	
			 	<p class="lead">Do zapłaty: <strong><?= View::pobierz('cena_calkowita'); ?></strong></p>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="rpFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>