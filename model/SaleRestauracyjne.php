<?php
class SaleRestauracyjne {
	public $pola = array(
		'sar_nazwa',
		'sar_opis',
		'sar_zdjecie',
		'sar_dla_palacych'
	);
	
	public $walidacja = array(
		'sar_nazwa' => array(
			'nie_pusty'
		),
		'sar_opis' => array(
			'nie_pusty'
		),
		'sar_zdjecie' => array(
			'nie_pusty'
		),
	);
	
	public function pobierz_wszystkie_sale() {
		$sql = "SELECT * FROM hotel_sale_restauracyjne";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_sale($id) {
		$sql = "SELECT * FROM hotel_sale_restauracyjne WHERE sar_id = ?";
		$parametry = array($id);
		
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}