<form name="potrawaFormularz" action="<?= Router::utworz_link(array('controller' => 'Potrawy', 'action' => 'edytuj', 'id' => View::pobierz('pot_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-header">
		<a href="<?= Router::utworz_link(array('controller' => 'ProduktyPotrawy', 'action' => 'lista', 'meal_id' => View::pobierz('pot_id')))?>" class="btn btn-primary pull-right"><span class="fa fa-fw fa-cubes"></span> Produkty dla potrawy</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa',
				  		'id' => 'potrawaNazwa',
				  		'name' => 'pot_nazwa',
				  		'aktualna_wartosc' => $dane['pot_nazwa'],
				  		'blad' => $bledy['pot_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Potrawa wegetariańska',
				  			'id' => 'potrawaWegetarianska',
				  			'name' => 'pot_wegetarianska',
				  			'aktualna_wartosc' => $dane['pot_wegetarianska'],
				  			'blad' => $bledy['pot_wegetarianska']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Potrawa bezglutenowa',
				  			'id' => 'potrawaBezglutenowa',
				  			'name' => 'pot_bezglutenowa',
				  			'aktualna_wartosc' => $dane['pot_bezglutenowa'],
				  			'blad' => $bledy['pot_bezglutenowa']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				  	
				  	
				  	$parametry = array(
				  			'nazwa' => 'Opis',
				  			'id' => 'potrawaOpis',
				  			'name' => 'pot_opis',
				  			'aktualna_wartosc' => $dane['pot_opis'],
				  			'blad' => $bledy['pot_opis']
				  	);
				  	Html::zrob_element_formularza('html-textarea', $parametry);
				  ?>
				 		  
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Zdjęcie',
							'id' => 'potrawaZdjecie',
							'name' => 'pot_zdjecie',
							'aktualna_wartosc' => $dane['pot_zdjecie'],
							'blad' => $bledy['pot_zdjecie'],
							'kontroler' => 'Potrawy'
					);
					Html::zrob_element_formularza('upload', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
	  <button name="potrawaFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
	  <a href="<?= Router::utworz_link(array('controller' => 'Potrawy'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>