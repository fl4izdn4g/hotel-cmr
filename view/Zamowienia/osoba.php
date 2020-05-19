<?php 
	$form_post = array(
		'controller' => 'Zamowienia', 
		'action' => 'osoba',
		'z[potrawy]' => View::pobierz('z[potrawy]'),
	);

?>

<form name="zpFormularz" action="<?= Router::utworz_link($form_post); ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
			  <?php 
			  	$dane = View::pobierz('dane_do_formularza');
			  	$bledy = View::pobierz('bledy_walidacji');
			  
			  	$parametry = array(
			  			'nazwa' => 'Zamawiający',
			  			'id' => 'zpZamawiajacy',
			  			'name' => 'zp_uzy_id',
			  			'wartosc_pusta' => 'Wybierz zamawiającego',
			  			'wartosci' => View::pobierz('zamawiajacy'),
			  			'aktualna_wartosc' => $dane['zp_uzy_id'],
			  			'blad' => $bledy['zp_uzy_id'],
			  			'czy_select2' => true,
			  	);
			  	Html::zrob_element_formularza_select($parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Wystaw fakturę',
			  			'id' => 'zpFaktura',
			  			'name' => 'zp_czy_faktura',
			  			'aktualna_wartosc' => $dane['zp_czy_faktura'],
			  			'blad' => $bledy['zp_czy_faktura']
			  	);
			  	Html::zrob_element_formularza('checkbox', $parametry);
			  	
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