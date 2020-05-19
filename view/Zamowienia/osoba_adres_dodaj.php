<?php 
	$form_post = array(
		'controller' => 'Zamowienia', 
		'action' => 'osoba_adres_dodaj',
		'z[potrawy]' => View::pobierz('z[potrawy]'),
		'z[osoba]' => View::pobierz('z[osoba]'),
		'z[faktura]' => View::pobierz('z[faktura]'),
	);

?>

<form name="rpFormularz" action="<?= Router::utworz_link($form_post); ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
			  <?php 
			  	$dane = View::pobierz('dane_do_formularza');
			  	$bledy = View::pobierz('bledy_walidacji');
			  
			  	$parametry = array(
			  			'nazwa' => 'Ulica',
			  			'id' => 'adrUlica',
			  			'name' => 'adr_ulica',
			  			'aktualna_wartosc' => $dane['adr_ulica'],
			  			'blad' => $bledy['adr_ulica']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Numer domu',
			  			'id' => 'adrNumerDomu',
			  			'name' => 'adr_numer_domu',
			  			'aktualna_wartosc' => $dane['adr_numer_domu'],
			  			'blad' => $bledy['adr_numer_domu']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Numer mieszkania',
			  			'id' => 'adrNumerMieszkania',
			  			'name' => 'adr_numer_mieszkania',
			  			'aktualna_wartosc' => $dane['adr_numer_mieszkania'],
			  			'blad' => $bledy['adr_numer_mieszkania']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Kod pocztowy',
			  			'id' => 'adrKodPocztowy',
			  			'name' => 'adr_kod_pocztowy',
			  			'aktualna_wartosc' => $dane['adr_kod_pocztowy'],
			  			'blad' => $bledy['adr_kod_pocztowy']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Miejscowość',
			  			'id' => 'adrMiejscowosc',
			  			'name' => 'adr_miejscowosc',
			  			'aktualna_wartosc' => $dane['adr_miejscowosc'],
			  			'blad' => $bledy['adr_miejscowosc']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Wojewodztwo',
			  			'id' => 'adrWojewodztwo',
			  			'name' => 'adr_wojewodztwo',
			  			'wartosc_pusta' => 'Wybierz wojewodztwo',
			  			'wartosci' => View::pobierz('wojewodztwa'),
			  			'aktualna_wartosc' => $dane['adr_wojewodztwo'],
			  			'blad' => $bledy['adr_wojewodztwo'],
			  			'czy_select2' => true,
			  	);
			  	Html::zrob_element_formularza_select($parametry);
			  		
			  		  	
			 ?>
			 </div>
			 <div class="col-md-6">	
		
			
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="zpFormularz" value="dodaj" type="submit" class="btn btn-success">Dalej</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Zamowienia'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>