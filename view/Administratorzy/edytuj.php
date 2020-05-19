<form name="admnistratorFormularz" action="<?= Router::utworz_link(array('controller' => 'Administratorzy', 'action' => 'edytuj', 'id' => View::pobierz('adm_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Stanowisko',
				  		'id' => 'adminStanowisko',
				  		'name' => 'adm_stanowisko',
				  		'aktualna_wartosc' => $dane['adm_stanowisko'],
				  		'blad' => $bledy['adm_stanowisko']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				 
				  	$parametry = array(
				  			'nazwa' => 'Zdjęcie',
				  			'id' => 'adminZdjecie',
				  			'name' => 'adm_zdjecie',
				  			'aktualna_wartosc' => $dane['adm_zdjecie'],
				  			'blad' => $bledy['adm_zdjecie'],
				  			'kontroler' => 'Administratorzy'
				  	);
				  	Html::zrob_element_formularza('upload', $parametry);
				  
				  	
				  
				  ?>
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="admnistratorFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Administratorzy'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
