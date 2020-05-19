<?php
class Zamowienia {
	public $pola = array();
	public $walidacja = array();
	
	public function pobierz_wszystkie_zamowienia() {
		$sql = "SELECT * FROM hotel_zamowienie_potrawy
				JOIN hotel_uzytkownicy ON zp_uzy_id = uzy_id";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_potrawy($potrawy_ids, $ilosci) {
		$potrawy_list = implode(',', $potrawy_ids);
		$sql = "SELECT * FROM hotel_potrawy 
				JOIN hotel_menu_x_potrawa ON menxpot_pot_id = pot_id
				JOIN hotel_menu ON menxpot_men_id = men_id
				WHERE men_czy_aktualne = 1 AND
					  men_wazne_od <= ? AND men_wazne_do >= ? AND
					  pot_id IN(".$potrawy_list.")";
		
		$current_date = date('Y-m-d H:i:s');
		$parametry = array($current_date,$current_date);
		
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		global $konfiguracja;
		$podatek = $konfiguracja['tax']['kuchnia'];
		
		$przetworzone_potrawy = array();
		$iterator = 0;
		foreach ($result as $p) {
			$element = $p;
			
			$element['pot_ilosc'] = $ilosci[$iterator]; 
			$element['pot_cena'] = $p['menxpot_cena_netto'] + (($podatek/100) * $p['menxpot_cena_netto']);
			$element['pot_cena_netto_lacznie'] = $p['menxpot_cena_netto'] * $element['pot_ilosc'];
			$element['pot_cena_lacznie'] = $element['pot_cena'] * $element['pot_ilosc'];
			
			$przetworzone_potrawy[] = $element;
			$iterator++;
		}
		
		return $przetworzone_potrawy;
	}
	
	public function zamowienie_walidacja_potrawa() {
		$this->pola = array(
			'zp_potrawa',
			'zp_ilosc',
		);
		
		$this->walidacja = array(
			'zp_potrawa' => array(
				'nie_pusty'
			),
			'zp_ilosc' => array(
				'nie_pusty',
				'liczba',
				'wiekszy_od' => 0
			)
		);
	}
	
	public function walidacja_zamawiajacy() {
		$this->pola = array(
			'zp_uzy_id',
			'zp_czy_faktura',
		);
		
		$this->walidacja = array(
			'zp_uzy_id' => array(
				'nie_pusty'
			),
		);
	}
	
	public function pobierz_dostepne_potrawy() {
		$sql = "SELECT * FROM hotel_potrawy
				JOIN hotel_menu_x_potrawa ON menxpot_pot_id = pot_id
				JOIN hotel_menu ON menxpot_men_id = men_id
				WHERE men_czy_aktualne = 1 AND
					  men_wazne_od <= ? AND men_wazne_do >= ?"; //TODO sprawdzenie dostepnosci
		
		$current_date = date('Y-m-d H:i:s');
		$parametry = array($current_date,$current_date);
		
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		return $result;
	}
	
	public function pobierz_zamawiajacych() {
		$sql = "SELECT * FROM hotel_uzytkownicy 
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				JOIN hotel_role ON kuz_rol_id = rol_id
				WHERE rol_kod LIKE 'GOSC%'";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function dodaj_zamowienie($parametry) {
		$current_date = date('Y-m-d H:i:s');
		if($parametry['czy_faktura']) {
			$sql = "INSERT INTO hotel_zamowienie_potrawy (
						zp_uzy_id,
						zp_cena_calkowita_netto,
						zp_data_zamowienia,
						zp_anulowano,
						zp_podatek,
						zp_cena_brutto,
						zp_adres_ulica,
						zp_kod_pocztowy,
						zp_miejscowosc,
						zp_czy_faktura,
						zp_fak_id
					)
					VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			$p = array(
					$parametry['uzytkownik'],
					$parametry['cena']['netto'],
					$current_date,
					0,
					$parametry['cena']['podatek'],
					$parametry['cena']['brutto'],
					$parametry['adres']['ulica'],
					$parametry['adres']['kod_pocztowy'],
					$parametry['adres']['miejscowosc'],
					$parametry['czy_faktura'],
					$parametry['faktura_id']
			);
		}
		else {
			$sql = "INSERT INTO hotel_zamowienie_potrawy (
					zp_uzy_id,
					zp_cena_calkowita_netto,
					zp_data_zamowienia,
					zp_anulowano,
					zp_podatek,
					zp_cena_brutto,
					zp_adres_ulica,
					zp_kod_pocztowy,
					zp_miejscowosc,
					zp_czy_faktura
				)
				VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			$p = array(
					$parametry['uzytkownik'],
					$parametry['cena']['netto'],
					$current_date,
					0,
					$parametry['cena']['podatek'],
					$parametry['cena']['brutto'],
					$parametry['adres']['ulica'],
					$parametry['adres']['kod_pocztowy'],
					$parametry['adres']['miejscowosc'],
					$parametry['czy_faktura'],
			);
		}
		Model::wykonaj_zapytanie_sql($sql,$p);
		
		$sql = "SELECT zp_id FROM hotel_zamowienie_potrawy ORDER BY zp_id DESC LIMIT 1";
		$result = Model::wykonaj_zapytanie_sql($sql);
		$zp_id = $result[0]['zp_id'];
		
		$i = 0;
		foreach ($parametry['potrawy'] as $pot_id) {
			$sql = "INSERT INTO hotel_zamowienie_x_potrawy (zxp_pot_id, zxp_zp_id, zxp_liczba_sztuk) VALUES (?,?,?)";
			$p = array($pot_id, $zp_id, $parametry['ilosci'][$i]);
			Model::wykonaj_zapytanie_sql($sql, $p);
			
			$i++;
		}
		
		return $zp_id;
	}
	
	public function pobierz_potrawy_do_zamowienia($zamowienie_id) {
		$sql = "SELECT * FROM hotel_zamowienie_potrawy
				JOIN hotel_zamowienie_x_potrawy ON zxp_zp_id = zp_id
				JOIN hotel_potrawy ON zxp_pot_id = pot_id
				WHERE zp_id = ?";
		$parametry = array($zamowienie_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function anuluj_zamowienie($id) {
		$current_date = date('Y-m-d H:i:s');
		$sql = "UPDATE hotel_zamowienie_potrawy SET zp_anulowano = 1, zp_data_anulowania = ? WHERE zp_id = ?";
		$parametry = array($current_date, $id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_zamowienie($id) {
		$sql = "SELECT * FROM hotel_zamowienie_potrawy WHERE zp_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_uzytkownika_do_zamowienia($uzytkownik_id) {
		$sql = "SELECT uzy_imie, uzy_nazwisko, kuz_email FROM hotel_uzytkownicy 
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				WHERE uzy_id = ?";
		$parametry = array($uzytkownik_id);
		
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}