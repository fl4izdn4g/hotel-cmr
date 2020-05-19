<?php
class RezerwacjeStolikow {
	public $pola = array();
	public $walidacja = array();
	
	public function pobierz_wszystkie_rezerwacje() {
		$sql = "SELECT * FROM hotel_rezerwacja_stolika
				JOIN hotel_stoliki ON rs_sto_id = sto_id
				JOIN hotel_sale_restauracyjne ON sto_sar_id = sar_id
				JOIN hotel_uzytkownicy ON rs_uzy_id = uzy_id";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_sale_restauracyjne() {
		$sql = "SELECT * FROM hotel_sale_restauracyjne";
		return Model::wykonaj_zapytanie_sql($sql);
	}

	public function walidacja_data_sala() {
		$this->pola = array(
			'rs_data_rezerwacji',
			'rs_sala_id'
		);
		
		$this->walidacja = array(
			'rs_data_rezerwacji' => array(
				'nie_pusty',
				'nie_wczesniej_niz_dzisiaj'
			),
			'rs_sala_id' => array(
				'nie_pusty'
			)
		);
	}
	
	public function walidacja_uzytkownikow() {
		$this->pola = array(
			'rs_uzy_id',
			'rs_czy_faktura',
			'rs_liczba_osob'
		);
		
		$this->walidacja = array(
			'rs_uzy_id' => array(
				'nie_pusty',
			),
			'rs_liczba_osob' => array(
				'nie_pusty',
				'liczba',
				'wiekszy_od' => 0
			)
		);
	}
	
	public function pobierz_stoliki_na_podstawie_rezerwacji($sala, $data) {
		$sql = "SELECT * FROM hotel_stoliki
				JOIN hotel_sale_restauracyjne ON sto_sar_id = sar_id
						WHERE sto_sar_id = ? AND sto_id NOT IN (SELECT rs_sto_id FROM hotel_rezerwacja_stolika
																WHERE rs_data_rezerwacji = ? AND rs_anulowano = 0)
				";
		$parametry = array($sala, $data);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_uzytkownikow() {
		$sql = "SELECT * FROM hotel_uzytkownicy
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				JOIN hotel_role ON kuz_rol_id = rol_id
				WHERE rol_kod LIKE 'GOSC%' AND kuz_status_konta <> 'USUNIETE'";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function dodaj_rezerwacje($parametry) {
		$current_date = date('Y-m-d H:i:s');
		
		if($parametry['czy_faktura']) { 
			$sql = "INSERT INTO hotel_rezerwacja_stolika (
						rs_data_rezerwacji,
						rs_sto_id,
						rs_data_dokonania_rezerwacji,
						rs_liczba_osob,
						rs_cena_netto,
						rs_uzy_id,
						rs_czy_faktura,
						rs_fak_id,
						rs_podatek,
						rs_cena_brutto
					)
					VALUES (?,?,?,?,?,?,?,?,?,?)";
			$p = array(
					$parametry['data'],
					$parametry['stolik'],
					$current_date,
					$parametry['liczba_osob'],
					$parametry['cena']['netto'],
					$parametry['uzytkownik'],
					$parametry['czy_faktura'],
					$parametry['faktura_id'],
					$parametry['cena']['podatek'],
					$parametry['cena']['brutto'],
			);
		}
		else {
			$sql = "INSERT INTO hotel_rezerwacja_pokoju (
						rs_data_rezerwacji,
						rs_sto_id,
						rs_data_dokonania_rezerwacji,
						rs_liczba_osob,
						rs_cena_netto,
						rs_uzy_id,
						rs_czy_faktura,
						rs_podatek,
						rs_cena_brutto
					)
					VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			$p = array(
					$parametry['data'],
					$parametry['stolik'],
					$current_date,
					$parametry['liczba_osob'],
					$parametry['cena']['netto'],
					$parametry['uzytkownik'],
					$parametry['czy_faktura'],
					$parametry['cena']['podatek'],
					$parametry['cena']['brutto'],
			);
		}
		Model::wykonaj_zapytanie_sql($sql,$p);
		
		$sql = "SELECT rs_id FROM hotel_rezerwacja_stolika ORDER BY rs_id DESC LIMIT 1";
		$result = Model::wykonaj_zapytanie_sql($sql);
		return $result[0]['rs_id'];
	}
	
	public function pobierz_rezerwacje($rezerwacja_id) {
		$sql = "SELECT * FROM hotel_rezerwacja_stolika WHERE rs_id = ?";
		$parametry = array($rezerwacja_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function anuluj_rezerwacje($id) {
		$current_date = date('Y-m-d H:i:s');
		$sql = "UPDATE hotel_rezerwacja_stolika SET rs_anulowano = 1, rs_data_anulowania = ? WHERE rs_id = ?";
		$parametry = array($current_date, $id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
}