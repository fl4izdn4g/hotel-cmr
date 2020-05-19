<?php
?>
  <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        <?php 
          	$hotel_menu = array(
          		'GrupyPokoi',
          		'Pokoje',
          		'Wyposazenie',
          		'Rezerwacje',
          		'GoscieHotelowi',
          	);
          	
          	$hotel_active_class = '';
          	if(isset($_GET['controller']) && in_array($_GET['controller'], $hotel_menu)) {
          		$hotel_active_class = 'active';
          	}
        ?>
        <?php if(Security::moze_wyswietlic_menu('hotel')): ?>
	    	<li class="<?= $hotel_active_class; ?> treeview">
	          <a href="#"><i class="fa fa-hotel"></i> <span>Hotel</span> <i class="fa fa-angle-left pull-right"></i></a>
	          <ul class="treeview-menu">
	          	<?php Html::zrob_element_menu(array('controller' => 'GrupyPokoi'), array('nazwa' => 'Grupy pokoi')); ?>
	          	<?php Html::zrob_element_menu(array('controller' => 'Pokoje'), array('nazwa' => 'Pokoje')); ?>
	          	<?php Html::zrob_element_menu(array('controller' => 'Wyposazenie'), array('nazwa' => 'Wyposażenie')); ?>
	          	<?php Html::zrob_element_menu(array('controller' => 'Rezerwacje'), array('nazwa' => 'Rezerwacje')); ?>
	          	<?php Html::zrob_element_menu(array('controller' => 'GoscieHotelowi'), array('nazwa' => 'Goście hotelowi')); ?>
	          </ul>
	        </li>
        <?php endif; ?>
        <?php 
        	$restauracja_menu = array(
        		'SaleRestauracyjne',
        		'Stoliki',
        		'UzytkownicyRestauracji',
        		'RezerwacjeStolikow',
        	);
        
        	$restauracja_active_class = '';
        	if(isset($_GET['controller']) && in_array($_GET['controller'], $restauracja_menu)) {
        		if(isset($_GET['parent'])) {
        			if($_GET['parent'] == 'restauracja') {
        				$restauracja_active_class = 'active';
        			}
        		}
        		else {
        			$restauracja_active_class = 'active';
        		}
        	}
        ?>
        <?php if(Security::moze_wyswietlic_menu('restauracja')): ?>
        <li class="<?= $restauracja_active_class; ?> treeview">
          <a href="#"><i class="fa fa-cutlery"></i> <span>Restauracja</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          	<?php Html::zrob_element_menu(array('controller' => 'SaleRestauracyjne'), array('nazwa' => 'Sale restauracyjne')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Stoliki'), array('nazwa' => 'Stoliki')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'UzytkownicyRestauracji', 'parent' => 'restauracja'), array('nazwa' => 'Rezerwujący')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'RezerwacjeStolikow'), array('nazwa' => 'Rezerwacje stolików')); ?>
          </ul>
        </li>
        <?php endif; ?>
        <?php 
        	$kuchnia_menu = array(
        		'GrupyProduktow',
        		'Produkty',
        		'StanMagazynowy',
        		'Potrawy',
        		'Menu',
        		'UzytkownicyRestauracji',
        		'Zamowienia',
        	);
        
        	$kuchnia_active_class = '';
        	if(isset($_GET['controller']) && in_array($_GET['controller'], $kuchnia_menu)) {
        		if(isset($_GET['parent'])) {
        			if($_GET['parent'] == 'kuchnia') {
        				$kuchnia_active_class = 'active';
        			}
        		}
        		else {
        			$kuchnia_active_class = 'active';
        		}
        	}
        ?>
        <?php if(Security::moze_wyswietlic_menu('kuchnia')): ?>
        <li class="<?= $kuchnia_active_class; ?> treeview">
          <a href="#"><i class="fa fa-ship"></i> <span>Kuchnia</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          	<?php Html::zrob_element_menu(array('controller' => 'GrupyProduktow'), array('nazwa' => 'Grupy produktów')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Produkty'), array('nazwa' => 'Produkty')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'StanMagazynowy'), array('nazwa' => 'Stan magazynowy')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Potrawy'), array('nazwa' => 'Potrawy')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Menu'), array('nazwa' => 'Menu')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'UzytkownicyRestauracji', 'parent' => 'kuchnia'), array('nazwa' => 'Zamawiający')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Zamowienia'), array('nazwa' => 'Zamówienia')); ?>
         </ul>
        </li>
        <?php endif; ?>
        <?php 
        	$administracja_menu = array(
        		'Administratorzy',
        		'Uzytkownicy',
        		'Faktury',
        		'Role',
        		'Dostep',
        		'Galerie'
        	);
        	
        	$administracja_active_class = '';
        	if(isset($_GET['controller']) && in_array($_GET['controller'], $administracja_menu)) {
        		$administracja_active_class = 'active';
        	}
        ?>      
        <?php if(Security::moze_wyswietlic_menu('administracja')): ?>  
        <li class="<?= $administracja_active_class; ?> treeview">
          <a href="#"><i class="fa fa-desktop"></i> <span>Administracja</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          	<?php Html::zrob_element_menu(array('controller' => 'Administratorzy'), array('nazwa' => 'Administratorzy')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Uzytkownicy'), array('nazwa' => 'Użytkownicy systemu')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Faktury'), array('nazwa' => 'Faktury')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Role'), array('nazwa' => 'Role')); ?>
          	<?php Html::zrob_element_menu(array('controller' => 'Dostep'), array('nazwa' => 'Dostęp')); ?>
			<?php Html::zrob_element_menu(array('controller' => 'Galerie'), array('nazwa' => 'Galerie')); ?>

          </ul>
        </li>
        <?php endif; ?>
      </ul>
      <!-- /.sidebar-menu -->