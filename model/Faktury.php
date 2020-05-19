<?php
class Faktury {
	
	
	public function dodaj_fakture($parametry) {
		$current_date = date('Y-m-d H:i:s');
		$current_date_flat = date('Ymd');
		
		$sql = "SELECT fak_numer_identyfikacyjny FROM hotel_faktury ORDER BY fak_id DESC LIMIT 1";
		$result = Model::wykonaj_zapytanie_sql($sql);
		if(!empty($result)) {
			$numer_max = $result[0]['fak_numer_identyfikacyjny'];
			$max_id = (int)substr($numer_max, 9);
			$max_id++;
		}
		else {
			$max_id = 1;
		}
		
		$next_id = str_pad($max_id, 4, '0', STR_PAD_LEFT);
		$next_numer = $current_date_flat.$next_id;
		
		
		$sql = "INSERT INTO hotel_faktury (
					fak_numer_identyfikacyjny,
					fak_data_wystawienia,
					fak_rodzaj,
					fak_odbiorca_imie,
					fak_odbiorca_nazwisko, 
					fak_odbiorca_numer_identyfikacyjny, 
					fak_odbiorca_adres, 
					fak_odbiorca_kod_pocztowy, 
					fak_odbiorca_miejscowosc, 
					fak_data_zaplaty, 
					fak_naleznosc_ogolem_netto, 
					fak_naleznosc_ogolem_brutto			
				) 
				VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
	
		$zapytanie_p = array(
			$next_numer,
			$current_date,
			$parametry['rodzaj'],
			$parametry['odbiorca']['imie'],
			$parametry['odbiorca']['nazwisko'],
			$parametry['odbiorca']['numer_id'],
			$parametry['odbiorca']['adres']['ulica'],
			$parametry['odbiorca']['adres']['kod_pocztowy'],
			$parametry['odbiorca']['adres']['miejscowosc'],
			$parametry['data_zaplaty'],
			$parametry['naleznosc_netto'],
			$parametry['naleznosc_brutto']
		);
		
		Model::wykonaj_zapytanie_sql($sql, $zapytanie_p);
		
		$sql = "SELECT fak_id FROM hotel_faktury ORDER BY fak_id DESC LIMIT 1";
		$result = Model::wykonaj_zapytanie_sql($sql);
		$faktura_id = $result[0]['fak_id'];
		
		foreach ($parametry['pozycje'] as $pozycja) {
			$sql = "INSERT INTO hotel_pozycje_faktury (
						poz_nazwa, 
						poz_liczba, 
						poz_cena_netto, 
						poz_podatek, 
						poz_cena_brutto, 
						poz_kwota_podatku, 
						poz_fak_id
					) 
					VALUES (?,?,?,?,?,?,?)";
			
			$p = array(
				$pozycja['nazwa'],
				$pozycja['liczba'],
				$pozycja['netto'],
				$pozycja['podatek'],
				$pozycja['brutto'],
				$pozycja['kwota_vat'],
				$faktura_id
			);
			
			Model::wykonaj_zapytanie_sql($sql, $p);
		}
		
		return $faktura_id;
	}
	
	
	public function pobierz_typy_faktur() {
		return array(
			'PROFORMA' => 'Faktura proforma',
		);
	}
	
	public function pobierz_wszystkie_faktury() {
		$sql = "SELECT * FROM hotel_faktury";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public  function pobierz_fakture($id) {
		$sql = "SELECT * FROM hotel_faktury WHERE fak_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public  function pobierz_pozycje_faktury($id) {
		$sql = "SELECT * FROM hotel_pozycje_faktury WHERE poz_fak_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_identyfikator_zamowienia_na_podstawie_faktury($id) {
		$sql = "SELECT * FROM hotel_rezerwacja_pokoju WHERE rp_fak_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			return 'POKOJ_'.str_pad($result[0]['rp_id'], 5, '0', STR_PAD_LEFT);
		}

		$sql = "SELECT * FROM hotel_rezerwacja_stolika WHERE rs_fak_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			return 'STOLIK_'.str_pad($result[0]['rs_id'], 5, '0', STR_PAD_LEFT);
		}
		
		$sql = "SELECT * FROM hotel_zamowienie_potrawy WHERE zp_fak_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			return 'POTRAWA_'.str_pad($result[0]['zp_id'], 5, '0', STR_PAD_LEFT);
		}
		
	}
	
	public function anuluj_fakture($faktura_id) {
		$sql = "UPDATE hotel_faktury SET fak_czy_usunieto = 1, fak_data_usuniecia = ? WHERE fak_id = ?";
		$parametry = array($current_date, $faktura_id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}