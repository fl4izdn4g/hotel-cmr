<?php
class PotrawyMenu {
	public $pola = array(
		'menxpot_pot_id',
		'menxpot_cena_netto'
	);
	
	public $walidacja = array(
		'menxpot_pot_id' => array(
			'nie_pusty'
		),
		'menxpot_cena_netto' => array(
			'nie_pusty',
			'liczba'
		)
	);
	
	public function pobierz_potrawy_dla_menu($menu_id) {
		$sql = "SELECT * FROM hotel_menu_x_potrawa JOIN hotel_potrawy ON pot_id = menxpot_pot_id WHERE menxpot_men_id = ?";
		$parametry = array($menu_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
}