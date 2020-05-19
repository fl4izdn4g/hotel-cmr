<?php
$dane = View::pobierz('dane_do_formularza');
$bledy = View::pobierz('bledy_walidacji');
?>

<div class="register-box">
  <div class="register-logo">
    <a href="<?= Router::utworz_link(array('controller' => 'Home')); ?>"><b>HOTEL</b></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg lead">Rejestracja. Krok 1 z 2</p>

    <form action="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'register'))?>" method="post">
    	<?php 
	    	$parametry = array(
	    			'nazwa' => 'Typ rejestracji',
	    			'id' => 'rejTyp',
	    			'name' => 'rej_typ',
	    			'wartosc_pusta' => 'Dlaczego chcesz się zarejestrować',
	    			'wartosci' => View::pobierz('typy_rejestracji'),
	    			'aktualna_wartosc' => $dane['rej_typ'],
	    			'blad' => $bledy['rej_typ'],
	    			'czy_select2' => true,
	    	);
	    	Html::zrob_element_formularza_select($parametry);
    	
	    	$parametry = array(
	    			'nazwa' => 'Imię',
	    			'id' => 'rejImie',
	    			'name' => 'rej_imie',
	    			'aktualna_wartosc' => $dane['rej_imie'],
	    			'blad' => $bledy['rej_imie']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Nazwisko',
	    			'id' => 'rejNazwisko',
	    			'name' => 'rej_nazwisko',
	    			'aktualna_wartosc' => $dane['rej_nazwisko'],
	    			'blad' => $bledy['rej_nazwisko']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Email',
	    			'id' => 'rejEmail',
	    			'name' => 'rej_email',
	    			'aktualna_wartosc' => $dane['rej_email'],
	    			'blad' => $bledy['rej_email']
	    	);
	    	Html::zrob_element_formularza('text', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Hasło',
	    			'id' => 'rejHaslo',
	    			'name' => 'rej_haslo',
	    			'aktualna_wartosc' => $dane['rej_haslo'],
	    			'blad' => $bledy['rej_haslo']
	    	);
	    	Html::zrob_element_formularza('password', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Powtórz hasło',
	    			'id' => 'rejHasloPowtorz',
	    			'name' => 'rej_haslo_powtorz',
	    			'aktualna_wartosc' => $dane['rej_haslo_powtorz'],
	    			'blad' => $bledy['rej_haslo_powtorz']
	    	);
	    	Html::zrob_element_formularza('password', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Zapoznałem się i zgadzam się z <a href="'.Router::utworz_link(array('controller' => 'Login', 'action' => 'terms')).'" target="_blank">regulaminem</a>',
	    			'id' => 'rejRegulamin',
	    			'name' => 'rej_regulamin',
	    			'aktualna_wartosc' => $dane['rej_regulamin'],
	    			'blad' => $bledy['rej_regulamin']
	    	);
	    	Html::zrob_element_formularza('checkbox', $parametry);
	    	
	    	$parametry = array(
	    			'nazwa' => 'Wyrażam zgodę na przetwarzanie moich danych osobowych dla potrzeb niezbędnych do realizacji procesu realizacji zamówień w aplikacji HOTEL (zgodnie z Ustawą z dnia 29.08.1997 roku o Ochronie Danych Osobowych; tekst jednolity: Dz. U. z 2002r. Nr 101, poz. 926 ze zm.).',
	    			'id' => 'rejZgoda',
	    			'name' => 'rej_zgoda',
	    			'aktualna_wartosc' => $dane['rej_zgoda'],
	    			'blad' => $bledy['rej_zgoda']
	    	);
	    	Html::zrob_element_formularza('checkbox', $parametry);
    	?>
        
        <div style="padding-top: 15px;">
          <button type="submit" name="rejestracjaFormularz" value="register" class="btn btn-primary btn-block btn-flat">Przejdź dalej</button>
        </div>
       
    </form>

    <a href="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'login'))?>" class="text-center">Posiadam konto w systemie</a>
  </div>
  <!-- /.form-box -->
  <div class="box-footer">
 	<?php Html::pokaz_alerty(); ?>
  </div>
<!-- /.register-box -->