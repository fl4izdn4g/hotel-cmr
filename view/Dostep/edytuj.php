<form name="dostepFormularz" action="<?= Router::utworz_link(array('controller' => 'Dostep', 'action' => 'edytuj', 'id' => View::pobierz('dos_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">	
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Typ obiektu',
				  			'id' => 'dostepTyp',
				  			'name' => 'dos_typ',
				  			'wartosci' => View::pobierz('wszystkie_typy'),
				  			'wartosc_pusta' => 'Wybierz typ obiektu',
				  			'aktualna_wartosc' => $dane['dos_typ'],
				  			'blad' => $bledy['dos_typ'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Nazwa obiektu',
			  			'id' => 'dostepNazwa',
			  			'name' => 'dos_obiekt',
			  			'aktualna_wartosc' => $dane['dos_obiekt'],
			  			'blad' => $bledy['dos_obiekt']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Rola',
			  			'id' => 'dostepRola',
			  			'name' => 'dos_rol_id',
				  		'wartosci' => View::pobierz('wszystkie_role'),
				  		'wartosc_pusta' => 'Wybierz rolę',
			  			'aktualna_wartosc' => $dane['dos_rol_id'],
			  			'blad' => $bledy['dos_rol_id'],
				  		'czy_select2' => true,
				  	);
				   	Html::zrob_element_formularza_select($parametry);
				  	
				  ?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="dostepFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Dostep'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>