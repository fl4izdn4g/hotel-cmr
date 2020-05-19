<?php
class Dostep {
	public $pola = array(
		'dos_typ',
		'dos_obiekt',
		'dos_rol_id'
	);
	
	public $walidacja = array(
		'dos_typ' => array(
			'nie_pusty',
		),
		'dos_obiekt' => array(
			'nie_pusty',
			'dlugosc' => array('max' => 45)
		),
		'dos_rol_id' => array(
			'nie_pusty'
		)
	);
	
	public function pobierz_typy_obiektow() {
		return array(
			'KONTROLER' => 'Kontroler',
			//'AKCJA' => 'Akcja',
			'MENU' => 'Menu'
		);
	}
	
	public function pobierz_wszystkie_dostepy() {
		$sql = "SELECT * FROM hotel_dostep";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	
}