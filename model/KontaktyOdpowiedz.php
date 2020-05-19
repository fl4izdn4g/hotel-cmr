<?php
class KontaktyOdpowiedz {
	public $pola = array(
		'kodp_tytul',
		'kodp_tresc'
	);
	
	public $walidacja = array(
			'kodp_tytul' => array(
				'nie_pusty'
			),
			'kodp_tresc' => array(
				'nie_pusty'
			)
	);
	
	
	public function pobierz_odpowiedz($id) {
		$sql = "SELECT * FROM hotel_kontakt_odpowiedz
				JOIN hotel_kontakty ON kodp_kon_id = kon_id
				WHERE kodp_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}