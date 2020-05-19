<?php
class WyposazenieGrupyPokoi {
	public $pola = array(
		'gxw_wyp_id',
		'gxw_ilosc_wyposazenia'
	);
	
	public $walidacja = array(
		'gxw_wyp_id' => array(
			'nie_pusty'
		),
		'gxw_ilosc_wyposazenia' => array(
			'nie_pusty',
			'liczba'
		)
	);
	
	public function pobierz_wyposazenie_grupy_pokoi($group_id) {
		$sql = "SELECT * FROM hotel_grupa_pokoi_x_wyposazenie JOIN hotel_wyposazenie ON wyp_id = gxw_wyp_id WHERE gxw_grp_id = ?";
		$parametry = array($group_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}