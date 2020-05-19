<?php
class GoscieHotelowiController {
	public function lista() {
		View::ustaw('tytul_strony', 'Goście hotelowi');
		View::ustaw('podtytul_strony', 'Lista gości hotelowych');
		
		$typy_dokumentow = $this->model->pobierz_typy_dokumentow();
		
		$goscie = $this->model->pobierz_gosci();
		$przetworzeni_goscie = array();
		foreach ($goscie as $g) {
			$element = $g;
			if(!empty($g['gh_zagraniczny'])) {
				$element['gh_zagraniczny'] = 'tak';
			}
			else {
				$element['gh_zagraniczny'] = 'nie';
			}
			if(!empty($g['gh_typ_dokumentu_tozsamosci'])) { 
				$element['gh_typ_dokumentu_tozsamosci'] = $typy_dokumentow[$g['gh_typ_dokumentu_tozsamosci']];
			}
			
			$przetworzeni_goscie[] = $element;
		}
		
		View::ustaw('goscie', $przetworzeni_goscie);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Goście hotelowi');
		View::ustaw('podtytul_strony', 'Dodaj gościa');
		
		$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
		if(empty($user_id)) {
			Router::przekierowanie(array('controller' => 'GoscieHotelowi'));
		}
		View::ustaw('user_id', $user_id);
		
		
		$typy = $this->model->pobierz_typy_dokumentow();
		View::ustaw('wszystkie_typy', $typy);
		

		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['goscFormularz']) && $_POST['goscFormularz'] == 'dodaj') {
			if(isset($_POST['gh_zagraniczny']) && $_POST['gh_zagraniczny'] == 'on') {
				$_POST['gh_zagraniczny'] = 1;
				$_POST['gh_pesel'] = '';
			}
			else {
				$_POST['gh_zagraniczny'] = 0;
			}
			
			$reguly_walidacji = $this->model->walidacja;
			
			if(!empty($_POST['gh_zagraniczny'])) {
				unset($reguly_walidacji['gh_pesel']);
			}
			
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $user_id;
				$sql = "INSERT INTO hotel_goscie_hotelowi (gh_pesel, gh_zagraniczny, gh_typ_dokumentu_tozsamosci, gh_numer_dokumentu_tozsamosci, gh_uzy_id) VALUES(?,?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
			
				Router::przekierowanie(array('controller' => 'GoscieHotelowi'));
				
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Goście hotelowi');
		View::ustaw('podtytul_strony', 'Edytuj gościa');
	
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'GoscieHotelowi'));
		}
		View::ustaw('gh_id', $id);
		
		$typy = $this->model->pobierz_typy_dokumentow();
		View::ustaw('wszystkie_typy', $typy);
				
			
		$sql = "SELECT * FROM hotel_goscie_hotelowi WHERE gh_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
	
		if(!empty($_POST['goscFormularz']) && $_POST['goscFormularz'] == 'edytuj') {
			if(isset($_POST['gh_zagraniczny']) && $_POST['gh_zagraniczny'] == 'on') {
				$_POST['gh_zagraniczny'] = 1;
				$_POST['gh_pesel'] = '';
			}
			else {
				$_POST['gh_zagraniczny'] = 0;
			}
				
			$reguly_walidacji = $this->model->walidacja;
				
			if(!empty($_POST['gh_zagraniczny'])) {
				unset($reguly_walidacji['gh_pesel']);
			}
				
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
		
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_goscie_hotelowi SET gh_pesel = ?,
														 gh_zagraniczny = ?,
														 gh_typ_dokumentu_tozsamosci = ?,
														 gh_numer_dokumentu_tozsamosci = ?
						WHERE gh_id = ?";
				
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
					
				Router::przekierowanie(array('controller' => 'GoscieHotelowi'));
		
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'GoscieHotelowi'));
		}
			
		$sql = "DELETE FROM hotel_goscie_hotelowi WHERE gh_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
		Router::przekierowanie(array('controller' => 'GoscieHotelowi'));
	}
	
}