<?php
class LoginController {
	
	public $without_template = true;
	
	public function login() {
		View::ustaw('body_class', 'hold-transition login-page');
		
		$zalogowany = Session::pobierz_zalogownego_uzytkownika();
		if(!empty($zalogowany)) {
			$link_powrotu = $this->pobierz_link_powrotu($zalogowany['rol_kod']);
			Router::przekierowanie($link_powrotu);
		}
		
		if(isset($_GET['return'])) {
			View::ustaw('return', $_GET['return']);
		}
				
		if(isset($_POST['logowanie']) && $_POST['logowanie'] == 'login') {
			if(empty($_POST['email']) || empty($_POST['password'])) {
				$this->ustaw_link_powrotu();
				
				Session::ustaw_alert('error', 'Nieprawidłowy login lub hasło');
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'login'));
			}
			$email = $_POST['email'];
			$haslo = $_POST['password'];
			
			$sql = "SELECT * FROM hotel_konta_uzytkownikow
					JOIN hotel_role ON kuz_rol_id = rol_id
					JOIN hotel_uzytkownicy ON uzy_kuz_id = kuz_id
					JOIN hotel_administratorzy ON adm_uzy_id = uzy_id
					WHERE kuz_email = ? AND kuz_status_konta = 'AKTYWNE'";
			$parametry = array($email);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
			if(empty($result)) {
				//$this->ustaw_link_powrotu();
				Session::ustaw_alert('error', 'Nieprawidłowy login lub hasło');
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'login'));
			}
			
			$uzytkownik = $result[0];
		
			$haslo_wpisane = sha1($haslo.$uzytkownik['kuz_haslo_sekret']);
			
			if($haslo_wpisane == $uzytkownik['kuz_haslo']) {
				$user = array(
					'is_logged' => true,
					'name' => $uzytkownik['uzy_imie'],
					'surname' => $uzytkownik['uzy_nazwisko'],
					'id' => $uzytkownik['uzy_id'],
					'job' => $uzytkownik['adm_stanowisko'],
					'photo' => Router::poprawny_url_obrazka($uzytkownik['adm_zdjecie'], 'Administratorzy', 'small'),
					'registration_date' => $uzytkownik['kuz_data_rejestracji'],
					'role_code' => $uzytkownik['rol_kod']
				);
				
				Session::ustaw('logged_user_data', $user);
				
				//aktualizuj datę zalogowania użytkownika
				$sql = "UPDATE hotel_konta_uzytkownikow SET kuz_data_ostatniego_logowania = ? 
						WHERE kuz_id = ?";
				$parametry = array(
					date('Y-m-d H:i:s'),
					$uzytkownik['kuz_id']
				);
				
				$link_powrotu = $this->pobierz_link_powrotu($uzytkownik['rol_kod']);
				
				Session::usun('link_powrotu');
				Router::przekierowanie($link_powrotu);
			}
			else {
				Session::ustaw_alert('error', 'Nieprawidłowy login lub hasło');
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'login'));
			}
		}
		
	}
	
	private function pobierz_link_powrotu($rola) {
		if(strpos($rola, 'GOSC') !== false) {
			$link_powrotu = '/';
		}
		else {
			$link_powrotu = 'index.php?controller=Home';
		}
		if(isset($_GET['return'])) {
			$link_powrotu = Router::zdekoduj_link($_GET['return']);
		}
		
		return $link_powrotu;
	}
	
	public function logout() {
		$this->not_viewable = true;
		Session::usun_zalogowanego_uzytkownika();
		
		Router::przekierowanie(array('controller' => 'Login', 'action' => 'login'));
	}
	
	public function remind() {
		View::ustaw('body_class', 'hold-transition login-page');
		
		$this->model->walidacja_remind_email();
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(isset($_POST['przypomnij']) && $_POST['przypomnij'] == 'remind') {
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$sql = "SELECT * FROM hotel_konta_uzytkownikow WHERE kuz_email = ? AND kuz_status_konta <> 'USUNIETE'";
				$parametry = array($_POST['email']);
				$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
				if(empty($result)) {
					Session::ustaw_alert('error', 'Użytkownik o podanym adresie email nie istnieje w systemie');
					Router::przekierowanie(array('controller' => 'Login', 'action' => 'remind'));
				}
				
				$konto_model = Model::zaladuj_model('KontaUzytkownikow');
				$konto_model->wygeneruj_kod_resetujacy_haslo($result[0]['kuz_id']);
				Mail::wyslij_mail_zmiany_hasla($result[0]);
				
				Session::ustaw_alert('success', 'Wysłano link resetujący konto na podany adres email');
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'remind'));
			}
		}
	}
	
	public function remind_reset() {
		///pokaz formatkę z hasłem i ewentualnie zresetuj hasło
		//sprawdź czy przyszedł prawidłowy kod resetujacy
	}
	
	public function delete_account() {
		//usuwanie konta uzytkownika
	}
	
	public function register() {
		View::ustaw('body_class', 'hold-transition register-page');
		
		$typy_rejestracji = $this->model->pobierz_typy_rejestracji();
		View::ustaw('typy_rejestracji', $typy_rejestracji);
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['rejestracjaFormularz']) && $_POST['rejestracjaFormularz'] == 'register') {
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				
				$sql = "SELECT * FROM hotel_role WHERE rol_kod = ?";
				$parametry = array($_POST['rej_typ']);
				$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
				if(!empty($result)) {
				
					$konto_model = Model::zaladuj_model('KontaUzytkownikow');
					$konto = array(
						'email' => $_POST['rej_email'],
						'haslo' => $_POST['rej_haslo'],
						'rola' => $result[0]['rol_id']
					);
					
					$konto_id = $konto_model->dodaj_konto_uzytkownika($konto);
					if(!empty($konto_id)) {
						$uzytkownik_model = Model::zaladuj_model('Uzytkownik');
						$uzytkownik = array(
							'imie' => $_POST['rej_imie'],
							'nazwisko' => $_POST['rej_nazwisko']
						);
						$uzytkownik_id = $uzytkownik_model->dodaj_uzytkownika($uzytkownik, $konto_id);
						if(!empty($uzytkownik_id)) {
							Mail::wyslij_mail_aktywacyjny($uzytkownik_id);
							
							if($_POST['rej_typ'] == 'GOSC_HOTELU') {
								Router::przekierowanie(array('controller' => 'Login', 'action' => 'register_hotel', 'id' => $uzytkownik_id));
							}
							else if($_POST['rej_typ'] == 'GOSC_RESTAURACJA') {
								Router::przekierowanie(array('controller' => 'Login', 'action' => 'register_restaurant', 'id' => $uzytkownik_id));
							}
							else {
								Router::przekierowanie(array('controller' => 'Login', 'action' => 'register_meal', 'id' => $uzytkownik_id));
							}
						}
						else { 
							Session::ustaw_alert('error', 'Nie udało się zarejstrować użytkownika. Skontaktuj się z administratorem');
							Router::przekierowanie(array('controller' => 'Login', 'action' => 'register'));
						}
					}
					else {
						Session::ustaw_alert('error', 'Nie udało się zarejstrować użytkownika. Skontaktuj się z administratorem');
						Router::przekierowanie(array('controller' => 'Login', 'action' => 'register'));
					}
				}
				else {
					Session::ustaw_alert('error', 'Nie można dokończyć rejestracji, ponieważ w systemie nie istnieje taka rola użytkownika');
					Router::przekierowanie(array('controller' => 'Login', 'action' => 'register'));
				}
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function sprawdz_prawidlowa_kolejnosc_rejestracji(){ 
		if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'controller=Login&action=register') !== false ) {
			return true;
		}
		else {
			Session::ustaw_alert('error', 'Nie udało się dokończyć rejestracji. Proszę skontaktować się z administratorem');
			Router::przekierowanie(array('controller' => 'Login', 'action' => 'register'));
		}
	}
	
	public function register_hotel() {
		View::ustaw('body_class', 'hold-transition register-page');
		
		//zabezpieczenie refferer
		$this->sprawdz_prawidlowa_kolejnosc_rejestracji();
		
		//zabezpieczenie
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			Session::ustaw_alert('error', 'Nie udało się dokończyć rejestracji skontaktuj się z administratorem');
			Router::przekierowanie(array('controller', 'action' => 'register'));
		}
		$id = $_GET['id'];
		View::ustaw('user_id', $id);
		
		$goscie_model = Model::zaladuj_model('GoscieHotelowi');
		
		$typy = $goscie_model->pobierz_typy_dokumentow();
		View::ustaw('typy_dokumentow', $typy);
		
		$parametry_z_formularza = $goscie_model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['rejestracjaFormularz']) && $_POST['rejestracjaFormularz'] == 'register') {
			if(isset($_POST['gh_zagraniczny']) && $_POST['gh_zagraniczny'] == 'on') {
				$_POST['gh_zagraniczny'] = 1;
				$_POST['gh_pesel'] = '';
			}
			else {
				$_POST['gh_zagraniczny'] = 0;
			}
			
			$reguly_walidacji = $goscie_model->walidacja;
			
			if(!empty($_POST['gh_zagraniczny'])) {
				unset($reguly_walidacji['gh_pesel']);
			}
			
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				$sql = "INSERT INTO hotel_goscie_hotelowi (gh_pesel, gh_zagraniczny, gh_typ_dokumentu_tozsamosci, gh_numer_dokumentu_tozsamosci, gh_uzy_id) VALUES(?,?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'register_summary'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function register_restaurant() {
		View::ustaw('body_class', 'hold-transition register-page');
		
		//zabezpieczenie refferer
		$this->sprawdz_prawidlowa_kolejnosc_rejestracji();
		
		//zabezpieczenie
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			Session::ustaw_alert('error', 'Nie udało się dokończyć rejestracji skontaktuj się z administratorem');
			Router::przekierowanie(array('controller', 'action' => 'register'));
		}
		$id = $_GET['id'];
		View::ustaw('user_id', $id);
		
		$uzytkownicy_model = Model::zaladuj_model('Uzytkownicy');
		
		$parametry_z_formularza = array('uzy_telefon_kontaktowy');
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['rejestracjaFormularz']) && $_POST['rejestracjaFormularz'] == 'register') {
			$reguly_walidacji = array(
				'uzy_telefon_kontaktowy' => array(
					'nie_pusty',
					'telefon'
				)
			);
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$uzytkownicy_model->aktualizuj_telefon($_POST['uzy_telefon_kontaktowy'], $id);
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'register_summary'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function register_meal() {
		View::ustaw('body_class', 'hold-transition register-page');
		
		//zabezpieczenie refferer
		$this->sprawdz_prawidlowa_kolejnosc_rejestracji();
		
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			Session::ustaw_alert('error', 'Nie udało się dokończyć rejestracji skontaktuj się z administratorem');
			Router::przekierowanie(array('controller', 'action' => 'register'));
		}
		$id = $_GET['id'];
		View::ustaw('user_id', $id);
		
		$adresy_model = Model::zaladuj_model('Adresy');
		$wojewodztwa = $adresy_model->pobierz_wojewodztwa();
		View::ustaw('wojewodztwa', $wojewodztwa);
		
		$parametry_z_formularza = $adresy_model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['rejestracjaFormularz']) && $_POST['rejestracjaFormularz'] == 'register') {
			$reguly_walidacji = $adresy_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				//utworzenie adresu
				$adres_id = $adresy_model->dodaj_adres($_POST);
				
				//podpięcie adresu pod użytkownika jako domyślny
				$sql = "INSERT INTO hotel_uzytkownicy_x_adresy (uxa_uzy_id, uxa_adr_id, uxa_domyslny) VALUES(?,?,?)";
				$parametry = array($id, $adres_id, 1);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
				
				Router::przekierowanie(array('controller' => 'Login', 'action' => 'register_summary'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function register_summary() {
		View::ustaw('body_class', 'hold-transition register-page');
			
	}
	
	public function terms() {
		View::ustaw('body_class', 'hold-transition register-page');
				
	}
	
	public function activation() {
		View::ustaw('body_class', 'hold-transition register-page');
	
	}
	
	
	
}