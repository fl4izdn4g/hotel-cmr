<?php
class Pokoje {
	public $pola = array(
		'pok_numer',
		'pok_liczba_osob',
		'pok_pietro',
		'pok_zdjecie',
		'pok_grp_id'
	);
	
	public $walidacja = array(
		'pok_numer' => array(
			'nie_pusty',
			'liczba'
		),
		'pok_liczba_osob' => array(
			'nie_pusty',
			'liczba'
		),
		'pok_pietro' => array(
			'nie_pusty',
			'liczba'
		),
		'pok_zdjecie' => array(
			'nie_pusty'
		),
		'pok_grp_id' => array(
			'nie_pusty'
		)
	);
	
	
	public function pobierz_wszystkie_pokoje() {
		$sql = "SELECT * FROM hotel_pokoje";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_pokoj($id) {
		$sql = "SELECT * FROM hotel_pokoje WHERE pok_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_pokoj_rezerwacje($pokoj_id) {
		$sql = "SELECT * FROM hotel_pokoje
				JOIN hotel_grupy_pokoi ON pok_grp_id = grp_id
				WHERE pok_id = ?";
		$parametry = array($pokoj_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}