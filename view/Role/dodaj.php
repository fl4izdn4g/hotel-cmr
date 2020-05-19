<form name="roleFormularz" action="<?= Router::utworz_link(array('controller' => 'Role', 'action' => 'dodaj')) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Kod',
				  		'id' => 'rolaKod',
				  		'name' => 'rol_kod',
				  		'aktualna_wartosc' => $dane['rol_kod'],
				  		'blad' => $bledy['rol_kod'],
				  		'readonly' => true,
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Nazwa',
			  			'id' => 'rolaNazwa',
			  			'name' => 'rol_nazwa',
			  			'aktualna_wartosc' => $dane['rol_nazwa'],
			  			'blad' => $bledy['rol_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Opis',
			  			'id' => 'rolaOpis',
			  			'name' => 'rol_opis',
			  			'aktualna_wartosc' => $dane['rol_opis'],
			  			'blad' => $bledy['rol_opis']
				  	);
				  	Html::zrob_element_formularza('textarea', $parametry);
				  ?>
				 		  
				
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="roleFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Role'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>