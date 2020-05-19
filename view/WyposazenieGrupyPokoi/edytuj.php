<form name="wyposazenieFormularz" action="<?= Router::utworz_link(array('controller' => 'WyposazenieGrupyPokoi', 'action' => 'edytuj', 'id' => View::pobierz('gxw_id'), 'group_id' => View::pobierz('group_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa wyposazenia',
				  		'id' => 'wyposazenieNazwa',
				  		'name' => 'gxw_wyp_id',
				  		'wartosc_pusta' => 'Wybierz wyposażenie',
				  		'wartosci' => View::pobierz('wszystkie_wyposazenia'),
				  		'aktualna_wartosc' => $dane['gxw_wyp_id'],
				  		'blad' => $bledy['gxw_wyp_id'],
				  		'czy_select2' => true
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  ?>
				 		  
				
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Ilość',
							'id' => 'wyposazenieIlosc',
							'name' => 'gxw_ilosc_wyposazenia',
							'aktualna_wartosc' => $dane['gxw_ilosc_wyposazenia'],
							'blad' => $bledy['gxw_ilosc_wyposazenia'],
					);
					Html::zrob_element_formularza('text', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="wyposazenieFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'WyposazenieGrupyPokoi', 'group_id' => View::pobierz('group_id')))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>