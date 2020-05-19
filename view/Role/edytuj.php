<form name="roleFormularz" action="<?= Router::utworz_link(array('controller' => 'Role', 'action' => 'edytuj', 'id' => View::pobierz('rol_id'))) ?>" method="post">
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
				  		'readonly' => true,
				  		'aktualna_wartosc' => $dane['rol_kod'],
				  		'blad' => $bledy['rol_kod']
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
		<button name="roleFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Role'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>
