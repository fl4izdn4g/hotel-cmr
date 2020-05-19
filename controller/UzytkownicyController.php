<?php
class UzytkownicyController {
public function lista() {
		View::ustaw('tytul_strony', 'Użytkownicy');
		View::ustaw('podtytul_strony', 'Lista użytkowników');
	
		$uzytkownicy = $this->model->pobierz_wszystkich_uzytkownikow();
		View::ustaw('uzytkownicy', $uzytkownicy);
		
		
		$wszystkie_statusy = $this->model->pobierz_statusy_uzytkownika();
		View::ustaw('wszystkie_statusy', $wszystkie_statusy);
	}
	
	private function pobierz_role() {
		$role_model = Model::zaladuj_model('Role');
		$role = $role_model->pobierz_wszystkie_role();
		$przetworzone_role = array();
		foreach ($role as $r) {
			$przetworzone_role[$r['rol_id']] = $r['rol_nazwa'];
		}
		
		View::ustaw('wszystkie_role', $przetworzone_role);
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Użytkownicy');
		View::ustaw('podtytul_strony', 'Dodaj użytkownika');
		
		$this->pobierz_role();
		
		$konta_uzytkownikow_model = Model::zaladuj_model('KontaUzytkownikow');
		$parametry_z_konta = $konta_uzytkownikow_model->pola;
		$parametry_z_konta[] = 'kuz_haslo_powtorz';
		$parametry_z_formularza = array_merge($this->model->pola, $parametry_z_konta);
		
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['uzytkownikFormularz']) && $_POST['uzytkownikFormularz'] == 'dodaj') {
			$bledy_walidacji_konto = $konta_uzytkownikow_model->waliduj($_POST);
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			
			$bledy_walidacji = array_merge_recursive($bledy_walidacji_konto, $bledy_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			
			if(!$czy_sa_bledy) {
				$konto = array(
					'email' => $_POST['kuz_email'],
					'haslo' => $_POST['kuz_haslo'],
					'rola' => $_POST['kuz_rol_id'],
				);
				$konto_id = $konta_uzytkownikow_model->dodaj_konto_uzytkownika($konto);
				
				$uzytkownik = array(
					'imie' => $_POST['uzy_imie'],
					'nazwisko' => $_POST['uzy_nazwisko'],
					'telefon' => $_POST['uzy_telefon_kontaktowy']
				);
				
				$uzytkownik_id = $this->model->dodaj_uzytkownika($uzytkownik, $konto_id);
				Mail::wyslij_mail_aktywacyjny($uzytkownik_id);
				
				if(!empty($uzytkownik_id)) {
					Router::przekierowanie(array('controller' => 'Uzytkownicy', 'action' => 'edytuj', 'id' => $uzytkownik_id));
				}
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function  edytuj() {
		View::ustaw('tytul_strony', 'Użytkownicy');
		View::ustaw('podtytul_strony', 'Edytuj użytkownika');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('uzy_id', $id);

		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Uzytkownicy'));
		}
		
		$this->pobierz_role();
		
		$sql = "SELECT * FROM hotel_uzytkownicy WHERE uzy_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		$uzytkownik = $result[0];
		
		$konto_uzytkownika_model = Model::zaladuj_model('KontaUzytkownikow');
		$konto = $konto_uzytkownika_model->pobierz_konto_uzytkownika($uzytkownik['uzy_kuz_id']);
		
		$do_formularza = array_merge_recursive($uzytkownik, $konto[0]);
		$do_formularza['kuz_haslo'] = '';
		$do_formularza['kuz_haslo_powtorz'] = '';
		
		View::ustaw('dane_do_formularza', $do_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		$bledy_walidacji['kuz_email'] = array();
		$bledy_walidacji['kuz_haslo'] = array();
		$bledy_walidacji['kuz_haslo_powtorz'] = array();
		$bledy_walidacji['kuz_rol_id'] = array();
		
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['uzytkownikFormularz']) && $_POST['uzytkownikFormularz'] == 'edytuj') {
	
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			$dane_z_formularza['kuz_email'] = $_POST['kuz_email'];
			$dane_z_formularza['kuz_haslo'] = $_POST['kuz_haslo'];
			$dane_z_formularza['kuz_haslo_powtorz'] = $_POST['kuz_haslo_powtorz'];
			$dane_z_formularza['kuz_rol_id'] = $_POST['kuz_rol_id'];
			
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			
			$puste_haslo = empty($_POST['kuz_haslo']) && empty($_POST['kuz_haslo_powtorz']);
			$bledy_walidacji_konto = $konto_uzytkownika_model->waliduj($_POST);
			//resetowanie błędów, ponieważ i tak to pole jest nieedytowalne
			$bledy_walidacji_konto['kuz_email'] = array();
			
			if($puste_haslo) {
				$bledy_walidacji_konto['kuz_haslo'] = array();
				$bledy_walidacji_konto['kuz_haslo_powtorz'] = array();
			}
			
			$bledy_walidacji = array_merge_recursive($bledy_walidacji_konto, $bledy_walidacji);
				
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				if(!$puste_haslo) {
					$konto_uzytkownika_model->zmien_haslo($_POST['kuz_haslo'], $_POST['kuz_email'], true);
					//mail???				
				}
				$konto_uzytkownika_model->aktualizuj_role($_POST['kuz_rol_id'], $_POST['kuz_email']);
				
				$this->model->aktualizuj_dane_uzytkownika($_POST, $uzytkownik['uzy_id']);
				
				Router::przekierowanie(array('controller' => 'Uzytkownicy'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	

	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Uzytkownicy'));
		}
		
		//usuwnanie użytkownika poprzez zamazywanie
		$current_date = date('Y-m-d H:i:s');
		$sql = "SELECT * FROM hotel_uzytkownicy 
				JOIN hotel_konta_uzytkownikow ON uzy_kuz_id = kuz_id
				WHERE uzy_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			$sql = "UPDATE hotel_konta_uzytkownikow SET 
						kuz_email = ?,
						kuz_status_konta = ?,
						kuz_data_usuniecia_konta = ?
					WHERE kuz_id = ?";
			$parametry = array(
				md5($result[0]['kuz_email']),
				'USUNIETE',
				$current_date,
				$result[0]['kuz_id']
			);
			Model::wykonaj_zapytanie_sql($sql, $parametry);
		
			$sql = "UPDATE hotel_uzytkownicy SET 
						uzy_imie = ?,
						uzy_nazwisko = ?,
						uzy_telefon_kontaktowy = ?
					WHERE uzy_id = ?";
			$parametry = array(
				md5($result[0]['uzy_imie']),
				md5($result[0]['uzy_nazwisko']),
				md5($result[0]['uzy_telefon_kontaktowy']),
				$id
			);
			Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			//jeżeli był gościem hotelowym to usuwanie jego danych
			$sql = "SELECT * FROM hotel_goscie_hotelowi WHERE gh_uzy_id = ?";
			$parametry = array($id);
			$gosc = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			if(!empty($gosc)) {
				$sql = "DELETE FROM hotel_goscie_hotelowi WHERE gh_uzy_id = ?";
				$parametry = array($id);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
			}
			
			//anulowanie rezerwacji, które mają zostać zrealizowane po dacie usuniecia uzytkownika
			$sql = "SELECT * FROM hotel_rezerwacja_stolika WHERE rs_data_rezerwacji > ? AND rs_uzy_id = ?";
			$parametry = array($current_date, $id);
			$rs = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			if(!empty($rs)) {
				foreach ($rs as $r) {
					$sql = "UPDATE hotel_rezerwacja_stolika SET 
								rs_anulowano = 1,
								rs_data_anulowania = ?
							WHERE rs_id = ?";
					$parametry = array($current_date, $r['rs_id']);
					Model::wykonaj_zapytanie_sql($sql, $parametry);
				}
			}
		
			$sql = "SELECT * FROM hotel_rezerwacja_pokoju WHERE 
						rp_data_od < ? AND 
						rp_data_do > ? AND 
						rp_uzy_id = ?";
			$parametry = array($current_date, $id);
			$rp = Model::wykonaj_zapytanie_sql($sql, $parametry);
				
			if(!empty($rp)) {
				foreach ($rp as $r) {
					$sql = "UPDATE hotel_rezerwacja_pokoju SET
								rp_anulowano = 1,
								rp_data_anulowania = ?
							WHERE rs_id = ?";
					$parametry = array($current_date, $r['rp_id']);
					Model::wykonaj_zapytanie_sql($sql, $parametry);
				}
			}		
					
			//wyślij majla z potwierdzeniem usunięcia konta
			Mail::wyslij_mail_usuniecie_konta($result[0]['kuz_email']);
			
			
			Session::ustaw_alert('success', 'Poprawnie usunięto użytkownika oraz anulowano jego wszystkie rezerwacje');
		}
		else {
			Session::ustaw_alert('error', 'Użytkownik o takim identyfikatorze nie istnieje');
		}
		Router::przekierowanie(array('controller' => 'Uzytkownicy'));
	}
	
	public function aktywuj() {
		if(!isset($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Uzytkownicy'));
		}
		$id = $_GET['id'];
		
		$this->model->aktywuj_konto($id);
		
		Router::przekierowanie(array('controller' => 'Uzytkownicy'));
	}
	
}