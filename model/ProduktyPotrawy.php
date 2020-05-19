<?php
class ProduktyPotrawy {
	public $pola = array(
		'potxprod_prod_id',
		'potxprod_wykorzystywana_ilosc'
	);
	
	public $walidacja = array(
		'potxprod_prod_id' => array(
			'nie_pusty'
		),
		'potxprod_wykorzystywana_ilosc' => array(
			'nie_pusty',
			'liczba'
		)
	);
	
	
	public function pobierz_produkty_potrawy($meal_id) {
		$sql = "SELECT * FROM hotel_potrawa_x_produkt JOIN hotel_produkty ON prod_id = potxprod_prod_id WHERE potxprod_pot_id = ?";
		$parametry = array($meal_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}