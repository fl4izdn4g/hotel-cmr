<form name="produktyFormularz" action="<?= Router::utworz_link(array('controller' => 'Produkty', 'action' => 'edytuj', 'id' => View::pobierz('prod_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Grupa produktów',
				  			'id' => 'produktGrupa',
				  			'name' => 'prod_grpp_id',
				  			'wartosc_pusta' => 'Wybierz grupę produktów',
				  			'wartosci' => View::pobierz('wszystkie_grupy_produktow'),
				  			'aktualna_wartosc' => $dane['prod_grpp_id'],
				  			'blad' => $bledy['prod_grpp_id'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  		'nazwa' => 'Nazwa',
				  		'id' => 'produktNazwa',
				  		'name' => 'prod_nazwa',
				  		'aktualna_wartosc' => $dane['prod_nazwa'],
				  		'blad' => $bledy['prod_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Cena jednostkowa',
			  			'id' => 'produktCenaJednostkowa',
			  			'name' => 'prod_cena_jednostkowa_netto',
			  			'aktualna_wartosc' => $dane['prod_cena_jednostkowa_netto'],
			  			'blad' => $bledy['prod_cena_jednostkowa_netto']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Jednostka',
				  			'id' => 'produktJednostka',
				  			'name' => 'prod_jednostka',
				  			'wartosc_pusta' => 'Wybierz jednostkę',
				  			'wartosci' => View::pobierz('wszystkie_jednostki'),
				  			'aktualna_wartosc' => $dane['prod_jednostka'],
				  			'blad' => $bledy['prod_jednostka'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Opis',
				  			'id' => 'produktOpis',
				  			'name' => 'prod_opis',
				  			'aktualna_wartosc' => $dane['prod_opis'],
				  			'blad' => $bledy['prod_opis']
				  	);
				  	Html::zrob_element_formularza('html-textarea', $parametry);
				  ?>
				 		  
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Ikona',
							'id' => 'produktIkona',
							'name' => 'prod_ikona',
							'aktualna_wartosc' => $dane['prod_ikona'],
							'blad' => $bledy['prod_ikona'],
							'kontroler' => 'Produkty'
					);
					Html::zrob_element_formularza('upload', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
	  <button name="produktyFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
	  <a href="<?= Router::utworz_link(array('controller' => 'Produkty'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>
	