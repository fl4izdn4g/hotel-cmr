<form name="zdarzenieFormularz" action="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'zdarzenie', 'state_id' => View::pobierz('state_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Typ',
				  			'id' => 'zdarzenieTyp',
				  			'name' => 'zdm_typ',
				  			'wartosc_pusta' => 'Wybierz typ zdarzenia',
				  			'wartosci' => View::pobierz('typy_zdarzen'),
				  			'aktualna_wartosc' => $dane['zdm_typ'],
				  			'blad' => $bledy['zdm_typ'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  		'nazwa' => 'Data wystąpienia',
				  		'id' => 'zdarzenieDataWystapienia',
				  		'name' => 'zdm_data_wystapienia',
				  		'aktualna_wartosc' => $dane['zdm_data_wystapienia'],
				  		'blad' => $bledy['zdm_data_wystapienia']
				  	);
				  	Html::zrob_element_formularza('datepicker', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Ilość',
				  			'id' => 'zdarzenieIlosc',
				  			'name' => 'zdm_ilosc',
				  			'aktualna_wartosc' => $dane['zdm_ilosc'],
				  			'blad' => $bledy['zdm_ilosc']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				   
				  ?>
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="zdarzenieFormularz" value="zdarzenie" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'zdarzenia', 'state_id' => View::pobierz('state_id')))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
