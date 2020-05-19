<?php
class Potrawy {
	public $pola = array(
		'pot_nazwa',
		'pot_zdjecie',
		'pot_opis',
		'pot_wegetarianska',
		'pot_bezglutenowa'
	);
	
	public $walidacja = array(
		'pot_nazwa' => array(
			'nie_pusty'
		),
		'pot_zdjecie' => array(
			'nie_pusty'
		),
		'pot_opis' => array(
			'nie_pusty'
		),
	);
	
	public function pobierz_wszystkie_potrawy() {
		$sql = "SELECT * FROM hotel_potrawy";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_potrawy_rezerwacje($zamowienie_id) {
		$sql = "SELECT * FROM hotel_zamowienie_x_potrawy
				JOIN hotel_potrawy ON zxp_pot_id = pot_id
				WHERE zxp_zp_id = ?";
		$parametry = array($zamowienie_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}