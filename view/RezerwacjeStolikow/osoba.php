<?php 
	$form_post = array(
		'controller' => 'RezerwacjeStolikow', 
		'action' => 'osoba',
		'r[data]' => View::pobierz('r[data]'),
		'r[sala]' => View::pobierz('r[sala]'),
		'r[stolik]' => View::pobierz('r[stolik]'),
		'r[osoba]' => View::pobierz('r[osoba]'),
		'r[liczba]' => View::pobierz('r[liczba]'),
		'r[faktura]' => View::pobierz('r[faktura]')
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
			  			'nazwa' => 'Rezerwujący',
			  			'id' => 'rsRezerwujacy',
			  			'name' => 'rs_uzy_id',
			  			'wartosc_pusta' => 'Wybierz rezerwującego',
			  			'wartosci' => View::pobierz('uzytkownicy'),
			  			'aktualna_wartosc' => $dane['rs_uzy_id'],
			  			'blad' => $bledy['rs_uzy_id'],
			  			'czy_select2' => true,
			  	);
			  	Html::zrob_element_formularza_select($parametry);
			  		
			  	$parametry = array(
			  			'nazwa' => 'Liczba osób',
			  			'id' => 'rsLiczbaOsob',
			  			'name' => 'rs_liczba_osob',
			  			'aktualna_wartosc' => $dane['rs_liczba_osob'],
			  			'blad' => $bledy['rs_liczba_osob']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  		
			  	$parametry = array(
			  			'nazwa' => 'Wystaw fakturę',
			  			'id' => 'rsFaktura',
			  			'name' => 'rs_czy_faktura',
			  			'aktualna_wartosc' => $dane['rs_czy_faktura'],
			  			'blad' => $bledy['rs_czy_faktura']
			  	);
			  	Html::zrob_element_formularza('checkbox', $parametry);
			  	
			 ?>
			 </div>
			 <div class="col-md-6">	
		
			
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="rpFormularz" value="dodaj" type="submit" class="btn btn-success">Dalej</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>