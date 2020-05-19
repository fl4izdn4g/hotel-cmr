<form name="stanFormularz" action="<?= Router::utworz_link(array('controller' => 'StanMagazynowy', 'action' => 'edytuj', 'id' => View::pobierz('stm_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Produkt',
				  			'id' => 'stanProdukt',
				  			'name' => 'stm_prod_id',
				  			'wartosc_pusta' => 'Wybierz produkt',
				  			'wartosci' => View::pobierz('produkty'),
				  			'aktualna_wartosc' => $dane['stm_prod_id'],
				  			'blad' => $bledy['stm_prod_id'],
				  			'czy_select2' => true,
				  			'readonly' => true
				  	); 
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  		'nazwa' => 'Aktualny stan',
				  		'id' => 'stanPoczatkowy',
				  		'name' => 'stm_aktualny_stan',
				  		'aktualna_wartosc' => $dane['stm_aktualny_stan'],
				  		'blad' => $bledy['stm_aktualny_stan'],
				  		'readonly' => true
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Dopuszczalny stan minimalny',
			  			'id' => 'stanMinimalny',
			  			'name' => 'stm_minimalny_dopuszczalny_stan',
			  			'aktualna_wartosc' => $dane['stm_minimalny_dopuszczalny_stan'],
			  			'blad' => $bledy['stm_minimalny_dopuszczalny_stan']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				   
				  ?>
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="stanFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'StanMagazynowy'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
