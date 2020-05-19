<?php
	$logged = Session::pobierz_zalogownego_uzytkownika();

?>

 <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="<?= $logged['photo']; ?>" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?= $logged['name']; ?> <?= $logged['surname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="<?= $logged['photo']; ?>" class="img-circle" alt="User Image">

                <p>
                  <?= $logged['name']; ?> <?= $logged['surname']; ?> - <?= $logged['job']; ?>
                  <small>Administrator od <?= $logged['registration_date']; ?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                  <a href="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'logout')); ?>" class="btn btn-default btn-flat">Wyloguj się</a>
              </li>
            </ul>
          </li>