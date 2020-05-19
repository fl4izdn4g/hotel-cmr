<form name="potrawaFormularz" action="<?= Router::utworz_link(array('controller' => 'PotrawyMenu', 'action' => 'edytuj', 'id' => View::pobierz('menxpot_id'), 'menu_id' => View::pobierz('menu_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-8">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  		'nazwa' => 'Nazwa potrawy',
				  		'id' => 'potrawaNazwa',
				  		'name' => 'menxpot_pot_id',
				  		'wartosc_pusta' => 'Wybierz potrawę',
				  		'wartosci' => View::pobierz('wszystkie_potrawy'),
				  		'aktualna_wartosc' => $dane['menxpot_pot_id'],
				  		'blad' => $bledy['menxpot_pot_id'],
				  		'czy_select2' => true
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  ?>
			</div>
			<div class="col-md-3">
				<?php 
					$parametry = array(
							'nazwa' => 'Cena',
							'id' => 'potrawaCena',
							'name' => 'menxpot_cena_netto',
							'aktualna_wartosc' => $dane['menxpot_cena_netto'],
							'blad' => $bledy['menxpot_cena_netto'],
					);
					Html::zrob_element_formularza('text', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="potrawaFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'PotrawyMenu', 'menu_id' => View::pobierz('menu_id')))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>