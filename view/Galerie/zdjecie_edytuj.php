<form name="zdjecieFormularz" action="<?= Router::utworz_link(array('controller' => 'Galerie', 'action' => 'zdjecie_edytuj', 'id' => View::pobierz('id'), 'gallery_id' => View::pobierz('gallery_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Tytuł',
				  		'id' => 'zdjTytul',
				  		'name' => 'zdj_tytul',
				  		'aktualna_wartosc' => $dane['zdj_tytul'],
				  		'blad' => $bledy['zdj_tytul']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Zdjęcie',
				  			'id' => 'zdjZdjecie',
				  			'name' => 'zdj_plik',
				  			'aktualna_wartosc' => $dane['zdj_plik'],
				  			'blad' => $bledy['zdj_plik'],
				  			'kontroler' => 'Galerie'
				  	);
				  	Html::zrob_element_formularza('upload', $parametry);
				  ?>
			</div><!-- /.col-md-6 -->
			<div class="col-md-3">
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="zdjecieFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Galerie','action' => 'zdjecia', 'gallery_id' => View::pobierz('gallery_id')))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
