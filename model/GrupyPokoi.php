<?php
class GrupyPokoi {
	public $pola = array(
		'grp_nazwa',
		'grp_cena_netto',
		'grp_opis',
		'grp_ikona',
	);
	
	public $walidacja =  array(
		'grp_nazwa' => array(
				'nie_pusty',
				'dlugosc' => array('max' => 255)
		),
		'grp_cena_netto' => array(
				'nie_pusty',
				'liczba',
				'dlugosc' => array('max' => 9)
		),
		'grp_opis' => array(
				'nie_pusty',
				'dlugosc' => array('max' => 1000)
		),
		'grp_ikona' => array(
				'nie_pusty',
				'dlugosc' => array('max' => 255)
		),
	);
	
	public function pobierz_wszystkie_grupy_pokoi() {
		$sql = "SELECT * FROM hotel_grupy_pokoi";
		return Model::wykonaj_zapytanie_sql($sql);
	}
		
	public function pobierz_grupe_pokoi($id) {
		$sql = "SELECT * FROM hotel_grupy_pokoi WHERE grp_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function ostatnie_id() {
		$sql = "SELECT grp_id FROM hotel_grupy_pokoi ORDER BY grp_id DESC LIMIT 1";
		$dane = Model::wykonaj_zapytanie_sql($sql);
		return $dane[0]['grp_id'];
	}
	
	public function pobierz_grupe_pokoi_na_podstawie_pokoju($pokoj_id) {
		$sql = "SELECT * FROM hotel_grupy_pokoi 
				JOIN hotel_pokoje ON pok_grp_id = grp_id
				WHERE pok_id = ?";
		$parametry = array($pokoj_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}