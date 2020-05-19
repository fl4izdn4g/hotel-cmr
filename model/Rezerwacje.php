<?php
class Rezerwacje {
	public $pola = array(
		'rp_data_od',
		'rp_data_do',
		'rp_pok_id',
		'rp_uzy_id',
		'rp_liczba_osob',
		'rp_cena_netto',
		'rp_podatek',
		'rp_cena_brutto',
		'rp_czy_faktura',
			
		'rp_grupa_id'
	);
	
	public $walidacja = array(
		'rp_data_od' => array(
			'nie_pusty',
			'wczesniej_od' => array(
				'pole' => 'rp_data_do'
			),
			'nie_rowna_data_od' => array(
				'pole' => 'rp_data_do'
			),
			'nie_wczesniej_niz_dzisiaj'
		),
		'rp_data_do' => array(
			'nie_pusty',
			'pozniej_od' => array(
				'pole' => 'rp_data_od'
			),
			'nie_rowna_data_od' => array(
				'pole' => 'rp_data_od'
			)
		),
		'rp_pok_id' => array(
			'nie_pusty'
		),
		'rp_uzy_id' => array(
			'nie_pusty'
		),
		'rp_liczba_osob' => array(
			'nie_pusty',
			'liczba',
			'wieksze_od' => 0
		),
	
		'rp_grupa_id' => array(
			'nie_pusty'
		)
	);
	
	public function walidacja_full() {
		$this->pola = array(
			'rp_data_od',
			'rp_data_do',
			'rp_pok_id',
			'rp_uzy_id',
			'rp_liczba_osob',
			'rp_cena_netto',
			'rp_podatek',
			'rp_cena_brutto',
			'rp_czy_faktura',
				
			'rp_grupa_id'
		);
		
		$this->walidacja = array(
			'rp_data_od' => array(
				'nie_pusty',
				'wczesniej_od' => array(
					'pole' => 'rp_data_do'
				),
				'nie_rowna_data_od' => array(
					'pole' => 'rp_data_do'
				),
				'nie_wczesniej_niz_dzisiaj'
			),
			'rp_data_do' => array(
				'nie_pusty',
				'pozniej_od' => array(
					'pole' => 'rp_data_od'
				),
				'nie_rowna_data_od' => array(
					'pole' => 'rp_data_od'
				)
			),
			'rp_pok_id' => array(
				'nie_pusty'
			),
			'rp_uzy_id' => array(
				'nie_pusty'
			),
			'rp_liczba_osob' => array(
				'nie_pusty',
				'liczba',
				'wieksze_od' => 0
			),
		
			'rp_grupa_id' => array(
				'nie_pusty'
			)
		);
	}
	
	public function walidacja_data_grupa() {
		$this->pola = array(
				'rp_data_od',
				'rp_data_do',
				'rp_grupa_id'
		);
		
		$this->walidacja = array(
				'rp_data_od' => array(
						'nie_pusty',
						'wczesniej_od' => array(
								'pole' => 'rp_data_do'
						),
						'nie_rowna_data_od' => array(
								'pole' => 'rp_data_do'
						),
						'nie_wczesniej_niz_dzisiaj'
				),
				'rp_data_do' => array(
						'nie_pusty',
						'pozniej_od' => array(
								'pole' => 'rp_data_od'
						),
						'nie_rowna_data_od' => array(
								'pole' => 'rp_data_od'
						)
				),
				'rp_grupa_id' => array(
						'nie_pusty'
				)
		);
	}
	
	public function walidacja_gosc() {
		$this->pola = array(
			'rp_uzy_id',
			'rp_liczba_osob',
			'rp_czy_faktura',
		);
	
		$this->walidacja = array(
			'rp_uzy_id' => array(
				'nie_pusty'
			),
			'rp_liczba_osob' => array(
				'nie_pusty',
				'liczba',
				'wieksze_od' => 0
			),
		);
	}
	
	
	public function pobierz_wszystkie_rezerwacje() {
		$sql = "SELECT * FROM hotel_rezerwacja_pokoju 
				JOIN hotel_pokoje ON rp_pok_id = pok_id
				JOIN hotel_grupy_pokoi ON pok_grp_id = grp_id";
		
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_wszystkie_rezerwacje_dla_pokoju($pok_id) {
		return array();	
	}
	
	public function pobierz_aktywne_rezerwacje_dla_pokoju($pok_id) {
		return array();
	}
	
	public function pobierz_rezerwacje_dla_pokoju($pok_id) {
		$sql = "SELECT * FROM hotel_rezerwacja_pokoju WHERE rp_pok_id = ?";
		$paramentry = array($pok_id);
		
		return Model::wykonaj_zapytanie_sql($sql, $paramentry);
	}
	
	public function pobierz_gosci_hotelowych() {
		$sql = "SELECT * FROM hotel_goscie_hotelowi 
				JOIN hotel_uzytkownicy ON gh_uzy_id = uzy_id
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_grupy_pokoi() {
		$sql = "SELECT * FROM hotel_grupy_pokoi JOIN hotel_pokoje ON pok_grp_id = grp_id";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_pokoje_na_podstawie_rezerwacji($grupa, $od, $do) {
		$sql = "SELECT pok_id, pok_numer, pok_pietro, pok_liczba_osob, grp_nazwa, pok_zdjecie FROM hotel_pokoje
				JOIN hotel_grupy_pokoi ON pok_grp_id = grp_id
						WHERE pok_grp_id = ? AND pok_id NOT IN (SELECT rp_pok_id FROM hotel_rezerwacja_pokoju
																WHERE ((rp_data_od <= ? AND rp_data_do >= ?) OR (rp_data_od <= ? AND rp_data_do >= ?)) AND rp_anulowano = 0)
							  ";
		$parametry = array($grupa, $od, $od, $do, $do);
		
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_pokoje_na_podstawie_grupy($grupa) {
		$sql = "SELECT pok_id, pok_numer, pok_pietro FROM hotel_pokoje WHERE pok_grp_id = ?";
		$parametry = array($grupa);
		
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	
	public function pobierz_pokoje($od, $do) {
		$sql = "SELECT pok_id, pok_numer, pok_pietro, grp_cena, grp_nazwa FROM hotel_pokoje
				JOIN hotel_grupy_pokoi ON pok_grp_id = grp_id 
				WHERE pok_id NOT IN (SELECT rp_pok_id FROM hotel_rezerwacja_pokoju
									 WHERE (rp_data_od <= ? AND rp_data_do >= ?) OR (rp_data_od <= ? AND rp_data_do >= ?))";
		$parametry = array($od, $od, $do, $do);
		
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function dodaj_rezerwacje($parametry) {
		$current_date = date('Y-m-d H:i:s');
		if($parametry['czy_faktura']) {
		
			$sql = "INSERT INTO hotel_rezerwacja_pokoju (
						rp_data_od,
						rp_data_do, 
						rp_pok_id, 
						rp_data_dokonania_rezerwacji, 
						rp_liczba_osob, 
						rp_cena_netto, 
						rp_uzy_id, 
						rp_czy_faktura, 
						rp_fak_id, 
						rp_podatek, 
						rp_cena_brutto,
						rp_anulowano
					)
					VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
			$p = array(
				$parametry['od'],
				$parametry['do'],
				$parametry['pokoj'],
				$current_date,
				$parametry['liczba_osob'],
				$parametry['cena']['netto'],
				$parametry['uzytkownik'],
				$parametry['czy_faktura'],
				$parametry['faktura_id'],
				$parametry['cena']['podatek'],
				$parametry['cena']['brutto'],
				0
			);
		}
		else {
			$sql = "INSERT INTO hotel_rezerwacja_pokoju (
						rp_data_od,
						rp_data_do,
						rp_pok_id,
						rp_data_dokonania_rezerwacji,
						rp_liczba_osob,
						rp_cena_netto,
						rp_uzy_id,
						rp_czy_faktura,
						rp_podatek,
						rp_cena_brutto,
						rp_anulowano
					)
					VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			$p = array(
					$parametry['od'],
					$parametry['do'],
					$parametry['pokoj'],
					$current_date,
					$parametry['liczba_osob'],
					$parametry['cena']['netto'],
					$parametry['uzytkownik'],
					$parametry['czy_faktura'],
					$parametry['cena']['podatek'],
					$parametry['cena']['brutto'],
					0
			);
		}
		Model::wykonaj_zapytanie_sql($sql,$p);
		
		$sql = "SELECT rp_id FROM hotel_rezerwacja_pokoju ORDER BY rp_id DESC LIMIT 1";
		$result = Model::wykonaj_zapytanie_sql($sql);
		return $result[0]['rp_id'];		
	}
	
	public function pobierz_rezerwacje($id) {
		$sql = "SELECT * FROM hotel_rezerwacja_pokoju WHERE rp_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function anuluj_rezerwacje($id) {
		$current_date = date('Y-m-d H:i:s');
		$sql = "UPDATE hotel_rezerwacja_pokoju SET rp_anulowano = 1, rp_data_anulowania_rezerwacji = ? WHERE rp_id = ?";
		$parametry = array($current_date, $id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
} 