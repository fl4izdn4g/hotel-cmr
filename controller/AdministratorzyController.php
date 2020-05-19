<?php
class AdministratorzyController {
	public function lista() {
		View::ustaw('tytul_strony', 'Administratorzy');
		View::ustaw('podtytul_strony', 'Lista administratorów systemu');
		
		$admnistratorzy = $this->model->pobierz_administratorow();
		View::ustaw('administratorzy', $admnistratorzy);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Administratorzy');
		View::ustaw('podtytul_strony', 'Dodaj administratora systemu');
		
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(empty($user_id)) {
			Router::przekierowanie(array('controller' => 'Admninistratorzy'));
		}
		View::ustaw('user_id', $user_id);

		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['admnistratorFormularz']) && $_POST['admnistratorFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $user_id;
				$sql = "INSERT INTO hotel_administratorzy (adm_stanowisko, adm_zdjecie, adm_uzy_id) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
			
				Router::przekierowanie(array('controller' => 'Administratorzy'));
				
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Administratorzy');
		View::ustaw('podtytul_strony', 'Edytuj administratora systemu');
	
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('adm_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Administratorzy'));
		}
			
		$sql = "SELECT * FROM hotel_administratorzy WHERE adm_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
	
		if(!empty($_POST['admnistratorFormularz']) && $_POST['admnistratorFormularz'] == 'edytuj') {
	
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
	
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				$sql = "UPDATE hotel_administratorzy SET adm_stanowisko = ?, 
														 adm_zdjecie = ?
						WHERE adm_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
					
				Router::przekierowanie(array('controller' => 'Administratorzy'));
	
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Administratorzy'));
		}
			
		$sql = "DELETE FROM hotel_administratorzy WHERE adm_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
		Router::przekierowanie(array('controller' => 'Administratorzy'));
	}
	
}