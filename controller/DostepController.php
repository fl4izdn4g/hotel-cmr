<?php
class DostepController {
	public function lista() {
	
		View::ustaw('tytul_strony', 'Dostęp');
		View::ustaw('podtytul_strony', 'Lista dostępów');
		
		$role_model = Model::zaladuj_model('Role');
		$wszystkie_role = $role_model->pobierz_wszystkie_role();
		
		$role_przetworzone = array();
		if(!empty($wszystkie_role)) {
			foreach ($wszystkie_role as $rola) {
				$role_przetworzone[$rola['rol_id']] = $rola['rol_nazwa'];
			}
		}
		
		View::ustaw('wszystkie_role', $role_przetworzone);
		
		$wszystkie_typy = $this->model->pobierz_typy_obiektow();
		View::ustaw('wszystkie_typy', $wszystkie_typy);
		
		$wszystkie_dostepy = $this->model->pobierz_wszystkie_dostepy();
		View::ustaw('dostepy', $wszystkie_dostepy);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Dostęp');
		View::ustaw('podtytul_strony', 'Dodaj dostęp');
		
		$role_model = Model::zaladuj_model('Role');
		$wszystkie_role = $role_model->pobierz_wszystkie_role();
		if(empty($wszystkie_role)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych ról w systemie. Dodaj przynajmniej jedną rolę');
			// dostep nie moze funkcjonowac bez rol
			Router::przekierowanie(array('controller' => 'Role', 'action' => 'dodaj'));
		}
		
		$role_przetworzone = array();
		if(!empty($wszystkie_role)) {
			foreach ($wszystkie_role as $rola) {
				$role_przetworzone[$rola['rol_id']] = $rola['rol_nazwa'];
			}
		}
		
		View::ustaw('wszystkie_role', $role_przetworzone);
		
		$wszystkie_typy = $this->model->pobierz_typy_obiektow();
		View::ustaw('wszystkie_typy', $wszystkie_typy);
		
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['dostepFormularz']) && $_POST['dostepFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				var_dump($parametry_do_zapytania);
				$sql = "INSERT INTO hotel_dostep (dos_typ, dos_obiekt, dos_rol_id) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Dostep'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Dostęp');
		View::ustaw('podtytul_strony', 'Edytuj dostęp');
		
		$parametry_z_formularza = $this->model->pola;
		
		$id = $_GET['id'];
		View::ustaw('dos_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Dostep'));
		}
			
		$role_model = Model::zaladuj_model('Role');
		$wszystkie_role = $role_model->pobierz_wszystkie_role();
		
		$role_przetworzone = array();
		if(!empty($wszystkie_role)) {
			foreach ($wszystkie_role as $rola) {
				$role_przetworzone[$rola['rol_id']] = $rola['rol_nazwa'];
			}
		}
		View::ustaw('wszystkie_role', $role_przetworzone);
		
		$wszystkie_typy = $this->model->pobierz_typy_obiektow();
		View::ustaw('wszystkie_typy', $wszystkie_typy);
		
		
		
		$sql = "SELECT * FROM hotel_dostep WHERE dos_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['dostepFormularz']) && $_POST['dostepFormularz'] == 'edytuj') {
		
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
		
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
		
				$sql = "UPDATE hotel_dostep SET dos_typ = ?, dos_obiekt = ?, dos_rol_id = ?
						WHERE dos_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Dostep'));
			}
		}
		
		
		
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Dostep'));
		}
		
		$sql = "DELETE FROM hotel_dostep WHERE dos_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		Session::ustaw_alert('success', 'Poprawnie usunięto dostęp z systemu');
		
		Router::przekierowanie(array('controller' => 'Dostep'));
	}
}