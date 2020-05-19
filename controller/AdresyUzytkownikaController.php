<?php
class AdresyUzytkownikaController {
	private function sprawdz_kontekst_uzytkownika() {
		if(!isset($_GET['user_id'])) {
			Session::ustaw_alert('warning', 'Widok "Adresy użytkownika" może być uruchamiany wyłącznie w kontekście grupy');
			Router::przekierowanie(array('controller' => 'Uzytkownicy'));
		}
		else {
			View::ustaw('user_id', $_GET['user_id']);
		}
	}
	
	private function pobierz_nazwa_uzytkownika($user_id) {
		$sql = "SELECT uzy_imie, uzy_nazwisko FROM hotel_uzytkownicy WHERE uzy_id = ?";
		$parametry = array($user_id);
		$uzytkownik = Model::wykonaj_zapytanie_sql($sql, $parametry);
		return $uzytkownik[0]['uzy_imie'].' '.$uzytkownik[0]['uzy_nazwisko'];
	}
	
	private function ustaw_parent_menu() {
		if(isset($_GET['parent'])) {
			View::ustaw('parent_menu', $_GET['parent']);
		}
	}
	
	
	public function lista() {
		$this->sprawdz_kontekst_uzytkownika();
		$user_id = $_GET['user_id'];
		$nazwa_uzytkownika = $this->pobierz_nazwa_uzytkownika($user_id);	
		View::ustaw('tytul_strony', 'Adresy użytkownika');
		View::ustaw('podtytul_strony', 'Adresy dla '.$nazwa_uzytkownika);
		
		$adres_model = Model::zaladuj_model('Adresy');
		$adresy = $adres_model->pobierz_adresy_uzytkownika($user_id);
		$przetworzone_adresy = array();
		foreach ($adresy as $a) {
			$element = $a; 
			if(!empty($a['uxa_domyslny'])) {
				$element['uxa_domyslny'] = 'tak';
			}
			else {
				$element['uxa_domyslny'] = 'nie';
			}
			$przetworzone_adresy[] = $element;
		}
		View::ustaw('adresy', $przetworzone_adresy);
		
		$this->ustaw_parent_menu();
	}
	
	public function dodaj() {
		$this->sprawdz_kontekst_uzytkownika();
		$this->ustaw_parent_menu();
		$user_id = $_GET['user_id'];
		$nazwa_uzytkownika = $this->pobierz_nazwa_uzytkownika($user_id);
		View::ustaw('tytul_strony', 'Adresy użytkownika');
		View::ustaw('podtytul_strony', 'Dodaj adres dla '.$nazwa_uzytkownika);
		
		$adres_model = Model::zaladuj_model('Adresy');
		
		$wojewodztwa = $adres_model->pobierz_wojewodztwa();
		View::ustaw('wojewodztwa', $wojewodztwa);
					
		$parametry_z_formularza = $adres_model->pola;
		$parametry_z_formularza[] = 'adr_domyslny';
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
			
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
				
		
		if(!empty($_POST['adresFormularz']) && $_POST['adresFormularz'] == 'dodaj') {
			if(isset($_POST['adr_domyslny']) && $_POST['adr_domyslny'] == 'on') {
				$_POST['adr_domyslny'] = 1;
			}
			else {
				$_POST['adr_domyslny'] = 0;
			}
			
			$reguly_walidacji = $adres_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$adres_id = $adres_model->dodaj_adres($_POST);
				//powiaz adres z uzytkownikiem
				$adres_model->powiaz_adres($adres_id, $user_id, $_POST['adr_domyslny']);
				
				$link = array(
					'controller' => 'AdresyUzytkownika', 
					'user_id' => $user_id,
					'parent' => $_GET['parent']
				);
				
				Router::przekierowanie($link);
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		$this->sprawdz_kontekst_uzytkownika();
		$this->ustaw_parent_menu();
		$user_id = $_GET['user_id'];
		$nazwa_uzytkownika = $this->pobierz_nazwa_uzytkownika($user_id);
		View::ustaw('tytul_strony', 'Adresy użytkownika');
		View::ustaw('podtytul_strony', 'Dodaj adres dla '.$nazwa_uzytkownika);
		
		if(empty($_GET['id'])) {
			$link = array(
				'controller' => 'AdresyUzytkownika',
				'user_id' => $user_id,
			);
			
			if(!empty($_GET['parent'])) {
				$link['parent'] = $_GET['parent'];
			}
			
			Router::przekierowanie($link);
		}
		$id = $_GET['id'];
		View::ustaw('adr_id', $id);
		
		$adres_model = Model::zaladuj_model('Adresy');
		$adres = $adres_model->pobierz_adres($id);
		
		$adres_wiazanie = $adres_model->pobierz_wiazanie($id,$user_id);
	
		$adres[0]['adr_domyslny'] = $adres_wiazanie[0]['uxa_domyslny'];
		
		$wojewodztwa = $adres_model->pobierz_wojewodztwa();
		View::ustaw('wojewodztwa', $wojewodztwa);
					
		$parametry_z_formularza = $adres_model->pola;
		$parametry_z_formularza[] = 'adr_domyslny';
		
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $adres[0]);
			
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
				
		if(!empty($_POST['adresFormularz']) && $_POST['adresFormularz'] == 'edytuj') {
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			
			if(isset($_POST['adr_domyslny']) && $_POST['adr_domyslny'] == 'on') {
				$_POST['adr_domyslny'] = 1;
			}
			else {
				$_POST['adr_domyslny'] = 0;
			}
			
			$reguly_walidacji = $adres_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				
				$adres_model->aktualizuj_adres($_POST,$id);
				//powiaz adres z uzytkownikiem
				$adres_model->powiaz_adres($id, $user_id, $_POST['adr_domyslny']);
				
				$link = array(
					'controller' => 'AdresyUzytkownika', 
					'user_id' => $user_id,
					'parent' => $_GET['parent']
				);
				
				Router::przekierowanie($link);
				
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function usun() {
		$this->sprawdz_kontekst_uzytkownika();
		
		$user_id = $_GET['user_id'];
				
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			$this->przekieruj_do_poczatku($user_id);	
		}
		
		//sprawdzenie ile użytkownik ma adresów
		$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ? ";
		$parametry = array($user_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			if(count($result) > 1) {
				
				//sprawdzenie czy obecny adres jest domyślny
				$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_adr_id = ? AND uxa_uzy_id = ?";
				$parametry = array($id, $user_id);
				$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
				
				
				if(!empty($result[0]['uxa_domyslny'])) {
					
					//wybierz pierwszy niedomyslny adres o najnizszym id
					$sql = "SELECT * FROM hotel_uzytkownicy_x_adresy WHERE uxa_uzy_id = ? AND uxa_domyslny = 0 ORDER BY uxa_adr_id ASC LIMIT 1";
					$parametry = array($user_id);
					$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
					$uxa_id = $result[0]['uxa_id'];
					
					$sql = "UPDATE hotel_uzytkownicy_x_adresy SET uxa_domyslny = 1 WHERE uxa_id = ?";
					$parametry = array($uxa_id);
					Model::wykonaj_zapytanie_sql($sql, $parametry);

					
					$sql = "DELETE FROM hotel_uzytkownicy_x_adresy WHERE uxa_adr_id = ? AND uxa_uzy_id = ?";
					$parametry = array($id, $user_id);
					$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
						
					$sql = "DELETE FROM hotel_adresy WHERE adr_id = ?";
					$parametry = array($id);
					$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
					
				}
				else { //jeżeli to nie jest domyślny adres to można usunąć bez problemu
					$sql = "DELETE FROM hotel_uzytkownicy_x_adresy WHERE uxa_adr_id = ?";
					$parametry = array($id);
					$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
						
					$sql = "DELETE FROM hotel_adresy WHERE adr_id = ?";
					$parametry = array($id);
					$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
				}
			}
			else {
				Session::ustaw_alert('warning', 'Użytkownik posiada tylko jeden adres nie można go usunąć');
			}
		}
		
		$this->przekieruj_do_poczatku($user_id);
	}
	
	private function przekieruj_do_poczatku($user_id) {
		$link = array(
				'controller' => 'AdresyUzytkownika',
				'user_id' => $user_id,
		);
			
		if(isset($_GET['parent'])) {
			$link['parent'] = $_GET['parent'];
		}
			
		Router::przekierowanie($link);
	}
}