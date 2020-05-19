<?php
class Administratorzy {
	public $pola = array(
		'adm_stanowisko',
		'adm_zdjecie'
	);
	
	public $walidacja = array(
		'adm_stanowisko' => array(
			'nie_pusty'
		),
		'adm_zdjecie' => array(
			'nie_pusty'
		)
	);
	
	public function pobierz_administratorow() {
		
		$sql = "SELECT * FROM hotel_uzytkownicy 
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				JOIN hotel_role ON kuz_rol_id = rol_id
				LEFT JOIN hotel_administratorzy ON adm_uzy_id = uzy_id
				WHERE rol_kod LIKE 'ADMINISTRATOR%'";
		
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function dodaj_adminstratora($administrator) {
		if(empty($administrator['stanowisko']) || empty($administrator['zdjecie']) || empty($administrator['uzytkownik'])) {
			return false;
		}
		$sql = "INSERT INTO hotel_administratorzy (adm_stanowisko, adm_zdjecie, adm_uzy_id)
				VALUES (?,?,?)";
		$params = array($administrator['stanowisko'], $administrator['zdjecie'], $administrator['uzytkownik']);
		Model::wykonaj_zapytanie_sql($sql, $params);
	}
}