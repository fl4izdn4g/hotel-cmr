<?php 
	$form_link = array(
		'controller' => 'UzytkownicyRestauracji',
		'action' => 'edytuj',
		'id' => View::pobierz('uzy_id')
	);
	
	if(!empty(View::pobierz('parent_menu'))) {
		$form_link['parent'] = View::pobierz('parent_menu');
	}
?>
<form name="uzytkownikFormularz" action="<?= Router::utworz_link($form_link) ?>" method="post">
<div class="box box-solid">
	<div class="box-header">
		<?php 
			$adresy_link = array(
				'controller' => 'AdresyUzytkownika', 
				'action' => 'lista', 
				'user_id' => View::pobierz('uzy_id')
			);
			
			if(!empty(View::pobierz('parent_menu'))) {
				$adresy_link['parent'] = View::pobierz('parent_menu');
			}
		?>
		<a href="<?= Router::utworz_link($adresy_link)?>" class="btn btn-primary pull-right" title="Adresy" style="margin-right: 10px"><span class="fa fa-fw fa-home"></span> Adresy korespondencyjne</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">

				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  	
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
		<?php 
			$cancel_link = array(
				'controller' => 'UzytkownicyRestauracji'
			);
			if(!empty(View::pobierz('parent_menu'))) {
				$cancel_link['parent'] = View::pobierz('parent_menu');
			}
		?>
		<a href="<?= Router::utworz_link($cancel_link)?>" class="btn btn-default">Anuluj</a>
	</div>
</div><!-- /.box -->
				  
</form>
