<?php
class RezerwacjeStolikowController {
	public function lista() {
		View::ustaw('tytul_strony', 'Rezerwacje stolików');
		View::ustaw('podtytul_strony', 'Lista rezerwacji stolików');
		
		$wszystkie_rezerwacje = $this->model->pobierz_wszystkie_rezerwacje();
		$przetworzone_rezerwacje = array();
		foreach ($wszystkie_rezerwacje as $r) {
			$element = $r;
			if(!empty($r['rs_anulowano'])) {
				$element['rs_anulowano'] = 'tak';
			}
			else {
				$element['rs_anulowano'] = 'nie';
			}
			
			$przetworzone_rezerwacje[] = $element;
		}
		
		View::ustaw('rezerwacje', $przetworzone_rezerwacje);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Rezerwacje stolików');
		View::ustaw('podtytul_strony', 'Lista dodaj rezerwację stolika');
		
		$sale = $this->model->pobierz_sale_restauracyjne();
		$przetworzone_sale = array();
		foreach ($sale as $s) {
			$przetworzone_sale[$s['sar_id']] = $s['sar_nazwa'];
		}
		View::ustaw('sale', $przetworzone_sale);
		
		if(empty($sale)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych sal restauracyjnych. Dodaj przynajmniej jedną salę restauracyjną.');
			Router::przekierowanie(array('controller' => 'SaleRestauracyjne'));
		}
		
		$stoliki_model = Model::zaladuj_model('Stoliki');
		$stoliki = $stoliki_model->pobierz_wszystkie_stoliki();
		if(empty($stoliki)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych stolików. Dodaj przynajmniej jeden stolik do systemu.');
			Router::przekierowanie(array('controller' => 'Stoliki'));
		}
		
		$this->model->walidacja_data_sala();
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['rsFormularz']) && $_POST['rsFormularz'] == 'dodaj') {
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				Router::przekierowanie(array('controller' => 'RezerwacjeStolikow', 
											 'action' => 'stolik', 
											 'r[data]' => base64_encode($_POST['rs_data_rezerwacji']),
											 'r[sala]' => $_POST['rs_sala_id'],
				));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function stolik() {
		View::ustaw('tytul_strony', 'Rezerwacje stolików');
		View::ustaw('podtytul_strony', 'Wybierz stolik');
		
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
			$sala = $rezerwacja['sala'];
			$data = base64_decode($rezerwacja['data']);
			$stoliki = $this->model->pobierz_stoliki_na_podstawie_rezerwacji($sala, $data);
			
			global $konfiguracja;
			$podatek = $konfiguracja['tax']['restauracja'];
			
			$przetworzone_stoliki = array();
			foreach ($stoliki as $s) {
				$element = $s;
				
				$element['sto_cena_brutto'] = $s['sto_cena_netto'] + (($podatek/100) * $s['sto_cena_netto']);
				
				$przetworzone_stoliki[] = $element;
			}
			
			View::ustaw('stoliki', $przetworzone_stoliki);
		}
		else {
			Router::przekierowanie(array('controller' => 'RezerwacjeStolików'));
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
			
			$uzytkownicy = $this->model->pobierz_uzytkownikow();
			$przetworzeni_uzytkownicy = array();
			foreach ($uzytkownicy as $u) {
				$label = $u['uzy_imie'].' '.$u['uzy_nazwisko'].' ('.$u['kuz_email'].')';
				
				$przetworzeni_uzytkownicy[$u['uzy_id']] = $label; 
			}
			View::ustaw('uzytkownicy', $przetworzeni_uzytkownicy);
			
			$this->model->walidacja_uzytkownikow();
			
			$parametry_z_formularza = $this->model->pola;
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
			View::ustaw('bledy_walidacji', $bledy_walidacji);
			
			if(!empty($_POST['rpFormularz']) && $_POST['rpFormularz'] == 'dodaj') {
				
				if(isset($_POST['rs_czy_faktura']) && $_POST['rs_czy_faktura'] == 'on') {
					$_POST['rs_czy_faktura'] = 1;
				}
				else {
					$_POST['rs_czy_faktura'] = 0;
				}
								
				$reguly_walidacji = $this->model->walidacja;
				$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
				$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				if(!$czy_sa_bledy) {
					$r = $_GET['r'];
					$redirect_link = array(
						'controller' => 'RezerwacjeStolikow',
						'r[data]' => $r['data'],
						'r[sala]' => $r['sala'],
						'r[stolik]' => $r['stolik'],
						'r[osoba]' => $_POST['rs_uzy_id'],
						'r[liczba]' => $_POST['rs_liczba_osob'],
						'r[faktura]' => $_POST['rs_czy_faktura'],
					);
					
					$sql = "SELECT * FROM hotel_stoliki WHERE sto_id = ?";
					$p = array($r['stolik']);
					$result = Model::wykonaj_zapytanie_sql($sql, $p);
					$liczba_miejsc = $result[0]['sto_liczba_miejsc'];
					
					if($liczba_miejsc < $_POST['rs_liczba_osob']) {
						Session::ustaw_alert('warning', 'Wybrany stolik nie jest przystosowany dla tylu osób');
						$redirect_link['action'] = 'osoba';
						Router::przekierowanie($redirect_link);
					}
					
					if(!empty($_POST['rs_czy_faktura'])) {
						//czy wybrany uzytkownik posiada adres 
						$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ?";
						$parametry = array($_POST['rs_uzy_id']);
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
	
	public function podsumowanie() {
		View::ustaw('tytul_strony', 'Rezerwacje');
		View::ustaw('podtytul_strony', 'Podsumowanie rezerwacji pokoju');
		
		if(!empty($_GET['r'])) {
			$rezerwacja = $_GET['r'];
			$this->ustaw_parametry_rezerwacji($rezerwacja);
			
			$data = base64_decode($rezerwacja['data']);
			
			View::ustaw('data', $data);
			
			$sale_model = Model::zaladuj_model('SaleRestauracyjne');
			$sala = $sale_model->pobierz_sale($rezerwacja['sala']);
			$sala = $sala[0];
			View::ustaw('sala', $sala);
			
			global $konfiguracja;
			$podatek = $konfiguracja['tax']['restauracja'];
			
			$stoliki_model = Model::zaladuj_model('Stoliki');
			$stolik = $stoliki_model->pobierz_stolik($rezerwacja['stolik']);
			$stolik = $stolik[0];
			View::ustaw('stolik', $stolik);
			
			View::ustaw('liczba_osob', $rezerwacja['liczba']);
			
			if(!empty($rezerwacja['faktura'])) {
				$adres_model = Model::zaladuj_model('Adresy');
				$adres = $adres_model->pobierz_adres($rezerwacja['adres']);
				$adres = $adres[0];
				View::ustaw('adres', $adres);
			}
			
			$uzytkownik_model = Model::zaladuj_model('Uzytkownicy');
			$uzytkownik = $uzytkownik_model->pobierz_uzytkownika($rezerwacja['osoba']);
			$uzytkownik = $uzytkownik[0];
			View::ustaw('osoba', $uzytkownik);
			
			$cena_netto = $stolik['sto_cena_netto'];
			View::ustaw('cena_netto', $cena_netto);
			$cena_brutto = $stolik['sto_cena_netto'] + (($podatek/100) * $stolik['sto_cena_netto']);
			View::ustaw('cena_brutto', $cena_brutto);
			
			$cena_calkowita = $cena_brutto ;
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
						'nazwa' => $sala['sar_nazwa'].' - Stolik numer '.$stolik['sto_numer'].' dla '.$rezerwacja['liczba'].' osób',
						'liczba' => 1,
						'netto' => $cena_netto,
						'brutto' => $cena_brutto,
						'podatek' => $podatek,
						'kwota_vat' => $cena_brutto - $cena_netto
					);
					
					$ulica = $adres['adr_ulica'].' '.$adres['adr_numer_domu'];
					$numer_mieszkania = '';
					if(!empty($adres['adr_numer_mieszkania'])) {
						$numer_mieszkania = '/'.$adres['adr_numer_mieszkania'];
					}
					$ulica .= $numer_mieszkania;
					
					$parametry = array(
						'rodzaj' => 'PROFORMA',
						'odbiorca' => array(
							'imie' => $uzytkownik['uzy_imie'],
							'nazwisko' => $uzytkownik['uzy_nazwisko'],
							'numer_id' => '',
							'adres' => array(
								'ulica' => $ulica,
								'kod_pocztowy' => $adres['adr_kod_pocztowy'],
								'miejscowosc' => $adres['adr_miejscowosc']
							),
						),
						'pozycje' => $pozycje_na_fakturze,
						'data_zaplaty' => $data,
						'naleznosc_netto' => $cena_netto,
						'naleznosc_brutto' => $cena_brutto,
					); 
					$faktura_id = $faktura_model->dodaj_fakture($parametry);
				}
				
				
				$parametry = array(
					'data' => $data,
					'stolik' => $rezerwacja['stolik'],
					'uzytkownik' => $rezerwacja['osoba'],
					'liczba_osob' => $rezerwacja['liczba'],
					'czy_faktura' => $rezerwacja['faktura'],
					'faktura_id' => $faktura_id,
					
					'cena' => array(
						'netto' => $cena_netto,
						'brutto' => $cena_brutto,
						'podatek' => $podatek
					)
				);
				
				$rezerwacja_id = $this->model->dodaj_rezerwacje($parametry);
				$rezerwacja = $this->model->pobierz_rezerwacje($rezerwacja_id);
				
				Mail::wyslij_mail_potwierdzajacy_rezerwacje_stolika($rezerwacja);
				
				Session::ustaw_alert('success', 'Poprawnie wygenerowano rezerwację');
				Router::przekierowanie(array('controller' => 'Rezerwacje'));
			}
		}
		else {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
	}
	
	public function podglad() {
		View::ustaw('tytul_strony', 'Rezerwacje stolików');
		View::ustaw('podtytul_strony', 'Podgląd rezerwacji stolika');
		
		if(!isset($_GET['id'])) {
			Router::przekierowanie(array('RezerwacjeStolikow'));	
		}
		$id = $_GET['id'];
		
		$rezerwacja = $this->model->pobierz_rezerwacje($id);
		$rezerwacja = $rezerwacja[0];
		
		$data = $rezerwacja['rs_data_rezerwacji'];
		View::ustaw('data', $data);
		
		$stoliki_model = Model::zaladuj_model('Stoliki');
		$stolik = $stoliki_model->pobierz_stolik($rezerwacja['rs_sto_id']);
		$stolik = $stolik[0];
		View::ustaw('stolik', $stolik);
		
		
		$sale_model = Model::zaladuj_model('SaleRestauracyjne');
		$sala = $sale_model->pobierz_sale($stolik['sto_sar_id']);
		$sala = $sala[0];
		View::ustaw('sala', $sala);
				
		global $konfiguracja;
		$podatek = $konfiguracja['tax']['restauracja'];
							
		View::ustaw('liczba_osob', $rezerwacja['rs_liczba_osob']);
				
		$uzytkownik_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownik = $uzytkownik_model->pobierz_uzytkownika($rezerwacja['rs_uzy_id']);
		$uzytkownik = $uzytkownik[0];
		View::ustaw('osoba', $uzytkownik);
				
		$cena_netto = $stolik['sto_cena_netto'];
		View::ustaw('cena_netto', $cena_netto);
		$cena_brutto = $stolik['sto_cena_netto'] + (($podatek/100) * $stolik['sto_cena_netto']);
		View::ustaw('cena_brutto', $cena_brutto);
				
		$cena_calkowita = $cena_brutto ;
		View::ustaw('cena_calkowita', $cena_calkowita);
				
		$faktura = 'Nie';
		if(!empty($rezerwacja['rs_czy_faktura']))  {
			$faktura = 'Tak';
			
			View::ustaw('faktura_id', $rezerwacja['rs_fak_id']);
		}
		View::ustaw('faktura', $faktura);
	}
	
	public function anuluj() {
		$id = $_GET['id'];
		
		if(empty($id)) {
			Router::przekierowanie(array('controller' => 'Rezerwacje'));
		}
		
		$this->model->anuluj_rezerwacje($id);
		
		$rezerwacja = $this->model->pobierz_rezerwacje($id);
		$rezerwacja = $rezerwacja[0];
		
		if($rezerwacja['rs_czy_faktura']) {
			$faktura_model = Model::zaladuj_model('Faktury');
			$faktura_model->anuluj_fakture($rezerwacja['rs_fak_id']);
		}
		
		Mail::wyslij_mail_anulujacy_rezerwacje_pokoju($rezerwacja);
		
		Session::ustaw_alert('sucess', 'Anulowano rezerwację pokoju');
		Router::przekierowanie(array('controller' => 'RezerwacjeStolikow'));
	}
}