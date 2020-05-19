<?php
class ZdarzeniaMagazynowe {
	public $pola = array(
		'zdm_typ',
		'zdm_data_wystapienia',
		'zdm_ilosc'
	);
	
	public $walidacja = array(
		'zdm_typ' => array(
			'nie_pusty'
		),
		'zdm_data_wystapienia' => array(
			'nie_pusty',
		),
		'zdm_ilosc' => array(
			'nie_pusty',
			'liczba',
			'wiekszy_od' => 0
		)
	);
	
	public function pobierz_zdarzenia($state_id) {
		$sql = "SELECT * FROM hotel_zdarzenia_magazynowe WHERE zdm_stm_id = ? ORDER BY zdm_data_wystapienia DESC";
		$parametry = array($state_id);
		
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	
	public function pobierz_typy_zdarzen() {
		return array(
			'UZUPELNIENIE' => 'Uzupełnienie stanu magazynowego',
			'POBRANIE' => 'Redukcja stanu magazynowego'
		);
	}
	
	public function dodaj_zdarzenie_magazynowe($state_id, $dane) {
		$sql = "INSERT INTO hotel_zdarzenia_magazynowe (zdm_typ, zdm_data_wystapienia, zdm_ilosc, zdm_stm_id) VALUES (?,?,?,?)";
		$parametry = array(
			$dane['zdm_typ'],
			$dane['zdm_data_wystapienia'],
			$dane['zdm_ilosc'],
			$state_id
		);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		return true;
	}
	
	
	
	
	
}