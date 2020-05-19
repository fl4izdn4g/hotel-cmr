<?php
require_once 'lib/Session.php';
require_once 'lib/Router.php';
require_once 'lib/Application.php';
require_once 'lib/Html.php';
require_once 'lib/Mail.php';

require_once 'lib/View.php';
require_once 'lib/Model.php';

require_once 'lib/Security.php';
require_once 'lib/Installation.php';

$konfiguracja = include_once 'konfiguracja.php';

Session::inicjalizacja();

//Session::zapamietaj_zalogowanego_uzytkownika();

$installation = new Installation();
if($installation->should_install()) {
	$installation->install();
}

$router = new Router();
$application = new Application($router);
$application->run();