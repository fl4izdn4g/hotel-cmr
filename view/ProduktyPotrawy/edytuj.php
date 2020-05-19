<form name="potrawyFormularz" action="<?= Router::utworz_link(array('controller' => 'ProduktyPotrawy', 'action' => 'edytuj', 'id' => View::pobierz('potxprod_id'), 'meal_id' => View::pobierz('meal_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa produktu',
				  		'id' => 'produktNazwa',
				  		'name' => 'potxprod_prod_id',
				  		'wartosc_pusta' => 'Wybierz produkt',
				  		'wartosci' => View::pobierz('wszystkie_produkty'),
				  		'aktualna_wartosc' => $dane['potxprod_prod_id'],
				  		'blad' => $bledy['potxprod_prod_id'],
				  		'czy_select2' => true
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  ?>
				 		  
				
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Wykorzystywana ilość',
							'id' => 'produktIlosc',
							'name' => 'potxprod_wykorzystywana_ilosc',
							'aktualna_wartosc' => $dane['potxprod_wykorzystywana_ilosc'],
							'blad' => $bledy['potxprod_wykorzystywana_ilosc'],
					);
					Html::zrob_element_formularza('text', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="potrawyFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'ProduktyPotrawy', 'meal_id' => View::pobierz('meal_id')))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>