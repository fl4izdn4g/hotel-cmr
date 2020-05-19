<form name="wyposazenieFormularz" action="<?= Router::utworz_link(array('controller' => 'Wyposazenie', 'action' => 'edytuj', 'id' => View::pobierz('wyp_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">		
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa',
				  		'id' => 'wyposazenieNazwa',
				  		'name' => 'wyp_nazwa',
				  		'aktualna_wartosc' => $dane['wyp_nazwa'],
				  		'blad' => $bledy['wyp_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Opis',
			  			'id' => 'wyposazenieOpis',
			  			'name' => 'wyp_opis',
			  			'aktualna_wartosc' => $dane['wyp_opis'],
			  			'blad' => $bledy['wyp_opis'],
				  		'wysokosc_pola' => '150px;'
				  	);
				  	Html::zrob_element_formularza('textarea', $parametry);
				  	
				  	
				  ?>
				 		  
				
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Ikona',
							'id' => 'wyposazenieIkona',
							'name' => 'wyp_ikona',
							'aktualna_wartosc' => $dane['wyp_ikona'],
							'blad' => $bledy['wyp_ikona'],
							'kontroler' => 'Wyposazenie'
					);
					Html::zrob_element_formularza('upload', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="wyposazenieFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Wyposazenie'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>