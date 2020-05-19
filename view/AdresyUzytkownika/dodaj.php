<?php 
	$form_post = array(
		'controller' => 'AdresyUzytkownika',
		'action' => 'dodaj',
		'user_id' => View::pobierz('user_id'),
	);
	
	$parent_menu = View::pobierz('parent_menu');
	if(!empty($parent_menu)) {
		$form_post['parent'] = $parent_menu;
	}

?>

<form name="adresFormularz" action="<?= Router::utworz_link($form_post); ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
			  <?php 
			  	$dane = View::pobierz('dane_do_formularza');
			  	$bledy = View::pobierz('bledy_walidacji');
			  
			  	$parametry = array(
			  			'nazwa' => 'Ulica',
			  			'id' => 'adrUlica',
			  			'name' => 'adr_ulica',
			  			'aktualna_wartosc' => $dane['adr_ulica'],
			  			'blad' => $bledy['adr_ulica']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Numer domu',
			  			'id' => 'adrNumerDomu',
			  			'name' => 'adr_numer_domu',
			  			'aktualna_wartosc' => $dane['adr_numer_domu'],
			  			'blad' => $bledy['adr_numer_domu']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			  	
			  	$parametry = array(
			  			'nazwa' => 'Numer mieszkania',
			  			'id' => 'adrNumerMieszkania',
			  			'name' => 'adr_numer_mieszkania',
			  			'aktualna_wartosc' => $dane['adr_numer_mieszkania'],
			  			'blad' => $bledy['adr_numer_mieszkania']
			  	);
			  	Html::zrob_element_formularza('text', $parametry);
			 ?>
			 </div>
			 <div class="col-md-6">	
				<?php 
					$parametry = array(
							'nazwa' => 'Kod pocztowy',
							'id' => 'adrKodPocztowy',
							'name' => 'adr_kod_pocztowy',
							'aktualna_wartosc' => $dane['adr_kod_pocztowy'],
							'blad' => $bledy['adr_kod_pocztowy']
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Miejscowość',
							'id' => 'adrMiejscowosc',
							'name' => 'adr_miejscowosc',
							'aktualna_wartosc' => $dane['adr_miejscowosc'],
							'blad' => $bledy['adr_miejscowosc']
					);
					Html::zrob_element_formularza('text', $parametry);
					
					$parametry = array(
							'nazwa' => 'Wojewodztwo',
							'id' => 'adrWojewodztwo',
							'name' => 'adr_wojewodztwo',
							'wartosc_pusta' => 'Wybierz wojewodztwo',
							'wartosci' => View::pobierz('wojewodztwa'),
							'aktualna_wartosc' => $dane['adr_wojewodztwo'],
							'blad' => $bledy['adr_wojewodztwo'],
							'czy_select2' => true,
					);
					Html::zrob_element_formularza_select($parametry);
					 
					
					$parametry = array(
							'nazwa' => 'To jest adres domyślny',
							'id' => 'adrToDomyslny',
							'name' => 'adr_domyslny',
							'aktualna_wartosc' => $dane['adr_domyslny'],
							'blad' => $bledy['adr_domyslny']
					);
					Html::zrob_element_formularza('checkbox', $parametry);
				?>
			
			 </div>
		</div>
	</div>
	<div class="box-footer">
		<button name="adresFormularz" value="dodaj" type="submit" class="btn btn-success">Zapisz</button>
	  	<?php 
	  		$cancel_link = array(
  				'controller' => 'AdresyUzytkownika',
  				'user_id' => View::pobierz('user_id'),
	  		);
	  		
	  		$parent_menu = View::pobierz('parent_menu');
	  		if(!empty($parent_menu)) {
	  			$cancel_link['parent'] = $parent_menu;
	  		}
	  	?>
	  	<a href="<?= Router::utworz_link($cancel_link)?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>