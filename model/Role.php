<?php
class Role {
	public $pola = array(
		'rol_nazwa',
		'rol_opis',
		'rol_kod'
	);
	
	public $walidacja = array(
		'rol_nazwa' => array(
			'nie_pusty',
			'dlugosc' => array('max' => '45')
		),
		'rol_opis' => array(
			'nie_pusty',
			'dlugosc' => array('max' => '200')
		),
		'rol_kod' => array(
			'nie_pusty',
			'dlugosc' => array('max' => '45')
		)
	);

	
	public function pobierz_wszystkie_role() {
		$sql = "SELECT * FROM hotel_role";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_role($id) {
		$sql = "SELECT * FROM hotel_role WHERE rol_id =?";
		$params = array($id);
		
		return Model::wykonaj_zapytanie_sql($sql, $params);
	}
	
}