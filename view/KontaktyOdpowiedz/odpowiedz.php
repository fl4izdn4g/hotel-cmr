<form name="odpowiedzFormularz" action="<?= Router::utworz_link(array('controller' => 'KontaktyOdpowiedz', 'action' => 'odpowiedz', 'id' => View::pobierz('kon_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-9">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
							'nazwa' => 'Tytuł',
							'id' => 'odpowiedzTytul',
							'name' => 'kodp_tytul',
							'aktualna_wartosc' => $dane['kodp_tytul'],
							'blad' => $bledy['kodp_tytul'],
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Treść',
							'id' => 'odpowiedzTresc',
							'name' => 'kodp_tresc',
							'aktualna_wartosc' => $dane['kodp_tresc'],
							'blad' => $bledy['kodp_tresc'],
					);
					Html::zrob_element_formularza('html-textarea', $parametry);
				  	
				  ?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="odpowiedzFormularz" value="wyslij" type="submit" class="btn btn-success">Wyślij</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Kontakty', 'action' => 'wyswietl', 'id' => View::pobierz('kon_id')))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>