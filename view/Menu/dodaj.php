<form name="menuFormularz" action="<?= Router::utworz_link(array('controller' => 'Menu', 'action' => 'dodaj')) ?>" method="post">
<div class="box box-solid">
	<div class="box-header">
		<a class="btn btn-primary pull-right disabled"><span class="fa fa-fw fa-flask"></span> Potrawy w menu</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa menu',
				  		'id' => 'menuNazwa',
				  		'name' => 'men_nazwa',
				  		'aktualna_wartosc' => $dane['men_nazwa'],
				  		'blad' => $bledy['men_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Aktualne',
			  			'id' => 'menuAktualne',
			  			'name' => 'men_czy_aktualne',
			  			'aktualna_wartosc' => $dane['men_czy_aktualne'],
			  			'blad' => $bledy['men_czy_aktualne']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				  	
			
				  	
				  
				  ?>
			</div><!-- /.col-md-6 -->
			<div class="col-md-6">
				<?php 
					$parametry = array(
							'nazwa' => 'Ważne od',
							'id' => 'menuOd',
							'name' => 'men_wazne_od',
							'aktualna_wartosc' => $dane['men_wazne_od'],
							'blad' => $bledy['men_wazne_od']
					);
					Html::zrob_element_formularza('datepicker', $parametry);
					
					$parametry = array(
							'nazwa' => 'Ważne do',
							'id' => 'menuDo',
							'name' => 'men_wazne_do',
							'aktualna_wartosc' => $dane['men_wazne_do'],
							'blad' => $bledy['men_wazne_do']
					);
					Html::zrob_element_formularza('datepicker', $parametry);
				?>
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="menuFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Menu'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
