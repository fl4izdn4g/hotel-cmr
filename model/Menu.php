<?php
class Menu {
	public $pola = array(
		'men_nazwa',
		'men_wazne_od',
		'men_wazne_do',
		'men_czy_aktualne'
	);
	
	public $walidacja = array(
		'men_nazwa' => array(
			'nie_pusty'
		),
		'men_wazne_od' => array(
			'nie_pusty',
			'data',
			'wczesniej_od' => array(
				'pole' => 'men_wazne_do'
			)
		),
		'men_wazne_do' => array(
			'nie_pusty',
			'data',
			'pozniej_od' => array(
				'pole' => 'men_wazne_od'
			)	
		),
	);
	
	
	public function pobierz_wszystkie_menu() {
		$sql = "SELECT * FROM hotel_menu";
		return Model::wykonaj_zapytanie_sql($sql);
	}
}