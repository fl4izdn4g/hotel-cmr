<form name="rpFormularz" action="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'dodaj')) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Data rezerwacji',
				  			'id' => 'rsData',
				  			'name' => 'rs_data_rezerwacji',
				  			'aktualna_wartosc' => $dane['rs_data_rezerwacji'],
				  			'blad' => $bledy['rs_data_rezerwacji']
				  	);
				  	Html::zrob_element_formularza('datepicker', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Sala',
				  			'id' => 'rsSala',
				  			'name' => 'rs_sala_id',
				  			'wartosc_pusta' => 'Wybierz salę restauracyjną',
				  			'wartosci' => View::pobierz('sale'),
				  			'aktualna_wartosc' => $dane['rs_sala_id'],
				  			'blad' => $bledy['rs_sala_id'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				 ?>
			</div>
			<div class="col-md-6">
				<div id="salaInformation" class="row" style="display: none; margin-top: 25px;">
			 		<div style="float: left; margin-right: 20px">
						<img id="salaIcon" />				 		
			 		</div>
			 		<div class="float: left">
				 		<h3 id="salaName" style="margin-top: 0"></h3>
				 		<div id="salaDescription">
				 		</div>
				 		<p class="lead">Liczba dostępnych stolików: <strong><span id="salaTablesCount"></span></strong></p>
			 		</div>
			 		<div style="clear:both; overflow: auto"></div>
			 	</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button name="rsFormularz" id="rsStolikiStart" value="dodaj" type="submit" class="btn btn-success" style="display: none">Dalej</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>