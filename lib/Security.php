<?php
class Security {
	
	const KONTROLER_TYPE = "KONTROLER";
	const AKCJA_TYPE = "AKCJA";
	const MENU_TYPE = "MENU";
	
	public static function czy_zalogowany() {
		$zalogowany = false;
		
		$user = Session::pobierz_zalogownego_uzytkownika();
		if(!empty($user)) {
			$zalogowany = true;
		}
			
		return $zalogowany;
	}
	
	public static function nie_wymaga_logowania($typ, $nazwa) {
		$wyjatki = array(
			Security::KONTROLER_TYPE => array(
				'LoginController'					
			),
		);
		
		return in_array($nazwa, $wyjatki[$typ]);
	}
	
	
	public static function ma_dostep($type, $name) {
		//var_dump($type, $name); die;
		
		if(Security::KONTROLER_TYPE == $type && $name == 'LoginController') {
			return true;
		}
		
		$ma_dostep = false;
		$user = Session::pobierz_zalogownego_uzytkownika();
		if(empty($user)) {
			return false;
		}
		$ma_dostep = false;
		if(strpos($user['role_code'], 'ADMINISTRATOR') == 0) {
			$ma_dostep = true;
		}
		
		$sql = "SELECT * FROM hotel_dostep 
				JOIN hotel_role ON dos_rol_id = rol_id
				JOIN hotel_konta_uzytkownikow ON kuz_rol_id = rol_id
				JOIN hotel_uzytkownicy ON uzy_kuz_id = kuz_id
				WHERE rol_kod = ? AND dos_typ = ? AND dos_obiekt = ?";
		
		$parametry = array($user['role_code'], $type, $name);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);	
		
		if(empty($result)) { 
			return false;
		}
		
		return $ma_dostep;
	}
	
	public static function moze_wyswietlic_menu($menu) {
		$user = Session::pobierz_zalogownego_uzytkownika();
		
		if($user['role_code'] == 'ADMINISTRATOR_GLOWNY') {
			return true;
		}
		
		$sql = "SELECT * FROM hotel_dostep
				JOIN hotel_role ON dos_rol_id = rol_id
				JOIN hotel_konta_uzytkownikow ON kuz_rol_id = rol_id
				JOIN hotel_uzytkownicy ON uzy_kuz_id = kuz_id
				WHERE rol_kod = ? AND dos_typ = ? AND dos_obiekt = ?";
		
		$parametry = array($user['role_code'], Security::MENU_TYPE, $menu);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			return true;
		}
		
		return false;
	}
	
	public static function przekierowanie_do_logowania($link_powrotu = null) {
		$link = array(
			'controller' => 'Login',
			'action' => 'login'
		);
		
		if(!empty($link_powrotu)) {
			$link['return'] = base64_encode($link_powrotu);
		}
		
		Router::przekierowanie($link);
	}
	
	public static function przekierowanie_do_home() {
		Session::ustaw_alert('warning', 'Nie posiadasz odpowiednich uprawnień, aby skorzystać z tego elementu. Skontaktuj się z administratorem.');
		
		$link = array(
			'controller' => 'Home',
		);
	
		Router::przekierowanie($link);
	}
	
}