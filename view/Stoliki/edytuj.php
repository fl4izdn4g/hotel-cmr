<form name="stolikiFormularz" action="<?= Router::utworz_link(array('controller' => 'Stoliki', 'action' => 'edytuj', 'id' => View::pobierz('sto_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Sale restauracyjne',
				  			'id' => 'stolikSalaRestauracyjna',
				  			'name' => 'sto_sar_id',
				  			'wartosci' => View::pobierz('wszystkie_sale'),
				  			'wartosc_pusta' => 'Wybierz salę restauracyjną',
				  			'aktualna_wartosc' => $dane['sto_sar_id'],
				  			'blad' => $bledy['sto_sar_id'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  		'nazwa' => 'Numer',
				  		'id' => 'stolikNumer',
				  		'name' => 'sto_numer',
				  		'aktualna_wartosc' => $dane['sto_numer'],
				  		'blad' => $bledy['sto_numer']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Liczba miejsc',
				  			'id' => 'stolikLiczbaMiejsc',
				  			'name' => 'sto_liczba_miejsc',
				  			'aktualna_wartosc' => $dane['sto_liczba_miejsc'],
				  			'blad' => $bledy['sto_liczba_miejsc']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Cena',
				  			'id' => 'stolikCena',
				  			'name' => 'sto_cena_netto',
				  			'aktualna_wartosc' => $dane['sto_cena_netto'],
				  			'blad' => $bledy['sto_cena_netto']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  ?>
			</div>
			<div class="col-md-6">
				<?php 
					$parametry = array(
							'nazwa' => 'Położenie',
							'id' => 'stolikPolozenie',
							'name' => 'sto_polozenie',
							'aktualna_wartosc' => $dane['sto_polozenie'],
							'blad' => $bledy['sto_polozenie']
					);
					Html::zrob_element_formularza('html-textarea', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="stolikiFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Stoliki'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>