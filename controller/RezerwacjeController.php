<?php
class RezerwacjeController {
	public function lista() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Lista rezerwacji pokoi');
		
		$wszystkie_rezerwacje = $this->model->pobierz_wszystkie_rezerwacje();
		$przetworzone_rezerwacje = array();
		foreach ($wszystkie_rezerwacje as $r) {
			$element = $r;
			if(!empty($r['rp_anulowano'])) {
				$element['rp_anulowano'] = 'tak';
			}
			else {
				$element['rp_anulowano'] = 'nie';
			}
			
			$przetworzone_rezerwacje[] = $element;
		}
		
		View::ustaw('rezerwacje', $przetworzone_rezerwacje);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Lista dodaj rezerwację');
		
		$grupy = $this->model->pobierz_grupy_pokoi();
		$przetworzone_grupy = array();
		foreach ($grupy as $g) {
			$przetworzone_grupy[$g['grp_id']] = $g['grp_nazwa'];
		}
		View::ustaw('grupy', $przetworzone_grupy);
		
		if(empty($grupy)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych grup pokoi. Dodaj przynajmniej jedną grupę pokoi do systemu.');
			Router::przekierowanie(array('controller' => 'GrupyPokoi'));
		}
		
		$pokoje_model = Model::zaladuj_model('Pokoje');
		$pokoje = $pokoje_model->pobierz_wszystkie_pokoje();
		if(empty($pokoje)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych pokoi. Dodaj przynajmniej jeden pokój do systemu');
			Router::przekierowanie(array('controller' => 'Pokoje'));
		}
		
		$this->model->walidacja_data_grupa();
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['rpFormularz']) && $_POST['rpFormularz'] == 'dodaj') {
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				Router::przekierowanie(array('controller' => 'Rezerwacje', 
											 'action' => 'pokoje', 
											 'r[od]' => base64_encode($_POST['rp_data_od']),
											 'r[do]' => base64_encode($_POST['rp_data_do']),
											 'r[grupa]' => $_POST['rp_grupa_id'],
				));
		
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function pokoje() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Wybierz pokój');
		
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
			$grupa = $rezerwacja['grupa'];
			$od = base64_decode($rezerwacja['od']);
			$do = base64_decode($rezerwacja['do']);
			$pokoje = $this->model->pobierz_pokoje_na_podstawie_rezerwacji($grupa, $od, $do);
			View::ustaw('pokoje', $pokoje);
		}
		else {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
	}
	
	private function ustaw_parametry_rezerwacji($r) {
		foreach ($r as $klucz => $element) {
			View::ustaw('r['.$klucz.']', $element);
		}
	}
	
	public function osoba() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Wybierz gościa hotelowego');
		
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
			
			$goscie = $this->model->pobierz_gosci_hotelowych();
			$przetworzeni_goscie = array();
			foreach ($goscie as $g) {
				$label = $g['uzy_imie'].' '.$g['uzy_nazwisko'].' ('.$g['kuz_email'].')';
				
				$przetworzeni_goscie[$g['uzy_id']] = $label; 
			}
			View::ustaw('goscie', $przetworzeni_goscie);
			
			$this->model->walidacja_gosc();
			
			$parametry_z_formularza = $this->model->pola;
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
			View::ustaw('bledy_walidacji', $bledy_walidacji);
			
			if(!empty($_POST['rpFormularz']) && $_POST['rpFormularz'] == 'dodaj') {
				
				if(isset($_POST['rp_czy_faktura']) && $_POST['rp_czy_faktura'] == 'on') {
					$_POST['rp_czy_faktura'] = 1;
				}
				else {
					$_POST['rp_czy_faktura'] = 0;
				}
								
				$reguly_walidacji = $this->model->walidacja;
				$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
				$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				if(!$czy_sa_bledy) {
					$r = $_GET['r'];
					$redirect_link = array(
						'controller' => 'Rezerwacje',
						'r[grupa]' => $r['grupa'],
						'r[od]' => $r['od'],
						'r[do]' => $r['do'],
						'r[pokoj]' => $r['pokoj'],
						'r[osoba]' => $_POST['rp_uzy_id'],
						'r[liczba]' => $_POST['rp_liczba_osob'],
						'r[faktura]' => $_POST['rp_czy_faktura'],
					);
					
					if(!empty($_POST['rp_czy_faktura'])) {
						//czy wybrany uzytkownik posiada adres 
						$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ?";
						$parametry = array($_POST['rp_uzy_id']);
						$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
						
						if(!empty($result)) {
							$redirect_link['action'] = 'osoba_adres_wybierz';
						}
						else {
							$redirect_link['action'] = 'osoba_adres_dodaj';
						}
					}
					else {
						$redirect_link['action'] = 'podsumowanie';
					}
					
					Router::przekierowanie($redirect_link);
				}
				else {
					View::ustaw('bledy_walidacji', $bledy_walidacji);
				}
			}
			
		}
		else {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
	}
	
	public function osoba_adres_dodaj() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Dodaj adres do faktury');
		
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
			
			$adres_model = Model::zaladuj_model('Adresy');
					
			$wojewodztwa = $adres_model->pobierz_wojewodztwa();
			View::ustaw('wojewodztwa', $wojewodztwa);
						
			$parametry_z_formularza = $adres_model->pola;
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
			View::ustaw('bledy_walidacji', $bledy_walidacji);
				
			if(!empty($_POST['rpFormularz']) && $_POST['rpFormularz'] == 'dodaj') {
			
				$reguly_walidacji = $adres_model->walidacja;
				$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
				$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				if(!$czy_sa_bledy) {
					$r = $_GET['r'];
					
					//dodaj adres
					$adres_id = $adres_model->dodaj_adres($_POST);
					//powiaz adres z uzytkownikiem
					$adres_model->dodaj_domyslny_adres($adres_id, $r['osoba']);
					
					$redirect_link = array(
						'controller' => 'Rezerwacje',
						'action' => 'podsumowanie',
						'r[grupa]' => $r['grupa'],
						'r[od]' => $r['od'],
						'r[do]' => $r['do'],
						'r[pokoj]' => $r['pokoj'],
						'r[osoba]' => $r['osoba'],
						'r[liczba]' => $r['liczba'],
						'r[faktura]' => $r['faktura'],
						'r[adres]' => $adres_id
					);
					
					Router::przekierowanie($redirect_link);
				}
				else {
					View::ustaw('bledy_walidacji', $bledy_walidacji);
				}
			}
		}
		else {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
	}
	
	public function osoba_adres_wybierz() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Wybierz adres do faktury');
		
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
			
			$adres_model = Model::zaladuj_model('Adresy');
			
			$adresy_uzytkownika = $adres_model->pobierz_adresy_uzytkownika($rezerwacja['osoba']);
			View::ustaw('adresy', $adresy_uzytkownika);			
		}
		else {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
	}
	
	public function podglad() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Podgląd rezerwacji');
		
		if(empty($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
		$id = $_GET['id'];
		$rezerwacja = $this->model->pobierz_rezerwacje($id);
		$r = $rezerwacja[0];
		
		$data_od = $r['rp_data_od'];
		$data_do = $r['rp_data_do'];
		
		View::ustaw('data_od', $data_od);
		View::ustaw('data_do', $data_do);
		
		$od = new DateTime($data_od);
		$do  = new DateTime($data_do);
		$roznica = $od->diff($do);
			
		$liczba_dni = $roznica->days;
		View::ustaw('liczba_dni', $liczba_dni);
			
		$grupa_model = Model::zaladuj_model('GrupyPokoi');
		$grupa = $grupa_model->pobierz_grupe_pokoi_na_podstawie_pokoju($r['rp_pok_id']);
		$grupa = $grupa[0];
		View::ustaw('grupa', $grupa);
			
		global $konfiguracja;
		$podatek = $konfiguracja['tax']['pokoj'];
		$dodatek_za_dostawke = $konfiguracja['price']['dostawka'];
						
			
		$pokoj_model = Model::zaladuj_model('Pokoje');
		$pokoj = $pokoj_model->pobierz_pokoj($r['rp_pok_id']);
		$pokoj = $pokoj[0];
		View::ustaw('pokoj', $pokoj);
			
		$gosc_model = Model::zaladuj_model('GoscieHotelowi');
		$gosc = $gosc_model->pobierz_goscia_hotelowego($r['rp_uzy_id']);
		$gosc = $gosc[0];
		View::ustaw('osoba', $gosc);
			
		$liczba_osob = $r['rp_liczba_osob'];
		$ile_dostawek = $liczba_osob - $pokoj['pok_liczba_osob'];
			
		$cena_netto_za_dostawki = 0;
		$cena_brutto_za_dostawki = 0;
		if($ile_dostawek > 0) {
			$cena_netto_za_dostawki = $ile_dostawek * $dodatek_za_dostawke;
			View::ustaw('cena_netto_dostawki', $cena_netto_za_dostawki);
			$cena_brutto_za_dostawki = $cena_netto_za_dostawki + (($podatek/100) * $cena_netto_za_dostawki);
			View::ustaw('cena_brutto_dostawki', $cena_brutto_za_dostawki);
		}
		
		$cena_netto = $liczba_dni * $grupa['grp_cena_netto'];
		View::ustaw('cena_netto', $cena_netto);
		$cena_brutto = $grupa['grp_cena_netto'] + (($podatek/100) * $grupa['grp_cena_netto']);
		$cena_brutto = $liczba_dni * $cena_brutto;
		View::ustaw('cena_brutto', $cena_brutto);
		
		$cena_calkowita = $cena_brutto + $cena_brutto_za_dostawki;
		View::ustaw('cena_calkowita', $cena_calkowita);
		
		$faktura = 'Nie';
		if(!empty($r['rp_czy_faktura']))  {
			$faktura = 'Tak';
		}
		View::ustaw('faktura', $faktura);
		
		View::ustaw('faktura_id', $r['rp_fak_id']);
	}
	
	public function podsumowanie() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Podsumowanie rezerwacji pokoju');
	
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
				
			$data_od = base64_decode($rezerwacja['od']);
			$data_do = base64_decode($rezerwacja['do']);
				
			View::ustaw('data_od', $data_od);
			View::ustaw('data_do', $data_do);
				
			$od = new DateTime($data_od);
			$do  = new DateTime($data_do);
			$roznica = $od->diff($do);
				
			$liczba_dni = $roznica->days;
			View::ustaw('liczba_dni', $liczba_dni);
				
			$grupa_model = Model::zaladuj_model('GrupyPokoi');
			$grupa = $grupa_model->pobierz_grupe_pokoi($rezerwacja['grupa']);
			$grupa = $grupa[0];
			View::ustaw('grupa', $grupa);
				
			global $konfiguracja;
			$podatek = $konfiguracja['tax']['pokoj'];
			$dodatek_za_dostawke = $konfiguracja['price']['dostawka'];
	
				
			$pokoj_model = Model::zaladuj_model('Pokoje');
			$pokoj = $pokoj_model->pobierz_pokoj($rezerwacja['pokoj']);
			$pokoj = $pokoj[0];
			View::ustaw('pokoj', $pokoj);
				
			if(!empty($rezerwacja['faktura'])) {
				$adres_model = Model::zaladuj_model('Adresy');
				$adres = $adres_model->pobierz_adres($rezerwacja['adres']);
				$adres = $adres[0];
				View::ustaw('adres', $adres);
			}
				
			$gosc_model = Model::zaladuj_model('GoscieHotelowi');
			$gosc = $gosc_model->pobierz_goscia_hotelowego($rezerwacja['osoba']);
			$gosc = $gosc[0];
			View::ustaw('osoba', $gosc);
				
			$liczba_osob = $rezerwacja['liczba'];
			$ile_dostawek = $liczba_osob - $pokoj['pok_liczba_osob'];
				
			$cena_netto_za_dostawki = 0;
			$cena_brutto_za_dostawki = 0;
			if($ile_dostawek > 0) {
				$cena_netto_za_dostawki = $ile_dostawek * $dodatek_za_dostawke;
				View::ustaw('cena_netto_dostawki', $cena_netto_za_dostawki);
				$cena_brutto_za_dostawki = $cena_netto_za_dostawki + (($podatek/100) * $cena_netto_za_dostawki);
				View::ustaw('cena_brutto_dostawki', $cena_brutto_za_dostawki);
			}
				
			$cena_netto = $liczba_dni * $grupa['grp_cena_netto'];
			View::ustaw('cena_netto', $cena_netto);
			$cena_brutto = $grupa['grp_cena_netto'] + (($podatek/100) * $grupa['grp_cena_netto']);
			$cena_brutto = $liczba_dni * $cena_brutto;
			View::ustaw('cena_brutto', $cena_brutto);
				
			$cena_calkowita = $cena_brutto + $cena_brutto_za_dostawki;
			View::ustaw('cena_calkowita', $cena_calkowita);
				
			$faktura = 'Nie';
			if(!empty($rezerwacja['faktura']))  {
				$faktura = 'Tak';
			}
			View::ustaw('faktura', $faktura);
				
				
				
			if(isset($_POST['rpFormularz']) && $_POST['rpFormularz'] == 'dodaj') {
	
				$faktura_id = null;
				if(!empty($rezerwacja['faktura'])) {
	
					//najpierw faktura aby mieć
					$faktura_model = Model::zaladuj_model('Faktury');
						
					$pozycje_na_fakturze = array();
						
					$pozycje_na_fakturze[] = array(
							'nazwa' => $grupa['grp_nazwa'].' - Pokój numer '.$pokoj['pok_numer'].' na piętrze '.$pokoj['pok_pietro'],
							'liczba' => $liczba_dni,
							'netto' => $cena_netto,
							'brutto' => $cena_brutto,
							'podatek' => $podatek,
							'kwota_vat' => $cena_brutto - $cena_netto
					);
						
					if($ile_dostawek > 0) {
						$pozycje_na_fakturze[] = array(
								'nazwa' => 'Dostawka dla pokoju numer '.$pokoj['pok_numer'].' na piętrze '.$pokoj['pok_pietro'],
								'liczba' => 1,
								'netto' => $cena_netto_za_dostawki,
								'brutto' => $cena_brutto_za_dostawki,
								'podatek' => $podatek,
								'kwota_vat' => $cena_brutto_za_dostawki - $cena_netto_za_dostawki
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
									'numer_id' => $gosc['gh_pesel'],
									'adres' => array(
											'ulica' => $ulica,
											'kod_pocztowy' => $adres['adr_kod_pocztowy'],
											'miejscowosc' => $adres['adr_miejscowosc']
									),
							),
							'pozycje' => $pozycje_na_fakturze,
							'data_zaplaty' => $data_od,
							'naleznosc_netto' => $cena_netto + $cena_netto_za_dostawki,
							'naleznosc_brutto' => $cena_brutto + $cena_brutto_za_dostawki,
					);
					$faktura_id = $faktura_model->dodaj_fakture($parametry);
				}
	
	
				$parametry = array(
						'od' => $data_od,
						'do' => $data_do,
						'pokoj' => $rezerwacja['pokoj'],
						'uzytkownik' => $rezerwacja['osoba'],
						'liczba_osob' => $rezerwacja['liczba'],
						'czy_faktura' => $rezerwacja['faktura'],
						'faktura_id' => $faktura_id,
							
						'cena' => array(
								'netto' => $cena_netto + $cena_netto_za_dostawki,
								'brutto' => $cena_brutto + $cena_brutto_za_dostawki,
								'podatek' => $podatek
						)
				);
	
				$rezerwacja_id = $this->model->dodaj_rezerwacje($parametry);
				$rezerwacja = $this->model->pobierz_rezerwacje($rezerwacja_id);
				Mail::wyslij_mail_potwierdzajacy_rezerwacje_pokoju($rezerwacja);
	
				Session::ustaw_alert('success', 'Dodano rezerwację pokoju');
				Router::przekierowanie(array('controller' => 'Rezerwacje'));
			}
		}
		else {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
	}
	
	public function anuluj() {
		if(empty($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
		$id = $_GET['id'];
		
		$this->model->anuluj_rezerwacje($id);
		
		$rezerwacja = $this->model->pobierz_rezerwacje($id);
		$rezerwacja = $rezerwacja[0];
		
		if($rezerwacja['rp_czy_faktura']) {
			$faktura_model = Model::zaladuj_model('Faktury');
			$faktura_model->anuluj_fakture($rezerwacja['rp_fak_id']);
		}
		
		Mail::wyslij_mail_anulujacy_rezerwacje_pokoju($rezerwacja);
		
		Session::ustaw_alert('sucess', 'Anulowano rezerwację pokoju');
		Router::przekierowanie(array('controller' => 'Rezerwacje'));
	}
}