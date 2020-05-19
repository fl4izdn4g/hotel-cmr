<?php
	require_once dirname(__FILE__).'/head_layout.php';
?>
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="<?= Router::utworz_link(array('controller' => 'Home'))?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>H</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>HOTEL</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Włącz/wyłącz menu</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <?php View::pokaz_element('wiadomosci'); ?>
		  <?php //View::pokaz_element('zdarzenia'); ?>
          <?php View::pokaz_element('konto_uzytkownika'); ?>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">


	  <?php View::pokaz_element('konto_uzytkownika_widget'); ?>	
	  <?php View::pokaz_element('menu'); ?>


    
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= View::pobierz('tytul_strony'); ?>
        <small><?= View::pobierz('podtytul_strony'); ?></small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  <?php Html::pokaz_alerty(); ?>
      <!-- Your Page Content Here -->

   