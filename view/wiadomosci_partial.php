<?php
	$kontakty_model = Model::zaladuj_model('Kontakty');
	$liczba_nieprzeczytanych = $kontakty_model->policz_nieprzeczytane();

	$kontakty_limit = $kontakty_model->pobierz_wszystkie_kontakty(5);

?>

<!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"><?= $liczba_nieprzeczytanych; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Do przeczytania <?= $liczba_nieprzeczytanych; ?> wiadomości</li>
              <li>
              	<?php if(!empty($kontakty_limit)): ?>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <?php foreach ($kontakty_limit as $k): ?>
	                  <li><!-- start message -->
	                    <a href="<?= Router::utworz_link(array('controller' => 'Kontakty', 'action' => 'wyswietl', 'id' => $k['kon_id']))?>">
	                      <div class="pull-left">
	                      	<?php 
	                      		$color = '#666';
	                      		if(!empty($k['kon_data_przeczytania'])) {
	                      			$color = '#ddd';
	                      		}
	                      	?>
	                      	
	                        <span class="fa fa-fw fa-envelope" style="font-size: 20px; color: <?= $color; ?> "> </span>
	                      </div>
	                      <h4>
	                      	<?php ?>
	                        <?= $k['kon_tytul']; ?>
	                        <small><i class="fa fa-clock-o"></i> <?php echo Html::pokaz_ile_czasu_minelo($k['kon_data_dodania']); ?></small>
	                      </h4>
	                      <p><?= $k['kon_kategoria']; ?></p>
	                    </a>
	                  </li>
	                  <!-- end message -->
                  <?php endforeach; ?>
                </ul>
                <?php else: ?>
                	<p style="margin-left: 10px">Brak wiadomości</p>
                <?php endif;?>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="<?= Router::utworz_link(array('controller' => 'Kontakty'))?>">Wyświetl wszystkie wiadomości</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->