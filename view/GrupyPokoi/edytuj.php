<form name="grupaFormularz" action="<?= Router::utworz_link(array('controller' => 'GrupyPokoi', 'action' => 'edytuj', 'id' => View::pobierz('grp_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-header">
		<a href="<?= Router::utworz_link(array('controller' => 'WyposazenieGrupyPokoi', 'action' => 'lista', 'group_id' => View::pobierz('grp_id')))?>" class="btn btn-primary pull-right"><span class="fa fa-fw fa-archive"></span> Wyposażenie dla grupy</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa grupy',
				  		'id' => 'grupaNazwa',
				  		'name' => 'grp_nazwa',
				  		'aktualna_wartosc' => $dane['grp_nazwa'],
				  		'blad' => $bledy['grp_nazwa']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Cena',
			  			'id' => 'grupaCena',
			  			'name' => 'grp_cena_netto',
			  			'aktualna_wartosc' => $dane['grp_cena_netto'],
			  			'blad' => $bledy['grp_cena_netto']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
			  			'nazwa' => 'Opis',
			  			'id' => 'grupaOpis',
			  			'name' => 'grp_opis',
			  			'aktualna_wartosc' => $dane['grp_opis'],
			  			'blad' => $bledy['grp_opis']
				  	);
				  	Html::zrob_element_formularza('html-textarea', $parametry);
				  	
				  ?>
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Ikona',
							'id' => 'grupaIkona',
							'name' => 'grp_ikona',
							'aktualna_wartosc' => $dane['grp_ikona'],
							'blad' => $bledy['grp_ikona'],
							'kontroler' => 'GrupyPokoi'
					);
					Html::zrob_element_formularza('upload', $parametry);
				?>
				
			
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="grupaFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'GrupyPokoi'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>