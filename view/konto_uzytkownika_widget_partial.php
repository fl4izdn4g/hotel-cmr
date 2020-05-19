<?php
	$logged = Session::pobierz_zalogownego_uzytkownika();
?>


      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= $logged['photo']; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $logged['name']; ?> <?= $logged['surname']; ?></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Aktywny</a>
        </div>
      </div>