<?php
class GrupyProduktow {
	public $pola = array(
		'grpp_nazwa', 
		'grpp_opis', 
		'grpp_ikona'
	);
	
	public $walidacja = array(
		'grpp_nazwa' => array(
			'nie_pusty'
		),
		'grpp_opis' => array(
			'nie_pusty'
		),
		'grpp_ikona' => array(
			'nie_pusty'
		)
	);
	
	public function pobierz_wszystkie_grupy_produktow() {
		$sql = "SELECT * FROM hotel_grupy_produktow";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_grupe_produktow($id) {
		
	}
}