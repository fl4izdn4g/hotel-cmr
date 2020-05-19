<?php
class ZamowieniaController {
	public function lista() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Lista zamówień potraw');
	
		$wszystkie_zamowienia = $this->model->pobierz_wszystkie_zamowienia();
		$przetworzone_zamowienia = array();
		foreach ($wszystkie_zamowienia as $z) {
			$element = $z;
			if(!empty($z['zp_anulowano'])) {
				$element['zp_anulowano'] = 'tak';
			}
			else {
				$element['zp_anulowano'] = 'nie';
			}
				
			$element['zp_zamawiajacy'] = $z['uzy_imie'].' '.$z['uzy_nazwisko'];
			
			$element['zp_adres'] = $z['zp_adres_ulica'].'<br/>'.$z['zp_kod_pocztowy'].'<br/>'.$z['zp_miejscowosc'];
			
			$przetworzone_zamowienia[] = $element;
		}
	
		View::ustaw('zamowienia', $przetworzone_zamowienia);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Dodaj potrawy do zamówienia');
		if(isset($_GET['z'])) {
			$zamowienie = $_GET['z'];
			$this->ustaw_parametry_zamowienia($zamowienie);
						
			$potrawy_info = explode(',', base64_decode($zamowienie['potrawy']));
			
			if(!empty($potrawy_info)) {
				$potrawy_ids = array();
				$ilosci = array();
				foreach ($potrawy_info as $i) {
					$info = explode('-', $i);
					$potrawy_ids[] = $info[0];
					$ilosci[] = $info[1];
				}
				
				$potrawy = $this->model->pobierz_potrawy($potrawy_ids, $ilosci);
				View::ustaw('potrawy', $potrawy);
				
				$cena_lacznie = 0;
				foreach ($potrawy as $p) {
					$cena_lacznie += $p['pot_cena_lacznie'];
				}
				View::ustaw('cena_lacznie', $cena_lacznie);
			}
		}
	}
	
	private function ustaw_parametry_zamowienia($z) {
		foreach ($z as $klucz => $element) {
			View::ustaw('z['.$klucz.']', $element);
		}
	}
	
	public function potrawa() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Wybierz potrawę do zamówienia');
		
		if(isset($_GET['z'])) {
			$zamowienie = $_GET['z'];
			$this->ustaw_parametry_zamowienia($zamowienie);
		}
		
		$dostepne_potrawy = $this->model->pobierz_dostepne_potrawy();
		
		$przetworzone_potrawy = array();
		foreach ($dostepne_potrawy as $p) {
			$przetworzone_potrawy[$p['pot_id']] = $p['pot_nazwa'];
		}
		View::ustaw('potrawy', $przetworzone_potrawy);
		
		$this->model->zamowienie_walidacja_potrawa();
			
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
			
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(isset($_POST['zpFormularz']) && $_POST['zpFormularz'] == 'dodaj') {
			
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				if(!empty($_GET['z'])) {
					$zamowienie = $_GET['z'];
					
					$potrawy = base64_decode($zamowienie['potrawy']);
					$potrawy_array = explode(',', $potrawy);
					$potrawy_array[] = $_POST['zp_potrawa'].'-'.$_POST['zp_ilosc'];
					$potrawy = implode(',', $potrawy_array);
					$potrawy = base64_encode($potrawy);
					
					$link = array(
							'controller' => 'Zamowienia',
							'action' => 'dodaj',
							'z[potrawy]' => $potrawy
					);
					
					Router::przekierowanie($link);
				}
				else {
					$link = array(
						'controller' => 'Zamowienia',
						'action' => 'dodaj',
						'z[potrawy]' => base64_encode($_POST['zp_potrawa'].'-'.$_POST['zp_ilosc'])
					);
					Router::przekierowanie($link);
				}
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function osoba() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Wybierz zamawiajacego');
	
		if(!empty($_GET['z'])) {
			$zamowienie = $_GET['z'];
			$this->ustaw_parametry_zamowienia($zamowienie);
				
			$zamawiajacy = $this->model->pobierz_zamawiajacych();
			$przetworzeni_zamawiajacy = array();
			foreach ($zamawiajacy as $z) {
				$label = $z['uzy_imie'].' '.$z['uzy_nazwisko'].' ('.$z['kuz_email'].')';
	
				$przetworzeni_zamawiajacy[$z['uzy_id']] = $label;
			}
			View::ustaw('zamawiajacy', $przetworzeni_zamawiajacy);
				
			$this->model->walidacja_zamawiajacy();
				
			$parametry_z_formularza = $this->model->pola;
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
			View::ustaw('bledy_walidacji', $bledy_walidacji);
				
			if(!empty($_POST['zpFormularz']) && $_POST['zpFormularz'] == 'dodaj') {
	
				if(isset($_POST['zp_czy_faktura']) && $_POST['zp_czy_faktura'] == 'on') {
					$_POST['zp_czy_faktura'] = 1;
				}
				else {
					$_POST['zp_czy_faktura'] = 0;
				}
				
				$reguly_walidacji = $this->model->walidacja;
				$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
				$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				if(!$czy_sa_bledy) {
					$z = $_GET['z'];
					$redirect_link = array(
							'controller' => 'Zamowienia',
							'z[potrawy]' => $z['potrawy'],
							'z[osoba]' => $_POST['zp_uzy_id'],
							'z[faktura]' => $_POST['zp_czy_faktura'],
					);
						
					//czy wybrany uzytkownik posiada adres
					$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ?";
					$parametry = array($_POST['zp_uzy_id']);
					$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
					if(!empty($result)) {
						$redirect_link['action'] = 'osoba_adres_wybierz';
					}
					else {
						$redirect_link['action'] = 'osoba_adres_dodaj';
					}
						
					Router::przekierowanie($redirect_link);
				}
				else {
					View::ustaw('bledy_walidacji', $bledy_walidacji);
				}
			}
				
		}
		else {
			Router::przekierowanie(array('controller' => 'Zamowienia'));
		}
	}
	
	public function osoba_adres_dodaj() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Dodaj adres dostawy');
	
		if(!empty($_GET['z'])) {
			$zamowienie = $_GET['z'];
			$this->ustaw_parametry_zamowienia($zamowienie);
				
			$adres_model = Model::zaladuj_model('Adresy');
				
			$wojewodztwa = $adres_model->pobierz_wojewodztwa();
			View::ustaw('wojewodztwa', $wojewodztwa);
	
			$parametry_z_formularza = $adres_model->pola;
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
	
			$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
			View::ustaw('bledy_walidacji', $bledy_walidacji);
	
			if(!empty($_POST['zpFormularz']) && $_POST['zpFormularz'] == 'dodaj') {
					
				$reguly_walidacji = $adres_model->walidacja;
				$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
				$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				if(!$czy_sa_bledy) {
					$z = $_GET['z'];
						
					//dodaj adres
					$adres_id = $adres_model->dodaj_adres($_POST);
					//powiaz adres z uzytkownikiem
					$adres_model->dodaj_domyslny_adres($adres_id, $z['osoba']);
						
					$redirect_link = array(
							'controller' => 'Zamowienia',
							'action' => 'podsumowanie',
							'z[potrawy]' => $z['potrawy'],
							'z[osoba]' => $z['osoba'],
							'z[faktura]' => $z['faktura'],
							'z[adres]' => $adres_id
					);
						
					Router::przekierowanie($redirect_link);
				}
				else {
					View::ustaw('bledy_walidacji', $bledy_walidacji);
				}
			}
		}
		else {
			Router::przekierowanie(array('controller' => 'Zamowienia'));
		}
	}
	
	public function osoba_adres_wybierz() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Wybierz adres dostawy');
	
		if(!empty($_GET['z'])) {
			$zamowienia = $_GET['z'];
			$this->ustaw_parametry_zamowienia($zamowienia);
				
			$adres_model = Model::zaladuj_model('Adresy');
				
			$adresy_uzytkownika = $adres_model->pobierz_adresy_uzytkownika($zamowienia['osoba']);
			View::ustaw('adresy', $adresy_uzytkownika);
		}
		else {
			Router::przekierowanie(array('controller' => 'Zamowienia'));
		}
	}
	
	public function podsumowanie() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Podsumowanie zamówienia potrawy');
	
		if(!empty($_GET['z'])) {
			$zamowienie = $_GET['z'];
			$this->ustaw_parametry_zamowienia($zamowienie);
				
			$potrawy_info = explode(',', base64_decode($zamowienie['potrawy']));
				
			if(!empty($potrawy_info)) {
				$potrawy_ids = array();
				$ilosci = array();
				foreach ($potrawy_info as $i) {
					$info = explode('-', $i);
					$potrawy_ids[] = $info[0];
					$ilosci[] = $info[1];
				}
			
				$potrawy = $this->model->pobierz_potrawy($potrawy_ids, $ilosci);
				View::ustaw('potrawy', $potrawy);
			
				$cena_lacznie_brutto = 0;
				$cena_lacznie_netto = 0;
				foreach ($potrawy as $p) {
					$cena_lacznie_brutto += $p['pot_cena_lacznie'];
					$cena_lacznie_netto += $p['pot_cena_netto_lacznie'];
				}
				View::ustaw('cena_lacznie', $cena_lacznie_brutto);
			}
			
			global $konfiguracja;
			$podatek = $konfiguracja['tax']['kuchnia'];
			
			$adres_model = Model::zaladuj_model('Adresy');
			$adres = $adres_model->pobierz_adres($zamowienie['adres']);
			$adres = $adres[0];
			View::ustaw('adres', $adres);
				
			$gosc_model = Model::zaladuj_model('Uzytkownicy');
			$gosc = $gosc_model->pobierz_uzytkownika_do_zamowienia($zamowienie['osoba']);
			$gosc = $gosc[0];
			View::ustaw('osoba', $gosc);
			
			if(!empty($zamowienie['faktura'])) {
				View::ustaw('faktura', 'Tak');
			}
			else {
				View::ustaw('faktura', 'Nie');
			}
			
			if(isset($_POST['zpFormularz']) && $_POST['zpFormularz'] == 'dodaj') {
				$netto_lacznie = 0;
				$brutto_lacznie = 0;
				$faktura_id = 0;
				if(!empty($zamowienie['faktura'])) {
					//najpierw faktura aby mieć
					$faktura_model = Model::zaladuj_model('Faktury');
					$pozycje_na_fakturze = array();
					
					foreach ($potrawy as $p) {
						$netto_lacznie += $p['pot_cena_netto_lacznie'];
						$brutto_lacznie += $p['pot_cena_lacznie'];
						
						$pozycje_na_fakturze[] = array(
								'nazwa' => $p['pot_nazwa'],
								'liczba' => $p['pot_ilosc'],
								'netto' => $p['pot_cena_netto_lacznie'],
								'brutto' => $p['pot_cena_lacznie'],
								'podatek' => $podatek,
								'kwota_vat' => $p['pot_cena_lacznie'] - $p['pot_cena_netto_lacznie']
						);
					}
					
					$ulica = $adres['adr_ulica'].' '.$adres['adr_numer_domu'];
					$numer_mieszkania = '';
					if(!empty($adres['adr_numer_mieszkania'])) {
						$numer_mieszkania = '/'.$adres['adr_numer_mieszkania'];
					}
					$ulica .= $numer_mieszkania;
					
					$parametry = array(
							'rodzaj' => 'PROFORMA',
							'odbiorca' => array(
									'imie' => $gosc['uzy_imie'],
									'nazwisko' => $gosc['uzy_nazwisko'],
									'numer_id' => '',
									'adres' => array(
											'ulica' => $ulica,
											'kod_pocztowy' => $adres['adr_kod_pocztowy'],
											'miejscowosc' => $adres['adr_miejscowosc']
									),
							),
							'pozycje' => $pozycje_na_fakturze,
							'data_zaplaty' => date('Y-m-d H:i:s'),
							'naleznosc_netto' => $netto_lacznie,
							'naleznosc_brutto' => $brutto_lacznie,
					);
					$faktura_id = $faktura_model->dodaj_fakture($parametry);
				}
				
				
				
				$parametry = array(
						'uzytkownik' => $zamowienie['osoba'],
						'potrawy' => $potrawy_ids,
						'ilosci' => $ilosci,
						'czy_faktura' => $zamowienie['faktura'],
						'faktura_id' => $faktura_id,
						'adres' => array(
							'ulica' => $adres['adr_ulica'].' '.$adres['adr_numer_domu'].'/'.$adres['adr_numer_mieszkania'],
							'kod_pocztowy' => $adres['adr_kod_pocztowy'],
							'miejscowosc' => $adres['adr_miejscowosc']
						),
						'cena' => array(
							'netto' => $netto_lacznie,
							'brutto' => $brutto_lacznie,
							'podatek' => $podatek
						)
				);
	
				$zp_id = $this->model->dodaj_zamowienie($parametry);
				$zamowienie_db = $this->model->pobierz_zamowienie($zp_id);
		
				Mail::wyslij_mail_potwierdzajacy_zamowienie_potrawy($zamowienie_db);
			
				Router::przekierowanie(array('controller' => 'Zamowienia'));
			}
		}
		else {
			Router::przekierowanie(array('controller' => 'Zamowienia'));
		}
	}
	
	public function podglad() {
		View::ustaw('tytul_strony', 'Zamówienia');
		View::ustaw('podtytul_strony', 'Podsumowanie zamówienia potrawy');

		if(!isset($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Zamowienia'));
		}
		$id = $_GET['id'];
		
		$zamowienie = $this->model->pobierz_zamowienie($id); 
		$zamowienie = $zamowienie[0];
		
		$potrawy = $this->model->pobierz_potrawy_do_zamowienia($zamowienie['zp_id']);	
		View::ustaw('potrawy', $potrawy);
		
		
		View::ustaw('cena_lacznie_netto', $zamowienie['zp_cena_calkowita_netto']);
		View::ustaw('cena_lacznie_brutto', $zamowienie['zp_cena_brutto']);
				
		global $konfiguracja;
		$podatek = $konfiguracja['tax']['kuchnia'];
		View::ustaw('podatek', $podatek);
				
		$adres_model = Model::zaladuj_model('Adresy');
		
		$adres = array(
			'adr_ulica' => $zamowienie['zp_adres_ulica'],
			'adr_kod_pocztowy' => $zamowienie['zp_kod_pocztowy'],
			'adr_miejscowosc' => $zamowienie['zp_miejscowosc']
		);
		View::ustaw('adres', $adres);
	
		$uzytkownik = $this->model->pobierz_uzytkownika_do_zamowienia($zamowienie['zp_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		View::ustaw('osoba', $uzytkownik);
			
		if(!empty($zamowienie['zp_czy_faktura'])) {
			View::ustaw('faktura', 'Tak');
			
			View::ustaw('faktura_id', $zamowienie['zp_fak_id']);
		}
		else {
			View::ustaw('faktura', 'Nie');
		}
			
	
		
	}
	
	public function anuluj() {
		if(empty($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Zamowienia'));
		}
		$id = $_GET['id'];
		
		$this->model->anuluj_zamowienie($id);
		
		$zamowienie = $this->model->pobierz_zamowienie($id);
		$zamowienie = $zamowienie[0];
		
		if($zamowienie['zp_czy_faktura']) {
			$faktura_model = Model::zaladuj_model('Faktury');
			$faktura_model->anuluj_fakture($zamowienie['zp_fak_id']);
		}
		
		
		
		Mail::wyslij_mail_anulujacy_zamowienie_potrawy($zamowienie);
		
		Session::ustaw_alert('sucess', 'Anulowano zamówienie potraw');
		Router::przekierowanie(array('controller' => 'Zamowienia'));
	}
}