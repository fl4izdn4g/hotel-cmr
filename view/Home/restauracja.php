
<div class="row">
	<div class="col-md-4">
		<div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Liczba stolików</span>
              <span class="info-box-number"><?= View::pobierz('ile_stoliki')?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
	</div>
	<div class="col-md-4">
		<div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Liczba potraw</span>
              <span class="info-box-number"><?= View::pobierz('ile_potrawy')?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
	</div>
	<div class="col-md-4">
		<div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Liczba zamówień</span>
              <span class="info-box-number"><?= View::pobierz('ile_zamowienia'); ?></span>
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
       		<div class="col-md-8">
            	<p class="text-center">
                    <strong><?= View::pobierz('zestawienie_okres'); ?></strong>
                </p>
				<div class="chart">
                	<canvas height="180" width="703" id="restauracjaChart" style="height: 180px; width: 703px;"></canvas>
                </div>
            </div>
            <div class="col-md-4">
            	<p class="text-center">
                	<strong>Za miesiąc <?= View::pobierz('aktualny_miesiac'); ?> (% zrealizowanych)</strong>
                </p>
                
                <div class="progress-group">
                	<span class="progress-text">Rezerwacje stolików</span>
                    <span class="progress-number"><b><?= (View::pobierz('stoliki_miesiecznie')) ? View::pobierz('stoliki_miesiecznie') : '0';?></b>/<?= View::pobierz('stoliki_miesiecznie_lacznie')?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: <?= round(View::pobierz('stoliki_miesiecznie_procent'))?>%"></div>
                    </div>
                </div>
                
				<div class="progress-group">
                    <span class="progress-text">Zamówienia potraw</span>
                    <span class="progress-number"><b><?= (View::pobierz('potrawy_miesiecznie')) ? View::pobierz('potrawy_miesiecznie'): '0';?></b>/<?= View::pobierz('potrawy_miesiecznie_lacznie')?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: <?= round(View::pobierz('potrawy_miesiecznie_procent'))?>%"></div>
                    </div>
                </div>
            </div>
		</div>
    </div>
           
    <div style="display: block;" class="box-footer">
    	<div class="row">
                <div class="col-sm-4 col-xs-6">
                 <div class="description-block border-right">
                    <span class="description-percentage <?= View::pobierz('stoliki_klasa_kolor'); ?>"><i class="fa <?= View::pobierz('stoliki_klasa_strzalka')?>"></i> <?= View::pobierz('stoliki_procentowy')?>%</span>
                    <h5 class="description-header"><?= View::pobierz('stoliki_przychod'); ?></h5>
                    <span class="description-text">PRZYCHÓD STOLIKI</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage <?= View::pobierz('potrawy_klasa_kolor'); ?>"><i class="fa <?= View::pobierz('potrawy_klasa_strzalka')?>"></i> <?= View::pobierz('potrawy_procentowy')?>%</span>
                    <h5 class="description-header"><?= View::pobierz('potrawy_przychod'); ?></h5>
                    <span class="description-text">PRZYCHÓD POTRAWY</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage <?= View::pobierz('razem_klasa_kolor'); ?>"><i class="fa <?= View::pobierz('razem_klasa_strzalka')?>"></i> <?= View::pobierz('razem_procentowy')?>%</span>
                    <h5 class="description-header"><?= View::pobierz('razem_przychod'); ?></h5>
                    <span class="description-text">ŁĄCZNIE</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
<div class="row">
	<div class="col-md-6">
	<div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ostatnie rezerwacje stolika</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if (!empty(View::pobierz('ostanie_stoliki'))): ?>
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
                  <?php foreach (View::pobierz('ostanie_stoliki') as $p):?>
                  <tr>
                    <td><a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'podglad', 'id' => $p['rs_id']))?>"><?= $p['rs_id']; ?></a></td>
                    <td><?= $p['rs_nazwa']; ?></td>
                    <td><?= $p['rs_cena_netto']; ?></td>
                    <td><?= $p['rs_anulowano'] ?></td>
                  </tr>
                  <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
              <?php else: ?>
              	Brak rezerwacji
              <?php endif; ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow', 'action' => 'dodaj'))?>" class="btn btn-sm btn-primary btn-flat pull-left">Nowa rezerwacja</a>
              <a href="<?= Router::utworz_link(array('controller' => 'RezerwacjeStolikow'))?>" class="btn btn-sm btn-default btn-flat pull-right">Wszystkie rezerwacje</a>
            </div>
            <!-- /.box-footer -->
          </div>         
	</div>
	<div class="col-md-6">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Ostatnie zamówienia potraw</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
             </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if (!empty(View::pobierz('ostanie_potrawy'))): ?>
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
                  <?php foreach (View::pobierz('ostanie_potrawy') as $p):?>
	                  <tr>
	                    <td><a href="<?= Router::utworz_link(array('controller' => 'Zamowienia', 'action' => 'podglad', 'id' => $p['zp_id']))?>"><?= $p['zp_id']; ?></a></td>
	                    <td><?= $p['zp_nazwa']; ?></td>
	                    <td><?= $p['zp_cena_calkowita_netto']; ?></td>
	                    <td><?= $p['zp_anulowano'] ?></td>
	                  </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php else: ?>
              	Brak zamówień
              <?php endif; ?>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="<?= Router::utworz_link(array('controller' => 'Zamowienia', 'action' => 'dodaj')); ?>" class="btn btn-sm btn-primary btn-flat pull-left">Nowe zamówienie</a>
              <a href="<?= Router::utworz_link(array('controller' => 'Zamowienia')); ?>" class="btn btn-sm btn-default btn-flat pull-right">Wszystkie zamówienia</a>
            </div>
            <!-- /.box-footer -->
          </div>         
	</div>
</div>
 
 <div class="row">
	 	<div class="col-md-4">
	 	<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Produkty na wyczerpaniu</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php 
	            $statusy = array(
	            	'WARNING' => 'fa fa-fw fa-exclamation-triangle color-warning',
	            	'ERROR' => 'fa fa-fw fa-minus-square color-error',
	            );
            
            ?>
            
			<?php if (!empty(View::pobierz('stan_magazynowy'))): ?>
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Nazwa</th>
                    <th style="width: 36px">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach (View::pobierz('stan_magazynowy') as $p):?>
	                  <tr>
	                    <td><a href="<?= Router::utworz_link($parametry)?>"><?= $p['prod_nazwa']; ?></a></td>
	                    <td><i class="<?= $statusy[$p['stm_status']]; ?>" style="font-size: 20px"></i></td>
	                  </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php else: ?>
              	Wystarczająca ilość składników w magazynie
              <?php endif; ?>
              <!-- /.table-responsive -->	
            </div>
            <!-- /.box-body -->
          </div>         
 	</div>
 	<div class="col-md-4">
	     <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Popularne sale restauracyjne</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="chart">
                	<canvas height="250" width="250" id="popularneSaleChart" style="height: 250px; width: 250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
          </div>         
	</div>
	<div class="col-md-4">
      	<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Popularne potrawy</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<div class="chart">
                	<canvas height="250" width="250" id="popularnePotrawyChart" style="height: 250px; width: 250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
          </div>         
	</div>
	
</div>
 
          