<form name="rpFormularz" action="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'dodaj')) ?>" method="post">
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				  <?php 
				  	$dane = View::pobierz('dane_do_formularza');
				  	$bledy = View::pobierz('bledy_walidacji');
				  
				  	$parametry = array(
				  			'nazwa' => 'Data od',
				  			'id' => 'rpOd',
				  			'name' => 'rp_data_od',
				  			'aktualna_wartosc' => $dane['rp_data_od'],
				  			'blad' => $bledy['rp_data_od']
				  	);
				  	Html::zrob_element_formularza('datepicker', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Data do',
				  			'id' => 'rpDo',
				  			'name' => 'rp_data_do',
				  			'aktualna_wartosc' => $dane['rp_data_do'],
				  			'blad' => $bledy['rp_data_do']
				  	);
				  	Html::zrob_element_formularza('datepicker', $parametry);
				  	
				  	$parametry = array(
				  			'nazwa' => 'Grupa pokoi',
				  			'id' => 'rpGrupaPokoi',
				  			'name' => 'rp_grupa_id',
				  			'wartosc_pusta' => 'Wybierz grupę pokoi',
				  			'wartosci' => View::pobierz('grupy'),
				  			'aktualna_wartosc' => $dane['rp_grupa_id'],
				  			'blad' => $bledy['rp_grupa_id'],
				  			'czy_select2' => true,
				  	);
				  	Html::zrob_element_formularza_select($parametry);
				  	
				 ?>
				 </div>
				 <div class="col-md-6">	
				 	<div id="groupInformation" class="row" style="display: none; margin-top: 25px;">
				 		<div style="float: left; margin-right: 20px">
							<img id="groupIcon" />				 		
				 		</div>
				 		<div class="float: left">
					 		<h3 id="groupName" style="margin-top: 0"></h3>
					 		<div id="groupDescription">
					 		</div>
					 		<p class="lead">Liczba dostępnych pokoi: <strong><span id="groupRoomsCount"></span></strong></p>
					 		<div id="groupPrice">
					 			<div style="float: left; margin-right: 20px"><p class="lead">Netto: <span id="groupPriceWithoutTax"></span></p></div>
					 			<div style="float: left"><p class="lead">Brutto: <strong style="color: #00a65a"><span id="groupPriceWithTax"></span></strong></p></div>
					 			<div style="clear:both; overflow: auto;"></div>
					 		</div>
					 		
				 		</div>
				 		<div style="clear:both; overflow: auto"></div>
				 	</div>
				</div>
		</div>
	</div>
	<div class="box-footer">
		<button id="rpRezerwacjaStart" name="rpFormularz" value="dodaj" type="submit" class="btn btn-success" style="display: none">Dalej</button>
	  	<a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje'))?>" class="btn btn-default">Anuluj</a>
	</div>
</div>
</form>