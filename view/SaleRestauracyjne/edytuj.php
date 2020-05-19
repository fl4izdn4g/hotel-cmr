<form name="saleFormularz" action="<?= Router::utworz_link(array('controller' => 'SaleRestauracyjne', 'action' => 'edytuj', 'id' => View::pobierz('sar_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">		
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa',
				  		'id' => 'salaNazwa',
				  		'name' => 'sar_nazwa',
				  		'aktualna_wartosc' => $dane['sar_nazwa'],
				  		'blad' => $bledy['sar_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Opis',
				  			'id' => 'salaOpis',
				  			'name' => 'sar_opis',
				  			'aktualna_wartosc' => $dane['sar_opis'],
				  			'blad' => $bledy['sar_opis']
				  	);
				  	Html::zrob_element_formularza('html-textarea', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Dla palących',
				  			'id' => 'salaDlaPalacych',
				  			'name' => 'sar_dla_palacych',
				  			'aktualna_wartosc' => $dane['sar_dla_palacych'],
				  			'blad' => $bledy['sar_dla_palacych']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				   	
				  ?>
				 		  
				  
			</div>
			<div class="col-md-3">
				<?php 
				$parametry = array(
						'nazwa' => 'Zdjęcie',
						'id' => 'salaZdjecie',
						'name' => 'sar_zdjecie',
						'aktualna_wartosc' => $dane['sar_zdjecie'],
						'blad' => $bledy['sar_zdjecie'],
						'kontroler' => 'SaleRestauracyjne'
				);
				Html::zrob_element_formularza('upload', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="saleFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'SaleRestauracyjne'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>