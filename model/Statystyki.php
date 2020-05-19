<?php
class Statystyki {
	
	public function pobierz_liczbe_uzytkownikow($typ){
		if($typ == 'WSZYSCY') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow GROUP BY kuz_status_konta";
		}
		else if($typ == 'ADMINISTRATORZY') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow 
					JOIN hotel_role ON kuz_rol_id = rol_id
					WHERE rol_kod LIKE 'ADMINISTRATOR%'
					GROUP BY kuz_status_konta";
		}
		else if($typ == 'ADMINISTRATORZY_HOTELU') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow
					JOIN hotel_role ON kuz_rol_id = rol_id
					WHERE rol_kod = 'ADMINISTRATOR_HOTELU'
					GROUP BY kuz_status_konta";
		}
		else if($typ == 'ADMINISTRATORZY_RESTAURACJI') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow
					JOIN hotel_role ON kuz_rol_id = rol_id
					WHERE rol_kod = 'ADMINISTRATOR_RESTAURACJI'
					GROUP BY kuz_status_konta";
		}
		else if($typ == 'GOSCIE') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow
					JOIN hotel_role ON kuz_rol_id = rol_id
					WHERE rol_kod LIKE 'GOSC%'
					GROUP BY kuz_status_konta";
		}
		else if($typ == 'GOSCIE_HOTELU') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow
					JOIN hotel_role ON kuz_rol_id = rol_id
					WHERE rol_kod = 'GOSC_HOTELU'
					GROUP BY kuz_status_konta";
		}
		else if($typ == 'GOSCIE_RESTAURACJI') {
			$sql = "SELECT COUNT(*) as ile, kuz_status_konta FROM hotel_konta_uzytkownikow
					JOIN hotel_role ON kuz_rol_id = rol_id
					WHERE rol_kod LIKE 'GOSC_RESTAURACJI'
					GROUP BY kuz_status_konta";
		}
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_liczbe_rezerwacji_pokoi_na_dzien($date) {
		$sql = "SELECT COUNT(1) FROM hotel_rezerwacje_pokoi WHERE rp_data_dokonania_rezerwacji";
	}
	
	public function zestawienie_roczne_pokoje_do_dzisiaj() {
		$sql = "SELECT COUNT(*) AS ile, MONTH(rp_data_dokonania_rezerwacji) AS miesiac FROM hotel_rezerwacja_pokoju
				WHERE rp_data_dokonania_rezerwacji <= ? AND YEAR(rp_data_dokonania_rezerwacji) = ? GROUP BY MONTH(rp_data_dokonania_rezerwacji)";
		$parametry = array(date('Y-m-d'),date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zestawienie_roczne_pokoje_do_dzisiaj_dobre() {
		$sql = "SELECT COUNT(*) AS ile, MONTH(rp_data_dokonania_rezerwacji) AS miesiac FROM hotel_rezerwacja_pokoju
				WHERE rp_data_dokonania_rezerwacji <= ? AND rp_anulowano = 0 AND YEAR(rp_data_dokonania_rezerwacji) = ? GROUP BY MONTH(rp_data_dokonania_rezerwacji)";
		$parametry = array(date('Y-m-d'),date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zestawienie_roczne_pokoje_do_dzisiaj_anulowane() {
		$sql = "SELECT COUNT(*) AS ile, MONTH(rp_data_dokonania_rezerwacji) AS miesiac FROM hotel_rezerwacja_pokoju
				WHERE rp_data_dokonania_rezerwacji <= ? AND rp_anulowano = 1 AND YEAR(rp_data_dokonania_rezerwacji) = ? GROUP BY MONTH(rp_data_dokonania_rezerwacji)";
		$parametry = array(date('Y-m-d'),date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	
	public function zestawienie_miesieczne_pokoje() {
		$sql = "SELECT COUNT(*) AS ile, rp_anulowano AS anulowano FROM hotel_rezerwacja_pokoju
				WHERE rp_data_dokonania_rezerwacji <= ? AND MONTH(rp_data_dokonania_rezerwacji)= ? AND YEAR(rp_data_dokonania_rezerwacji) = ? GROUP BY rp_anulowano ORDER BY rp_anulowano";
		$parametry = array(date('Y-m-d'),date('m'), date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zestawienie_roczne_stoliki_do_dzisiaj() {
		$sql = "SELECT COUNT(*) AS ile, MONTH(rs_data_dokonania_rezerwacji) AS miesiac FROM hotel_rezerwacja_stolika
				WHERE rs_data_dokonania_rezerwacji <= ? AND YEAR(rs_data_dokonania_rezerwacji) = ? GROUP BY MONTH(rs_data_dokonania_rezerwacji)";
		$parametry = array(date('Y-m-d'),date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zestawienie_miesieczne_stoliki() {
		$sql = "SELECT COUNT(*) AS ile, rs_anulowano AS anulowano FROM hotel_rezerwacja_stolika
				WHERE rs_data_dokonania_rezerwacji <= ? AND MONTH(rs_data_dokonania_rezerwacji) = ? AND YEAR(rs_data_dokonania_rezerwacji) = ? GROUP BY rs_anulowano ORDER BY rs_anulowano";
		$parametry = array(date('Y-m-d'),date('m'), date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zestawienie_roczne_potrawy_do_dzisiaj() {
		$sql = "SELECT COUNT(*) AS ile, MONTH(zp_data_zamowienia) AS miesiac FROM hotel_zamowienie_potrawy
				WHERE zp_data_zamowienia <= ? AND YEAR(zp_data_zamowienia) = ? GROUP BY MONTH(zp_data_zamowienia)";
		$parametry = array(date('Y-m-d'),date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zestawienie_miesieczne_potrawy() {
		$sql = "SELECT COUNT(*) AS ile, zp_anulowano AS anulowano FROM hotel_zamowienie_potrawy
				WHERE zp_data_zamowienia <= ? AND MONTH(zp_data_zamowienia) = ? AND YEAR(zp_data_zamowienia) = ? GROUP BY zp_anulowano ORDER BY zp_anulowano";
		$parametry = array(date('Y-m-d'),date('m'),date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function przychod_potrawy($miesiac) {
		$sql = "SELECT SUM(zp_cena_calkowita_netto) AS ile FROM hotel_zamowienie_potrawy
				WHERE MONTH(zp_data_zamowienia) = ? AND YEAR(zp_data_zamowienia) = ? AND zp_anulowano = 0";
		$parametry = array($miesiac,date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function przychod_stoliki($miesiac) {
		$sql = "SELECT SUM(rs_cena_netto) AS ile FROM hotel_rezerwacja_stolika
				WHERE MONTH(rs_data_dokonania_rezerwacji) = ? AND YEAR(rs_data_dokonania_rezerwacji) = ? AND rs_anulowano = 0";
		$parametry = array($miesiac,date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function przychod_pokoje($miesiac) {
		$sql = "SELECT SUM(rp_cena_netto) AS ile FROM hotel_rezerwacja_pokoju
				WHERE MONTH(rp_data_dokonania_rezerwacji) = ? AND YEAR(rp_data_dokonania_rezerwacji) = ? AND rp_anulowano = 0";
		$parametry = array($miesiac,date('Y'));
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function ostatnie_rezerwacje_pokoi() {
		$sql = "SELECT * FROM hotel_rezerwacja_pokoju
				ORDER BY rp_data_dokonania_rezerwacji DESC LIMIT 3";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function ostatnie_rezerwacje_stolika() {
		$sql = "SELECT * FROM hotel_rezerwacja_stolika
				ORDER BY rs_data_dokonania_rezerwacji DESC LIMIT 3";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function ostatnie_zamowienia() {
		$sql = "SELECT * FROM hotel_zamowienie_potrawy
				ORDER BY zp_data_zamowienia DESC LIMIT 3";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function liczba_pokoi() {
		$sql = "SELECT COUNT(*) AS ile FROM hotel_pokoje";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function liczba_gosci() {
		$sql = "SELECT COUNT(*) AS ile FROM hotel_goscie_hotelowi";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function liczba_rezerwacji() {
		$sql = "SELECT COUNT(*) AS ile FROM hotel_rezerwacja_pokoju";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function najpopularniejsze_grupy_pokoi() {
		$sql = "SELECT COUNT(*) as ile, grp_nazwa FROM hotel_rezerwacja_pokoju
				JOIN hotel_pokoje ON rp_pok_id = pok_id
				JOIN hotel_grupy_pokoi ON pok_grp_id = grp_id
				GROUP BY grp_nazwa
				ORDER BY ile DESC";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function najpopularniejsze_sale() {
		$sql = "SELECT COUNT(*) as ile, sar_nazwa FROM hotel_rezerwacja_stolika
				JOIN hotel_stoliki ON rs_sto_id = sto_id
				JOIN hotel_sale_restauracyjne ON sto_sar_id = sar_id
				GROUP BY sar_nazwa
				ORDER BY ile DESC";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function najpopularniejsze_potrawy() {
		$sql = "SELECT SUM(zxp_liczba_sztuk) as ile, pot_nazwa FROM hotel_zamowienie_x_potrawy
				JOIN hotel_potrawy ON zxp_pot_id = pot_id
				GROUP BY pot_nazwa
				ORDER BY ile DESC";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function najpopularniejsze_pokoje() {
		$sql = "SELECT COUNT(*) as ile, grp_nazwa, pok_numer FROM hotel_rezerwacja_pokoju
				JOIN hotel_pokoje ON rp_pok_id = pok_id
				JOIN hotel_grupy_pokoi ON pok_grp_id = grp_id
				GROUP BY grp_nazwa, pok_numer
				ORDER BY ile DESC";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function liczba_stolikow() {
		$sql = "SELECT COUNT(*) as ile FROM hotel_stoliki";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function liczba_potraw() {
		$sql = "SELECT COUNT(*) as ile FROM hotel_potrawy";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function liczba_zamowien() {
		$sql = "SELECT COUNT(*) as ile FROM hotel_zamowienie_potrawy";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function produkty_na_wyczerpaniu() {
		$sql = "SELECT * FROM hotel.hotel_stan_magazynu 
				JOIN hotel_produkty ON stm_prod_id = prod_id";
		
		$results = Model::wykonaj_zapytanie_sql($sql);
		$przetworzone = array();
		foreach ($results as $r) {
			if($r['stm_aktualny_stan'] <= $r['stm_minimalny_dopuszczalny_stan']) {
				$przetworzone[] = $r;
			}
			else if($r['stm_aktualny_stan'] <= ($r['stm_minimalny_dopuszczalny_stan'] + 0.05*$r['stm_minimalny_dopuszczalny_stan'])) {
				$przetworzone[] = $r;
			}
		}
		
		return $przetworzone;
	}
}