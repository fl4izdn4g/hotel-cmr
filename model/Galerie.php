<?php
class Galerie {
	public $pola = array(
		'gal_nazwa',
		'gal_opis',
		'gal_widoczna',
	);
	
	public $walidacja =  array(
		'gal_nazwa' => array(
			'nie_pusty',
		),
		'gal_opis' => array(
			'nie_pusty',
		),
	);
	
	public function pobierz_wszystkie_galerie() {
		$sql = "SELECT * FROM hotel_galerie";
		return Model::wykonaj_zapytanie_sql($sql);
	}
		
	public function pobierz_galerie($id) {
		$sql = "SELECT * FROM hotel_galerie WHERE gal_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function ostatnie_id() {
		$sql = "SELECT gal_id FROM hotel_galerie ORDER BY gal_id DESC LIMIT 1";
		$dane = Model::wykonaj_zapytanie_sql($sql);
		return $dane[0]['gal_id'];
	}
}