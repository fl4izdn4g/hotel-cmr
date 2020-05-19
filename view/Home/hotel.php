
<div class="row">
	<div class="col-md-4">
		<div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Liczba pokoi</span>
              <span class="info-box-number"><?= (View::pobierz('liczba_pokoi')) ? View::pobierz('liczba_pokoi'): 0; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
	
		
	</div>
	<div class="col-md-4">
		<div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Liczba gości</span>
              <span class="info-box-number"><?= (View::pobierz('liczba_gosci')) ? View::pobierz('liczba_gosci') : 0; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
	</div>
	<div class="col-md-4">
		<div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Liczba rezerwacji</span>
              <span class="info-box-number"><?= (View::pobierz('liczba_rezerwacji')) ? View::pobierz('liczba_rezerwacji') : 0; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
    	<h3 class="box-title">Miesięczne zestawienie</h3>
        <div class="box-tools pull-right">
        	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div style="display: block;" class="box-body">
 	   <div class="row">
       		<div class="col-md-12">
            	<p class="text-center">
                    <strong><?= View::pobierz('zestawienie_okres'); ?></strong>
                </p>
				<div class="chart">
                	<canvas height="180" width="703" id="zestawienieHotelChart" style="height: 180px; width: 703px;"></canvas>
                </div>
            </div>
        </div>
    </div>
           
    
          </div>
<div class="row">
	<div class="col-md-4">
	<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Ostatnie rezerwacje pokoi</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if (!empty(View::pobierz('ostanie_pokoje'))): ?>
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Nazwa</th>
                    <th>Cena</th>
                    <th>Anulowana</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach (View::pobierz('ostanie_pokoje') as $p):?>
	                  <tr>
	                    <td><a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'podglad', 'id' => $p['rp_id']))?>"><?= $p['rp_id']; ?></a></td>
	                    <td><?= $p['rp_nazwa']; ?></td>
	                    <td><?= $p['rp_cena_netto']; ?></td>
	                    <td><?= $p['rp_anulowano'] ?></td>
	                  </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php else: ?>
              	Brak rezerwacji
              <?php endif; ?>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje', 'action' => 'dodaj'))?>" class="btn btn-sm btn-primary btn-flat pull-left">Nowa rezerwacja</a>
              <a href="<?= Router::utworz_link(array('controller' => 'Rezerwacje'))?>" class="btn btn-sm btn-default btn-flat pull-right">Wszystkie rezerwacje</a>
            </div>
            <!-- /.box-footer -->
          </div>         
	</div>
	<div class="col-md-4">
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Ostatnie rezerwacje pokoi</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="chart">
                	<canvas height="250" width="250" id="zestawieniePopularnePokojeChart" style="height: 250px; width: 250px;"></canvas>
                </div>
            </div>
        </div>
	</div>
	
	<div class="col-md-4">
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Najpopularniejsza grupa pokoi</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="chart">
                	<canvas height="250" width="250" id="zestawieniePopularneGrupyChart" style="height: 250px; width: 250px;"></canvas>
                </div>
            </div>
        </div>
	</div>
	
</div>
 