<?php
class RoleController {
	public function lista() {
		View::ustaw('tytul_strony', 'Role');
		View::ustaw('podtytul_strony', 'Lista ról');
		
		$role = $this->model->pobierz_wszystkie_role();
		View::ustaw('role', $role);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Role');
		View::ustaw('podtytul_strony', 'Dodaj rolę');
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['roleFormularz']) && $_POST['roleFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_role (rol_nazwa, rol_opis, rol_kod) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Role'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Role');
		View::ustaw('podtytul_strony', 'Edytuj rolę');
		
		$parametry_z_formularza = $this->model->pola;
		
		$id = $_GET['id'];
		View::ustaw('rol_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Role'));
		}
		$dane_do_formularza = $this->model->pobierz_role($id);	
		View::ustaw('dane_do_formularza', $dane_do_formularza[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['roleFormularz']) && $_POST['roleFormularz'] == 'edytuj') {
		
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
		
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
		
				$sql = "UPDATE hotel_role SET rol_nazwa = ?, rol_opis = ?, rol_kod = ?
						WHERE rol_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Role'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Role'));
		}
		
		//sprawdź czy istnieją użytkownicy powiązani z rolą
		$sql = "SELECT * FROM hotel_konta_uzytkownikow WHERE kuz_rol_id = ? AND kuz_status_konta <> 'USUNIETY' ";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(empty($result)) {
			$sql = "DELETE FROM hotel_role WHERE rol_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('error', 'Nie można usunąć elementu, ponieważ w systemie istnieją użytkownicy przypisani do tej roli');
		}
		Router::przekierowanie(array('controller' => 'Role'));
	}
}