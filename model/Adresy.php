<?php
class Adresy {
	public $pola = array(
		'adr_ulica',
		'adr_numer_domu',
		'adr_numer_mieszkania',
		'adr_kod_pocztowy',
		'adr_miejscowosc',
		'adr_wojewodztwo'
	);
	
	public $walidacja = array(
		'adr_ulica' => array(
			'nie_pusty'
		),
		'adr_numer_domu' => array(
			'nie_pusty'
		),
		'adr_kod_pocztowy' => array(
			'nie_pusty',
			'kod_pocztowy'
		),
		'adr_miejscowosc' => array(
			'nie_pusty'
		),
		'adr_wojewodztwo' => array(
			'nie_pusty'
		)
	);
	
	public function dodaj_adres($dane) {
		$sql = "INSERT INTO hotel_adresy (
						adr_ulica,
						adr_numer_domu,
						adr_numer_mieszkania,
						adr_kod_pocztowy,
						adr_miejscowosc,
						adr_wojewodztwo) 
				VALUES (?,?,?,?,?,?)";
		$parametry = array(
			$_POST['adr_ulica'],
			$_POST['adr_numer_domu'],
			$_POST['adr_numer_mieszkania'],
			$_POST['adr_kod_pocztowy'],
			$_POST['adr_miejscowosc'],
			$_POST['adr_wojewodztwo']
		);
		
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$sql = "SELECT adr_id FROM hotel_adresy ORDER BY adr_id DESC LIMIT 1";
		$result = Model::wykonaj_zapytanie_sql($sql);
		
		$adres_id = null;
		if($result) {
			$adres_id = $result[0]['adr_id'];
		}
		return $adres_id;
		
	}
	
	public function dodaj_domyslny_adres($adres_id, $uzytkownik_id) {
		$sql = "INSERT INTO hotel_uzytkownicy_x_adresy (uxa_adr_id, uxa_uzy_id, uxa_domyslny) VALUES (?, ?, 1)";
		$parametry = array($adres_id, $uzytkownik_id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	
	
	public function pobierz_wojewodztwa() {
		return array(
			'DOLNOSLASKIE' => 'dolnośląskie',
			'KUJAWSKO_POMORSKIE' => 'kujawsko-pomorskie',
			'LUBELSKIE' => 'lubelskie',
			'LUBUSKIE' => 'lubuskie',
			'LODZKIE' => 'łódzkie',
			'MALOPOLSKIE' => 'małopolskie',
			'MAZOWIECKIE' => 'mazowieckie',
			'OPOLSKIE' => 'opolskie',
			'PODKARPACKIE' => 'podkarpackie',
			'PODLASKIE' => 'podlaskie',
			'POMORSKIE' => 'pomorskie',
			'SLASKIE' => 'śląskie',
			'SWIETOKRZYSKIE' => 'świętokrzyskie',
			'WARMINSKO_MAZURSKIE' => 'warmińsko-mazurskie',
			'WIELKOPOLSKIE' => 'wielkopolskie',
			'ZACHODNIOPOMORSKIE' => 'zachodniopomorskie',
		);
	}
	
	public function pobierz_adresy_uzytkownika($uzytkownik_id) {
		$sql = "SELECT * FROM hotel_adresy 
				JOIN hotel_uzytkownicy_x_adresy ON uxa_adr_id = adr_id
				WHERE uxa_uzy_id = ?";
		
		$parametry = array($uzytkownik_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	
	public function pobierz_adres($adres_id) {
		$sql = "SELECT * FROM hotel_adresy WHERE adr_id = ?";
		$parametry = array($adres_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function powiaz_adres($adres_id, $user_id, $domyslny) {
		$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ?";
		$parametry = array($user_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(empty($result)) {
			$sql = "INSERT INTO hotel_uzytkownicy_x_adresy (uxa_adr_id, uxa_uzy_id, uxa_domyslny)
					VALUES (?,?,?)";
			$parametry = array($adres_id, $user_id, 1);
			Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			if($domyslny) {
				$sql = "UPDATE hotel_uzytkownicy_x_adresy SET uxa_domyslny = 0 WHERE uxa_uzy_id = ?";
				$parametry = array($user_id);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
			}
			//czy taki adres już istnieje 
			$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ? AND uxa_adr_id = ?";
			$parametry = array($user_id, $adres_id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			if(!empty($result)) {
				$sql = "UPDATE hotel_uzytkownicy_x_adresy SET uxa_adr_id = ?, uxa_uzy_id = ?, uxa_domyslny = ?
						WHERE uxa_id = ?";
				$parametry = array($adres_id, $user_id, $domyslny, $result[0]['uxa_id']);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
			}
			else {
				$sql = "INSERT INTO hotel_uzytkownicy_x_adresy (uxa_adr_id, uxa_uzy_id, uxa_domyslny)
						VALUES (?,?,?)";
				$parametry = array($adres_id, $user_id, $domyslny);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
			}
		}
	}
	
	public function pobierz_wiazanie($adres_id, $user_id) {
		$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_adr_id = ? AND uxa_uzy_id = ?";
		$parametry = array($adres_id, $user_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		return $result;
	}
	
	public function aktualizuj_adres($dane, $adres_id) {
		$sql = "UPDATE hotel_adresy SET 
					adr_ulica = ?,
					adr_numer_domu = ?,
					adr_numer_mieszkania = ?,
					adr_kod_pocztowy = ?,
					adr_miejscowosc = ?,
					adr_wojewodztwo = ?
				WHERE adr_id = ?";
		
		$parametry = array(
			$dane['adr_ulica'],
			$dane['adr_numer_domu'],
			$dane['adr_numer_mieszkania'],
			$dane['adr_kod_pocztowy'],
			$dane['adr_miejscowosc'],
			$dane['adr_wojewodztwo'],
			$adres_id
		);
		
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}