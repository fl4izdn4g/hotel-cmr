<?php
class Uzytkownicy {
	public $pola = array(
		'uzy_imie',
		'uzy_nazwisko',
		'uzy_telefon_kontaktowy'
	);
	
	public $walidacja = array(
		'uzy_imie' => array(
			'nie_pusty',
		),
		'uzy_nazwisko' => array(
			'nie_pusty'
		),
		'uzy_telefon_kontaktowy' => array(
			'telefon',
		)
	);
	
	public function pobierz_uzytkownika_na_podstawie_konta($konto_id) {
		$sql = "SELECT * FROM hotel_uzytkownicy WHERE uzy_kuz_id = ?";
		$parametry = array($konto_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_wszystkich_uzytkownikow() {
		$sql = "SELECT * FROM hotel_uzytkownicy JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_wszystkich_uzytkownikow_gosci() {
		$sql = "SELECT * FROM hotel_uzytkownicy 
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				JOIN hotel_role ON kuz_rol_id = rol_id 
				WHERE rol_kod LIKE 'GOSC%' AND kuz_status_konta <> 'USUNIETY'";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_uzytkownika($id) {
		$sql = "SELECT * FROM hotel_uzytkownicy
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id WHERE uzy_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_uzytkownika_do_zamowienia($id) {
		$sql = "SELECT uzy_imie, uzy_nazwisko, kuz_email FROM hotel_uzytkownicy
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				WHERE uzy_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	
	public function aktualizuj_telefon($telefon, $user_id) {
		$sql = "UPDATE hotel_uzytkownicy SET uzy_telefon_kontaktowy = ?, 
											 uzy_data_modyfikacji = ?
				WHERE uzy_id = ?";
		$parametry = array(
			$telefon,
			date('Y-m-d H:i:s'),
			$user_id
		);
		
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function aktualizuj_dane_uzytkownika($dane, $user_id) {
		$sql = "UPDATE hotel_uzytkownicy SET uzy_imie = ?, 
											 uzy_nazwisko = ?, 
											 uzy_telefon_kontaktowy = ?, 
											 uzy_data_modyfikacji = ?
				WHERE uzy_id = ?";
		$parametry = array(
			$dane['uzy_imie'],
			$dane['uzy_nazwisko'],
			$dane['uzy_telefon_kontaktowy'],
			date('Y-m-d H:i:s'),
			$user_id
		);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_statusy_uzytkownika() {
		return array(
			'NOWE' => 'Nowe konto',
			'AKTYWNE' => 'Aktywne konto',
			'USUNIETE' => 'Usunięte konto'
		);
	}
	
	
	public function dodaj_uzytkownika($uzytkownik, $konto_id) {
		if(empty($uzytkownik['imie']) || empty($uzytkownik['nazwisko'])) {
			return false;
		}
		
		if(empty($konto_id) || $konto_id < 0) {
			return false;
		}
		
		$imie = $uzytkownik['imie'];
		$nazwisko = $uzytkownik['nazwisko'];
		$telefon = '';
		if(isset($uzytkownik['telefon'])) {
			$telefon = $uzytkownik['telefon'];
		}
		
		$current_date = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO hotel_uzytkownicy (uzy_imie, uzy_nazwisko, uzy_telefon_kontaktowy, uzy_kuz_id, uzy_data_modyfikacji)
				VALUES (?,?,?,?,?)";
		$parametry = array(
				$imie,
				$nazwisko,
				$telefon,
				$konto_id,
				$current_date
		);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$sql = "SELECT uzy_id FROM hotel_uzytkownicy WHERE uzy_kuz_id = ? ORDER BY uzy_kuz_id DESC LIMIT 1";
		$parametry = array(
			$konto_id
		);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			$id = $result[0]['uzy_id'];
			return $id;
		}
		
		return false;
	}
	
	public function aktywuj_konto($id) {
		$uzytkownik = $this->pobierz_uzytkownika($id);
		
		$sql = "UPDATE hotel_konta_uzytkownikow SET kuz_status_konta = 'AKTYWNE' 
				WHERE kuz_id = ?";
		$parametry = array($uzytkownik[0]['uzy_kuz_id']);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
} 