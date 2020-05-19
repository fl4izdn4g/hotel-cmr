<form name="galeriaFormularz" action="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'dodaj')) ?>" method="post">
<div class="box box-solid">
	<div class="box-header">
		<a class="btn btn-primary pull-right disabled"><span class="fa fa-fw fa-file-image-o"></span> Zdjęcia</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa',
				  		'id' => 'galNazwa',
				  		'name' => 'gal_nazwa',
				  		'aktualna_wartosc' => $dane['gal_nazwa'],
				  		'blad' => $bledy['gal_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Widoczna',
				  			'id' => 'galWidoczna',
				  			'name' => 'gal_widoczna',
				  			'aktualna_wartosc' => $dane['gal_widoczna'],
				  			'blad' => $bledy['gal_widoczna']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Opis',
			  			'id' => 'galOpis',
			  			'name' => 'gal_opis',
			  			'aktualna_wartosc' => $dane['gal_opis'],
			  			'blad' => $bledy['gal_opis']
				  	);
				  	Html::zrob_element_formularza('html-textarea', $parametry);
				  	
				  ?>
			</div><!-- /.col-md-6 -->
			<div class="col-md-3">
			
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="galeriaFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Galerie'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
