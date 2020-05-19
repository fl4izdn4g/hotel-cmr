<form name="grupaFormularz" action="<?= Router::utworz_link(array('controller' => 'GrupyProduktow', 'action' => 'edytuj', 'id' => View::pobierz('grpp_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa grupy',
				  		'id' => 'grupaNazwa',
				  		'name' => 'grpp_nazwa',
				  		'aktualna_wartosc' => $dane['grpp_nazwa'],
				  		'blad' => $bledy['grpp_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Opis',
			  			'id' => 'grupaOpis',
			  			'name' => 'grpp_opis',
			  			'aktualna_wartosc' => $dane['grpp_opis'],
			  			'blad' => $bledy['grpp_opis']
				  	);
				  	Html::zrob_element_formularza('html-textarea', $parametry);
				  	
				  	
				  ?>
				 		  
				 
				
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Ikona',
							'id' => 'grupaIkona',
							'name' => 'grpp_ikona',
							'aktualna_wartosc' => $dane['grpp_ikona'],
							'blad' => $bledy['grpp_ikona'],
							'kontroler' => 'GrupyProduktow'
					);
					Html::zrob_element_formularza('upload', $parametry);
				?>
			
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="grupaFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'GrupyProduktow'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>