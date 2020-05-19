<?php 
	$form_post = array(
		'controller' => 'Zamowienia', 
		'action' => 'potrawa',
	);
	if(!empty(View::pobierz('z[potrawy]'))) {
		$form_post['z[potrawy]'] = View::pobierz('z[potrawy]');
	}
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
			  			'nazwa' => 'Potrawa',
			  			'id' => 'zpPotrawa',
			  			'name' => 'zp_potrawa',
			  			'wartosc_pusta' => 'Wybierz potrawę',
			  			'wartosci' => View::pobierz('potrawy'),
			  			'aktualna_wartosc' => $dane['zp_potrawa'],
			  			'blad' => $bledy['zp_potrawa'],
			  			'czy_select2' => true,
			  	);
			  	Html::zrob_element_formularza_select($parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Ilość',
			  			'id' => 'zpIlosc',
			  			'name' => 'zp_ilosc',
			  			'aktualna_wartosc' => $dane['zp_ilosc'],
			  			'blad' => $bledy['zp_ilosc']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			 ?>
			 </div>
			 <div class="col-md-6">	
		
			
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="zpFormularz" value="dodaj" type="submit" class="btn btn-success">Dalej</button>
	  	<?php 
			$anuluj_link = array(
				'controller' => 'Zamowienia', 
				'action' => 'dodaj',
			);
			if(!empty(View::pobierz('z[potrawy]'))) {
				$anuluj_link['z[potrawy]'] = View::pobierz('z[potrawy]');
			}
	  	?>
	  	
	  	<a href="<?= Router::utworz_link($anuluj_link)?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>