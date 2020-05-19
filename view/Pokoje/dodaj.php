<form name="pokojFormularz" action="<?= Router::utworz_link(array('controller' => 'Pokoje', 'action' => 'dodaj')) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Grupa',
				  			'id' => 'pokojGrupa',
				  			'name' => 'pok_grp_id',
				  			'wartosc_pusta' => 'Wybierz grupę pokoi',
				  			'wartosci' => View::pobierz('wszystkie_grupy_pokoi'),
				  			'aktualna_wartosc' => $dane['pok_grp_id'],
				  			'blad' => $bledy['pok_grp_id'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  		'nazwa' => 'Numer pokoju',
				  		'id' => 'pokojNazwa',
				  		'name' => 'pok_numer',
				  		'aktualna_wartosc' => $dane['pok_numer'],
				  		'blad' => $bledy['pok_numer']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Liczba osób',
			  			'id' => 'pokojLiczbaOsob',
			  			'name' => 'pok_liczba_osob',
			  			'aktualna_wartosc' => $dane['pok_liczba_osob'],
			  			'blad' => $bledy['pok_liczba_osob']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Piętro',
			  			'id' => 'pokojPietro',
			  			'name' => 'pok_pietro',
			  			'aktualna_wartosc' => $dane['pok_pietro'],
			  			'blad' => $bledy['pok_pietro']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	
				  ?>
				 		  
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
			  			'nazwa' => 'Zdjęcie',
			  			'id' => 'pokojZdjecie',
			  			'name' => 'pok_zdjecie',
			  			'aktualna_wartosc' => $dane['pok_zdjecie'],
			  			'blad' => $bledy['pok_zdjecie'],
				  		'kontroler' => 'Pokoje'
				  	);
				  	Html::zrob_element_formularza('upload', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
	  <button name="pokojFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
	  <a href="<?= Router::utworz_link(array('controller' => 'Pokoje'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>
	