<?php
class Produkty {
	public $pola = array(
		'prod_nazwa',
		'prod_opis',
		'prod_ikona',
		'prod_cena_jednostkowa_netto',
		'prod_jednostka',
		'prod_grpp_id'
	);
	
	public $walidacja = array(
		'prod_nazwa' => array(
			'nie_pusty'
		),
		'prod_opis' => array(
			'nie_pusty'
		),
		'prod_ikona' => array(
			'nie_pusty'
		),
		'prod_cena_jednostkowa_netto' => array(
			'nie_pusty',
			'liczba'
		),
		'prod_jednostka' => array(
			'nie_pusty'
		),
		'prod_grpp_id' => array(
			'nie_pusty'
		)
	);
	
	public function pobierz_wszystkie_produkty() {
		$sql = "SELECT * FROM hotel_produkty";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_wszystkie_jednostki() {
		return array(
			'SZTUKA' => 'sztuki',
			'KG' => 'kilogramy',
			'GR' => 'gramy',
			'L' => 'litry',
			'OPAKOWANIA' => 'opakowania',
			'ML' => 'mililitr',
		);
	}
}