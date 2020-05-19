<?php 
	$form_post = array(
		'controller' => 'Rezerwacje', 
		'action' => 'osoba',
		'r[grupa]' => View::pobierz('r[grupa]'),
		'r[od]' => View::pobierz('r[od]'),
		'r[do]' => View::pobierz('r[do]'),
		'r[pokoj]' => View::pobierz('r[pokoj]')
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
			  			'nazwa' => 'Gość hotelowy',
			  			'id' => 'rpGosc',
			  			'name' => 'rp_uzy_id',
			  			'wartosc_pusta' => 'Wybierz gościa',
			  			'wartosci' => View::pobierz('goscie'),
			  			'aktualna_wartosc' => $dane['rp_uzy_id'],
			  			'blad' => $bledy['rp_uzy_id'],
			  			'czy_select2' => true,
			  	);
			  	Html::zrob_element_formularza_select($parametry);
			  		
			  	$parametry = array(
			  			'nazwa' => 'Liczba osób',
			  			'id' => 'rpLiczbaOsob',
			  			'name' => 'rp_liczba_osob',
			  			'aktualna_wartosc' => $dane['rp_liczba_osob'],
			  			'blad' => $bledy['rp_liczba_osob']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  		
			  	$parametry = array(
			  			'nazwa' => 'Wystaw fakturę',
			  			'id' => 'rpFaktura',
			  			'name' => 'rp_czy_faktura',
			  			'aktualna_wartosc' => $dane['rp_czy_faktura'],
			  			'blad' => $bledy['rp_czy_faktura']
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