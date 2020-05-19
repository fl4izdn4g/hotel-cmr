<div class="box box-solid">
	<div class="box-header">
		<a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow')); ?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-arrow-left"></span></a>
		 
        <?php if(!empty(View::pobierz('faktura_id'))): ?>
        	<a href="<?= Router::utworz_link(array('controller' => 'Faktury', 'action' => 'pdf', 'id' => View::pobierz('faktura_id'))); ?>" class="btn btn-danger pull-right"><span class="fa fw fa-file-pdf-o"></span></a>
		<?php endif; ?>
				
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<p class="lead">Informacje o rezerwacji</p>
				<dl>
					<dt>Data rezerwacji</dt>
	                <dd><?= View::pobierz('data'); ?></dd>
	                <dt>Liczba osób</dt>
	                <dd><?= View::pobierz('liczba_osob'); ?></dd>
				</dl>
              
				<p class="lead">Zarezerwowany stolik</p>
				<dl>
					<?php 
						$sala = View::pobierz('sala');
						$stolik = View::pobierz('stolik');
					?>
					<dt>Sala</dt>
	                <dd><?= $sala['sar_nazwa']; ?></dd>
	                <dt>Numer stolika</dt>
	                <dd><?= $stolik['sto_numer']; ?></dd>
				</dl>
						
				<p class="lead">Rezerwujący</p>
				<dl>
					<?php 
						$osoba = View::pobierz('osoba');
					?>
					<dt>Imię i nazwisko</dt>
	                <dd><?= $osoba['uzy_imie'].' '.$osoba['uzy_nazwisko']; ?></dd>
	                <dt>Email</dt>
	                <dd><?= $osoba['kuz_email']; ?></dd>
				</dl>
			
			</div>
			<div class="col-md-6">	
				<p class="lead">Faktura</p>
				<dl>
					<dt>Wygenerować fakturę</dt>
	                <dd><?= View::pobierz('faktura'); ?></dd>
	            </dl>
	            
	           <p class="lead">Należności</p>
				<dl>
					<dt>Cena netto</dt>
	                <dd><?= View::pobierz('cena_netto'); ?></dd>
	                <dt>Cena brutto</dt>
	                <dd><?= View::pobierz('cena_brutto'); ?></dd>
				</dl>
			 	
			 	<?php if(!empty(View::pobierz('cena_netto_dostawka'))): ?>
			 		<p class="lead">Dostawka</p>
			 		<dl>
						<dt>Cena netto</dt>
		                <dd><?= View::pobierz('cena_netto_dostawka'); ?></dd>
		                <dt>Cena brutto</dt>
		                <dd><?= View::pobierz('cena_brutto_dostawka'); ?></dd>
					</dl>
			 	<?php endif; ?>
			 				 	
			 	<p class="lead">Do zapłaty: <strong><?= View::pobierz('cena_calkowita'); ?></strong></p>
			</div>
		</div>
	</div>
</div>
