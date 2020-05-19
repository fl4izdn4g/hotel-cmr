<?php
$dane = View::pobierz('dane_do_formularza');
$bledy = View::pobierz('bledy_walidacji');
?>

<div class="register-box">
  <div class="register-logo">
    <a href="<?= Router::utworz_link(array('controller' => 'Home')); ?>"><b>HOTEL</b></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Rejestracja. Krok 2 z 2.</p>

    <form action="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'register_meal', 'id' => View::pobierz('user_id')))?>" method="post">
    	<?php  
	    	$parametry = array(
	    			'nazwa' => 'Ulica',
	    			'id' => 'adresUlica',
	    			'name' => 'adr_ulica',
	    			'aktualna_wartosc' => $dane['adr_ulica'],
	    			'blad' => $bledy['adr_ulica']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
    	
	    	$parametry = array(
	    			'nazwa' => 'Numer domu',
	    			'id' => 'adresNumerDomu',
	    			'name' => 'adr_numer_domu',
	    			'aktualna_wartosc' => $dane['adr_numer_domu'],
	    			'blad' => $bledy['adr_numer_domu']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Numer mieszkania',
	    			'id' => 'adresNumerMieszkania',
	    			'name' => 'adr_numer_mieszkania',
	    			'aktualna_wartosc' => $dane['adr_numer_mieszkania'],
	    			'blad' => $bledy['adr_numer_mieszkania']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Kod pocztowy',
	    			'id' => 'adresKodPocztowy',
	    			'name' => 'adr_kod_pocztowy',
	    			'aktualna_wartosc' => $dane['adr_kod_pocztowy'],
	    			'blad' => $bledy['adr_kod_pocztowy']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Miejscowosc',
	    			'id' => 'adresMiejscowosc',
	    			'name' => 'adr_miejscowosc',
	    			'aktualna_wartosc' => $dane['adr_miejscowosc'],
	    			'blad' => $bledy['adr_miejscowosc']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Województwo',
	    			'id' => 'adr_wojewodztwo',
	    			'name' => 'adr_wojewodztwo',
	    			'wartosc_pusta' => 'Wybierz województwo',
	    			'wartosci' => View::pobierz('wojewodztwa'),
	    			'aktualna_wartosc' => $dane['adr_wojewodztwo'],
	    			'blad' => $bledy['adr_wojewodztwo'],
	    			'czy_select2' => true,
	    	);
	    	Html::zrob_element_formularza_select($parametry);
    	?>
        
        <div style="padding-top: 15px;">
          <button type="submit" name="rejestracjaFormularz" value="register" class="btn btn-primary btn-block btn-flat">Zarejestruj się</button>
        </div>
       
    </form>
  </div>
  <!-- /.form-box -->
    <div class="box-footer">
 	<?php Html::pokaz_alerty(); ?>
  </div>
</div>
<!-- /.register-box -->