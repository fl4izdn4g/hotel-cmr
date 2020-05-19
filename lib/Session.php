<?php
class Session {
	public static function inicjalizacja() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}
	
	public static function ustaw_alert($type, $message) {
		Session::inicjalizacja();
		$_SESSION['alert'][$type][] = $message;
	}
	
	public static function pobierz_alert($type) {
		$alerts = isset($_SESSION['alert'][$type]) ? $_SESSION['alert'][$type] : array();
		unset($_SESSION['alert'][$type]);
		
		return $alerts;
	}
	
	public static function pobierz_wszystkie_alerty() {
		$alerts = isset($_SESSION['alert']) ? $_SESSION['alert'] : array();
		unset($_SESSION['alert']);
		
		return $alerts;
	}
	
	public static function zapamietaj_zalogowanego_uzytkownika() {
		Session::inicjalizacja();
		/*$_SESSION['logged_user_data'] = array(
			'is_logged' => true,
			'name' => 'Peter',
			'surname' => 'Parker',
			'id' => 1,
			'job' => 'Kierownik',
			'photo' => Router::poprawny_url_obrazka('0c1b68565098564eaf78efdc13a1ae65.jpg', 'Administratorzy', 'small'),
			'registration_date' => date('Y-m-d'),
			'role_code' => 'ADMINISTRATOR'
		);*/
	}
	
	public function usun_zalogowanego_uzytkownika() {
		unset($_SESSION['logged_user_data']);
	}
	
	public static function pobierz_zalogownego_uzytkownika() {
		return empty($_SESSION['logged_user_data']) ? null : $_SESSION['logged_user_data'];
	}
	
	public static function ustaw($klucz, $wartosc) {
		Session::inicjalizacja();
		$_SESSION[$klucz] = $wartosc;
	}
	
	public static function pobierz($klucz) {
		return isset($_SESSION[$klucz]) ? $_SESSION[$klucz] : null;
	}
	
	public static function usun($klucz) {
		unset($_SESSION[$klucz]);
	}
} 