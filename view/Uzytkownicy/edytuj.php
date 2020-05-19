<form name="uzytkownikFormularz" action="<?= Router::utworz_link(array('controller' => 'Uzytkownicy', 'action' => 'edytuj', 'id' => View::pobierz('uzy_id'))) ?>" method="post">
<div class="box box-solid">
	<div class="box-header">
		<a href="<?= Router::utworz_link(array('controller' => 'AdresyUzytkownika', 'action' => 'lista', 'user_id' => View::pobierz('uzy_id')))?>" class="btn btn-primary pull-right" title="Adresy" style="margin-right: 10px"><span class="fa fa-fw fa-home"></span> Adresy korespondencyjne</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">

				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Rola użytkownika',
				  			'id' => 'uzytkownikRola',
				  			'name' => 'kuz_rol_id',
				  			'wartosc_pusta' => 'Wybierz rolę użytkownika',
				  			'wartosci' => View::pobierz('wszystkie_role'),
				  			'aktualna_wartosc' => $dane['kuz_rol_id'],
				  			'blad' => $bledy['kuz_rol_id'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				  	$parametry = array(
				  		'nazwa' => 'Imię',
				  		'id' => 'uzytkownikImie',
				  		'name' => 'uzy_imie',
				  		'aktualna_wartosc' => $dane['uzy_imie'],
				  		'blad' => $bledy['uzy_imie']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Nazwisko',
				  			'id' => 'uzytkownikNazwisko',
				  			'name' => 'uzy_nazwisko',
				  			'aktualna_wartosc' => $dane['uzy_nazwisko'],
				  			'blad' => $bledy['uzy_nazwisko']
				  	);
				  	Html::zrob_element_formularza('text', $parametry);
				  
				  	?>
				  	<div class="alert alert-info"><i class="icon fa fa-info"></i> Wypełnienie poniższych pól spowoduje zmianę hasła do konta użytkownika. Jeżeli nie chcesz zmieniać hasła pozostaw pola puste.</div>
				  	<?php 
				  	
				  	$parametry = array(
				  			'nazwa' => 'Hasło',
				  			'id' => 'uzytkownikHaslo',
				  			'name' => 'kuz_haslo',
				  			'aktualna_wartosc' => $dane['kuz_haslo'],
				  			'blad' => $bledy['kuz_haslo']
				  	);
				  	Html::zrob_element_formularza('password', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Powtórz hasło',
				  			'id' => 'uzytkownikHasloPowtorka',
				  			'name' => 'kuz_haslo_powtorz',
				  			'aktualna_wartosc' => $dane['kuz_haslo_powtorz'],
				  			'blad' => $bledy['kuz_haslo_powtorz']
				  	);
				  	Html::zrob_element_formularza('password', $parametry);
				  
				  ?>
			</div><!-- /.col-md-6 -->
			<div class="col-md-6">
				<?php 
					$parametry = array(
							'nazwa' => 'Email',
							'id' => 'uzytkownikEmail',
							'name' => 'kuz_email',
							'aktualna_wartosc' => $dane['kuz_email'],
							'blad' => $bledy['kuz_email'],
							'readonly' => true,
					);
					Html::zrob_element_formularza('text', $parametry);
				
					$parametry = array(
				  		'nazwa' => 'Telefon kontaktowy',
				  		'id' => 'uzytkownikTelefon',
				  		'name' => 'uzy_telefon_kontaktowy',
				  		'aktualna_wartosc' => $dane['uzy_telefon_kontaktowy'],
				  		'blad' => $bledy['uzy_telefon_kontaktowy']
				  	);
				  	Html::zrob_element_formularza('telephone', $parametry);
				?>
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.box-body -->
	<div class="box-footer">
		<button name="uzytkownikFormularz" value="edytuj" type="submit" class="btn btn-success">Zapisz</button>
		<a href="<?= Router::utworz_link(array('controller' => 'Uzytkownicy'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
