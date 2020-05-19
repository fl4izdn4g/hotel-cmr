<div class="box box-solid">
	<div class="box-body">
		<div class="row kontakt-row">
			<div class="col-md-7">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  
				  	$parametry = array(
							'nazwa' => 'Email',
							'id' => 'kontaktEmail',
							'name' => 'kon_email',
							'aktualna_wartosc' => $dane['kon_email'],
							'blad' => '',
				  			'readonly' => true
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Tytuł',
							'id' => 'kontaktTytul',
							'name' => 'kon_tytul',
							'aktualna_wartosc' => $dane['kon_tytul'],
							'blad' => '',
				  			'readonly' => true
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Kategoria',
							'id' => 'kontaktKategoria',
							'name' => 'kon_kategoria',
							'aktualna_wartosc' => $dane['kon_kategoria'],
							'blad' => '',
				  			'readonly' => true
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Opis',
							'id' => 'kontaktOpis',
							'name' => 'kon_opis',
							'aktualna_wartosc' => $dane['kon_opis'],
							'blad' => '',
				  			'readonly' => true
					);
					Html::zrob_element_formularza('html-textarea', $parametry);
				  	
				  ?>
			</div>
			<div class="col-md-4">
				<?php 
					$parametry = array(
							'nazwa' => 'Data dodania',
							'id' => 'kontaktDataDodania',
							'name' => 'kon_data_dodania',
							'aktualna_wartosc' => $dane['kon_data_dodania'],
							'blad' => '',
				  			'readonly' => true
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Data przeczytania',
							'id' => 'kontaktDataPrzeczytania',
							'name' => 'kon_data_przeczytania',
							'aktualna_wartosc' => $dane['kon_data_przeczytania'],
							'blad' => '',
							'readonly' => true
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Data odpowiedzi',
							'id' => 'kontaktDataOdpowiedzi',
							'name' => 'kon_data_odpowiedzi',
							'aktualna_wartosc' => $dane['kon_data_odpowiedzi'],
							'blad' => '',
							'readonly' => true
					);
					Html::zrob_element_formularza('text', $parametry);
				?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<a href="<?= Router::utworz_link(array('controller' => 'KontaktyOdpowiedz', 'action' => 'odpowiedz', 'id' => View::pobierz('kon_id'))); ?>" class="btn btn-primary">Odpowiedz</a>
		<a href="<?= Router::utworz_link(array('controller' => 'Kontakty'));?>" class="btn btn-default">Anuluj</a>
	</div>
</div>