<?php
class UzytkownicyRestauracjiController {
public function lista() {
		View::ustaw('tytul_strony', 'Użytkownicy restauracji');
		View::ustaw('podtytul_strony', 'Lista użytkowników');
	
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		$uzytkownicy = $uzytkownicy_model->pobierz_wszystkich_uzytkownikow_gosci();
		View::ustaw('uzytkownicy', $uzytkownicy);
		
		$this->ustaw_parent_menu();
		
		$wszystkie_statusy = $uzytkownicy_model->pobierz_statusy_uzytkownika();
		View::ustaw('wszystkie_statusy', $wszystkie_statusy);
	}
	
	private function ustaw_parent_menu() {
		if(isset($_GET['parent'])) {
			View::ustaw('parent_menu', $_GET['parent']);
		}
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Użytkownicy restauracji');
		View::ustaw('podtytul_strony', 'Dodaj użytkownika');
		$this->ustaw_parent_menu();
	
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		
		$konta_uzytkownikow_model = Model::zaladuj_model('KontaUzytkownikow');
		$konta_uzytkownikow_model->walidacja_uzytkownicy_rezerwacja();
		$parametry_z_konta = $konta_uzytkownikow_model->pola;
		$parametry_z_formularza = array_merge($uzytkownicy_model->pola, $parametry_z_konta);
		
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['uzytkownikFormularz']) && $_POST['uzytkownikFormularz'] == 'dodaj') {
			$bledy_walidacji_konto = $konta_uzytkownikow_model->waliduj($_POST);
			$reguly_walidacji = $uzytkownicy_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			
			$bledy_walidacji = array_merge_recursive($bledy_walidacji_konto, $bledy_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			
			if(!$czy_sa_bledy) {
				//czy istnieje rola goscia restauracji
				$sql = "SELECT * FROM hotel_role WHERE rol_kod = 'GOSC_RESTAURACJI'";
				$result = Model::wykonaj_zapytanie_sql($sql);
				if(empty($result)) {
					Session::ustaw_alert('error', 'Brak odpowiedniej roli dla gościa restauracji. Skontaktuj się z administratorem.');
				}
				else {
					$konto = array(
						'email' => $_POST['kuz_email'],
						'rola' => $result[0]['rol_id'],
					);
					$konto_id = $konta_uzytkownikow_model->dodaj_konto_uzytkownika_goscia($konto);
					
					$uzytkownik = array(
						'imie' => $_POST['uzy_imie'],
						'nazwisko' => $_POST['uzy_nazwisko'],
						'telefon' => $_POST['uzy_telefon_kontaktowy']
					);
					
					$uzytkownik_id = $uzytkownicy_model->dodaj_uzytkownika($uzytkownik, $konto_id);
					Mail::wyslij_mail_aktywacyjny($uzytkownik_id);
					
					if(!empty($uzytkownik_id)) {
						$link = array(
							'controller' => 'UzytkownicyRestauracji', 
							'action' => 'edytuj', 
							'id' => $uzytkownik_id,
							'parent' => $_GET['parent'],
						);
						Router::przekierowanie($link);
					}
				}
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function  edytuj() {
		View::ustaw('tytul_strony', 'Użytkownicy restauracji');
		View::ustaw('podtytul_strony', 'Edytuj użytkownika');
		$this->ustaw_parent_menu();
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		
		$parametry_z_formularza = $uzytkownicy_model->pola;
		$id = $_GET['id'];
		View::ustaw('uzy_id', $id);

		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'UzytkownicyStoliki'));
		}
		
		$sql = "SELECT * FROM hotel_uzytkownicy WHERE uzy_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		$uzytkownik = $result[0];
		
		$konto_uzytkownika_model = Model::zaladuj_model('KontaUzytkownikow');
		$konto = $konto_uzytkownika_model->pobierz_konto_uzytkownika($uzytkownik['uzy_kuz_id']);
		
		$do_formularza = array_merge_recursive($uzytkownik, $konto[0]);
		
		View::ustaw('dane_do_formularza', $do_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		$bledy_walidacji['kuz_email'] = array();
		$bledy_walidacji['kuz_rol_id'] = array();
		
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['uzytkownikFormularz']) && $_POST['uzytkownikFormularz'] == 'edytuj') {
	
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			$dane_z_formularza['kuz_email'] = $_POST['kuz_email'];
			
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			$reguly_walidacji = $uzytkownicy_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$bledy_walidacji['kuz_email'] = array();
			
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$uzytkownicy_model->aktualizuj_dane_uzytkownika($_POST, $uzytkownik['uzy_id']);
				
				Router::przekierowanie(array('controller' => 'UzytkownicyStoliki'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	

	}
	
}