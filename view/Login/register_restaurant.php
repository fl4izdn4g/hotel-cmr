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

    <form action="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'register_restaurant', 'id' => View::pobierz('user_id')))?>" method="post">
    	<?php 
	    	   	
	    	$parametry = array(
	    			'nazwa' => 'Numer telefonu',
	    			'id' => 'uzyTel',
	    			'name' => 'uzy_telefon_kontaktowy',
	    			'aktualna_wartosc' => $dane['uzy_telefon_kontaktowy'],
	    			'blad' => $bledy['uzy_telefon_kontaktowy']
	    	);
	    	Html::zrob_element_formularza('telephone', $parametry);
	    	
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