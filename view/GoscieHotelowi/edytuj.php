<form name="goscFormularz" action="<?= Router::utworz_link(array('controller' => 'GoscieHotelowi', 'action' => 'edytuj', 'id' => View::pobierz('gh_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'PESEL',
				  		'id' => 'goscPesel',
				  		'name' => 'gh_pesel',
				  		'aktualna_wartosc' => $dane['gh_pesel'],
				  		'blad' => $bledy['gh_pesel'],
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				 
				  	$parametry = array(
				  			'nazwa' => 'Zagraniczny',
				  			'id' => 'goscZagraniczny',
				  			'name' => 'gh_zagraniczny',
				  			'aktualna_wartosc' => $dane['gh_zagraniczny'],
				  			'blad' => $bledy['gh_zagraniczny']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Typ dokumentu tożsamości',
				  			'id' => 'goscTypDokumentu',
				  			'name' => 'gh_typ_dokumentu_tozsamosci',
				  			'wartosc_pusta' => 'Wybierz typ dokumentu',
				  			'wartosci' => View::pobierz('wszystkie_typy'),
				  			'aktualna_wartosc' => $dane['gh_typ_dokumentu_tozsamosci'],
				  			'blad' => $bledy['gh_typ_dokumentu_tozsamosci'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Numer dokumentu tożsamości',
				  			'id' => 'goscNumerDowodu',
				  			'name' => 'gh_numer_dokumentu_tozsamosci',
				  			'aktualna_wartosc' => $dane['gh_numer_dokumentu_tozsamosci'],
				  			'blad' => $bledy['gh_numer_dokumentu_tozsamosci']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  		
				  
				  ?>
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="goscFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'GoscieHotelowi'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
