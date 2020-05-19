
<div class="row">
	<div class="col-md-4">
		<div class="info-box bg-navy">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">WSZYSCY UŻYTKOWNICY</span>
              <span class="info-box-number"><?= View::pobierz('wszyscy_suma'); ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: <?= round(View::pobierz('wszyscy_procent_aktywnych')); ?>%"></div>
              </div>
                  <span class="progress-description">
                    <?= View::pobierz('wszyscy_procent_aktywnych')?>% aktywnych
                  </span>
            </div>
            <!-- /.info-box-content -->
        </div>
	
		
	</div>
	<div class="col-md-4">
		<div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">GOŚCIE</span>
              <span class="info-box-number"><?= View::pobierz('goscie_suma'); ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: <?= round(View::pobierz('goscie_procent_aktywnych')); ?>%"></div>
              </div>
                  <span class="progress-description">
                    <?= View::pobierz('goscie_procent_aktywnych')?>% aktywnych
                  </span>
            </div>
            <!-- /.info-box-content -->
         </div>
	</div>
	<div class="col-md-4">
		<div class="info-box bg-teal">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">ADMINISTRATORZY</span>
              <span class="info-box-number"><?= View::pobierz('administratorzy_suma'); ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: <?= round(View::pobierz('administratorzy_procent_aktywnych')); ?>%"></div>
              </div>
                  <span class="progress-description">
                    <?= View::pobierz('administratorzy_procent_aktywnych')?>% aktywnych
                  </span>
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
                	<canvas height="180" width="703" id="zestawienieChart" style="height: 180px; width: 703px;"></canvas>
                </div>
            </div>
            <div class="col-md-4">
            	<p class="text-center">
                	<strong>Za miesiąc <?= View::pobierz('aktualny_miesiac'); ?> (% zrealizowanych)</strong>
                </p>
                <div class="progress-group">
                	<span class="progress-text">Rezerwacje pokoi</span>
                    <span class="progress-number"><b><?= (View::pobierz('pokoje_miesiecznie')) ? View::pobierz('pokoje_miesiecznie') : '0';?></b>/<?= View::pobierz('pokoje_miesiecznie_lacznie')?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: <?= round(View::pobierz('pokoje_miesiecznie_procent'))?>%"></div>
                    </div>
                </div>
                
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
        	<div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage <?= View::pobierz('pokoje_klasa_kolor'); ?>"><i class="fa <?= View::pobierz('pokoje_klasa_strzalka')?>"></i> <?= View::pobierz('pokoje_procentowy')?>%</span>
                    <h5 class="description-header"><?= View::pobierz('pokoje_przychod'); ?></h5>
                    <span class="description-text">PRZYCHÓD POKOJE</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                 <div class="description-block border-right">
                    <span class="description-percentage <?= View::pobierz('stoliki_klasa_kolor'); ?>"><i class="fa <?= View::pobierz('stoliki_klasa_strzalka')?>"></i> <?= View::pobierz('stoliki_procentowy')?>%</span>
                    <h5 class="description-header"><?= View::pobierz('stoliki_przychod'); ?></h5>
                    <span class="description-text">PRZYCHÓD STOLIKI</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage <?= View::pobierz('potrawy_klasa_kolor'); ?>"><i class="fa <?= View::pobierz('potrawy_klasa_strzalka')?>"></i> <?= View::pobierz('potrawy_procentowy')?>%</span>
                    <h5 class="description-header"><?= View::pobierz('potrawy_przychod'); ?></h5>
                    <span class="description-text">PRZYCHÓD POTRAWY</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-xs-6">
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
	<div class="col-md-4">
	<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Ostatnie zamówienia potrawy</h3>
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
 
          