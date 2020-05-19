<?php
class Stoliki {
	public $pola = array(
		'sto_numer',
		'sto_liczba_miejsc',
		'sto_polozenie',
		'sto_sar_id',
		'sto_cena_netto'
	);
	
	public $walidacja = array(
		'sto_numer' => array(
			'nie_pusty',
			'liczba'
		),
		'sto_liczba_miejsc' => array(
			'nie_pusty',
			'liczba'
		),
		'sto_polozenie' => array(
			'nie_pusty',
		),
		'sto_sar_id' => array(
			'nie_pusty'
		),
		'sto_cena_netto' => array(
			'nie_pusty',
			'liczba'
		)
	);
	
	
	public function pobierz_wszystkie_stoliki() {
		$sql = "SELECT * FROM hotel_stoliki";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_stolik($stolik_id) {
		$sql = "SELECT * FROM hotel_stoliki WHERE sto_id = ?";
		$parametry = array($stolik_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_stolik_rezerwacje($stolik_id) {
		$sql = "SELECT * FROM hotel_stoliki
				JOIN hotel_sale_restauracyjne ON sto_sar_id = sar_id
				WHERE sto_id = ?";
		$parametry = array($stolik_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}