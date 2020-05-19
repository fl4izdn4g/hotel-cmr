<?php 
	$parametry = View::pobierz('parametry');
?>
<div class="box box-solid">
	<div class="box-header">
		<a href="<?= Router::utworz_link(array('controller' => 'Faktury')); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span></a>
	</div>
	<div class="box-body">
	    <section class="invoice">
	      <!-- title row -->
	      <div class="row">
	        <div class="col-xs-12">
	          <h2 class="page-header">
	            <i class="fa fa-globe"></i> HOTEL
	            <small class="pull-right">Date: <?= $parametry['faktura']['data_wystawienia']; ?></small>
	          </h2>
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- info row -->
	      <div class="row invoice-info">
	        <div class="col-sm-4 invoice-col">
	          Sprzedający
	          <address>
	            <strong><?= $parametry['nadawca']['nazwa']?></strong><br>
	            <?= $parametry['nadawca']['ulica']?><br>
	            <?= $parametry['nadawca']['kod_pocztowy']?> <?= $parametry['nadawca']['miejscowosc']?><br>
	            Email: <?= $parametry['nadawca']['email']?>
	          </address>
	        </div>
	        <!-- /.col -->
	        <div class="col-sm-4 invoice-col">
	          Kupujący
	          <address>
	            <strong><?= $parametry['odbiorca']['nazwa']; ?></strong><br>
	            <?= $parametry['odbiorca']['ulica']; ?><br>
	            <?= $parametry['odbiorca']['kod_pocztowy']?> <?= $parametry['odbiorca']['miejscowosc']?><br>
	          </address>
	        </div>
	        <!-- /.col -->
	        <div class="col-sm-4 invoice-col">
	          <b>Faktura <?= $parametry['faktura']['typ']; ?>/<?= $parametry['faktura']['numer']; ?></b><br>
	          <br>
	          <b>Do zamówienia:</b> <?= $parametry['zamowienie']['identyfikator'] ?><br>
	          <b>Płatność do:</b> <?= $parametry['faktura']['data_do_zaplaty']; ?><br>
	         </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.row -->
	
	      <!-- Table row -->
	      <div class="row">
	        <div class="col-xs-12 table-responsive">
	          <table class="table table-striped">
	            <thead>
	            <tr>
	              <th>Ilość</th>
	              <th>Nazwa</th>
	              <th>Netto</th>
	              <th>Podatek</th>
	              <th>Kwota podatku</th>
	              <th>Brutto</th>
	            </tr>
	            </thead>
	            <tbody>
	            <?php foreach ($parametry['faktura']['pozycje'] as $p): ?>
		            <tr>
		              <td><?= $p['ilosc']; ?></td>
		              <td><?= $p['nazwa']; ?></td>
		              <td><?= $p['netto']; ?></td>
		              <td><?= $p['podatek']; ?></td>
		              <td><?= $p['wartosc_podatku']; ?></td>
		              <td><?= $p['brutto']; ?></td>
		            </tr>
	            <?php endforeach; ?>
	            </tbody>
	          </table>
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.row -->
	
	      <div class="row">
	        <div class="col-xs-6">
	          <p class="lead">Zapłata do dnia <?= $parametry['faktura']['data_do_zaplaty']?></p>
	
	          <div class="table-responsive">
	            <table class="table">
	              <tr>
	                <th style="width:50%">Razem netto:</th>
	                <td><?= $parametry['faktura']['razem_netto'];?></td>
	              </tr>
	              <tr>
	                <th>Podatek (<?= $parametry['faktura']['podatek'];?>%)</th>
	                <td><?= $parametry['faktura']['razem_podatek'];?></td>
	              </tr>
	              <tr>
	                <th>Razem brutto:</th>
	                <td><?= $parametry['faktura']['razem_brutto'];?></td>
	              </tr>
	            </table>
	          </div>
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.row -->
	    </section>
    </div><!-- /.box-body -->
</div><!-- /.box -->