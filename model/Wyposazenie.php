<?php
class Wyposazenie {
	public $pola = array(
		'wyp_nazwa',
		'wyp_opis',
		'wyp_ikona'
	);
	
	public $walidacja = array(
		'wyp_nazwa' => array(
			'nie_pusty'
		),
		'wyp_opis' => array(
			'nie_pusty'
		),
		'wyp_ikona' => array(
			'nie_pusty'
		)
	);
	
	public function pobierz_wszystkie_wyposazenia() {
		$sql = "SELECT * FROM hotel_wyposazenie";
		return Model::wykonaj_zapytanie_sql($sql);
	}
}