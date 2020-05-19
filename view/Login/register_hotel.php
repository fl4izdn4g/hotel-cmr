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

    <form action="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'register_hotel', 'id' => View::pobierz('user_id')))?>" method="post">
    	<?php 
	    			$parametry = array(
				  		'nazwa' => 'PESEL',
				  		'id' => 'goscPesel',
				  		'name' => 'gh_pesel',
				  		'aktualna_wartosc' => $dane['gh_pesel'],
				  		'blad' => $bledy['gh_pesel']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				 
				  	$parametry = array(
				  			'nazwa' => 'Zagraniczny',
				  			'id' => 'goscZagraniczny',
				  			'name' => 'gh_zagraniczny',
				  			'aktualna_wartosc' => $dane['gh_zagraniczny'],
				  			'blad' => $bledy['gh_zagraniczny']
				  	);
				  	Html::zrob_element_formularza('checkbox', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Typ dokumentu tożsamości',
				  			'id' => 'goscTypDokumentu',
				  			'name' => 'gh_typ_dokumentu_tozsamosci',
				  			'wartosc_pusta' => 'Wybierz typ dokumentu',
				  			'wartosci' => View::pobierz('typy_dokumentow'),
				  			'aktualna_wartosc' => $dane['gh_typ_dokumentu_tozsamosci'],
				  			'blad' => $bledy['gh_typ_dokumentu_tozsamosci'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Numer dokumentu tożsamości',
				  			'id' => 'goscNumerDowodu',
				  			'name' => 'gh_numer_dokumentu_tozsamosci',
				  			'aktualna_wartosc' => $dane['gh_numer_dokumentu_tozsamosci'],
				  			'blad' => $bledy['gh_numer_dokumentu_tozsamosci']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
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