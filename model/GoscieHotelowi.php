<?php
class GoscieHotelowi {
	public $pola = array(
		'gh_pesel',
		'gh_zagraniczny',
		'gh_typ_dokumentu_tozsamosci',
		'gh_numer_dokumentu_tozsamosci',
			
	);
	
	public $walidacja = array(
		'gh_pesel' => array(
			'nie_pusty',
			'dlugosc' => array(
				'rowna' => 11
			),
			'liczba',
			'pesel',
		),
		'gh_typ_dokumentu_tozsamosci' => array(
			'nie_pusty'
		),
		'gh_numer_dokumentu_tozsamosci' => array(
			'nie_pusty'
		),
			
	);
	
	public function pobierz_typy_dokumentow() {
		return array(
			'DOWOD_OSOBISTY' => 'Dowód osobisty',
			'PASZPORT' => 'Paszport',
			'PRAWO_JAZDY' => 'Prawo jazdy',
			'INNY' => 'Inny'
		);
	}
	
	public function pobierz_gosci() {
		$sql = "SELECT * FROM hotel_uzytkownicy 
			JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
			JOIN hotel_role ON kuz_rol_id = rol_id
			LEFT JOIN hotel_goscie_hotelowi ON gh_uzy_id = uzy_id
			WHERE rol_kod LIKE 'GOSC%'";
		
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_goscia_hotelowego($uzy_id) {
		$sql = "SELECT uzy_imie, uzy_nazwisko, kuz_email, gh_pesel FROM hotel_goscie_hotelowi
				JOIN hotel_uzytkownicy ON gh_uzy_id = uzy_id
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				WHERE uzy_id = ?";
		$parametry = array($uzy_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}