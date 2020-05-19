<?php
class MenuController {
	public function lista() {
		View::ustaw('tytul_strony', 'Menu');
		View::ustaw('podtytul_strony', 'Lista menu');
		
		$wszystkie_menu = $this->model->pobierz_wszystkie_menu();
		
		$przetworzone_menu = array();
		foreach ($wszystkie_menu as $m) {
			$element = $m;
			if(!empty($m['men_czy_aktualne'])) {
				$element['men_czy_aktualne'] = 'tak';
			}
			else {
				$element['men_czy_aktualne'] = 'nie';
			}
			
			$przetworzone_menu[] = $element;
		}
		
		View::ustaw('menu', $przetworzone_menu);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Menu');
		View::ustaw('podtytul_strony', 'Dodaj menu');
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
	
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['menuFormularz']) && $_POST['menuFormularz'] == 'dodaj') {
		
			if(isset($_POST['men_czy_aktualne']) && $_POST['men_czy_aktualne'] == 'on') {
				$_POST['men_czy_aktualne'] = 1;
			}
			else {
				$_POST['men_czy_aktualne'] = 0;
			}
						
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_menu (men_nazwa, men_wazne_od, men_wazne_do, men_czy_aktualne) VALUES(?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Menu'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Menu');
		View::ustaw('podtytul_strony', 'Edytuj menu');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('men_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Menu'));
		}
			
		$sql = "SELECT * FROM hotel_menu WHERE men_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$przetworzone_result = $result[0];
		$przetworzone_result['men_wazne_od'] = date('Y-m-d', strtotime($przetworzone_result['men_wazne_od']));
		$przetworzone_result['men_wazne_do'] = date('Y-m-d', strtotime($przetworzone_result['men_wazne_do']));
		View::ustaw('dane_do_formularza', $przetworzone_result);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
				
		if(!empty($_POST['menuFormularz']) && $_POST['menuFormularz'] == 'edytuj') {
		
			if(isset($_POST['men_czy_aktualne']) && $_POST['men_czy_aktualne'] == 'on') {
				$_POST['men_czy_aktualne'] = 1;
			}
			else {
				$_POST['men_czy_aktualne'] = 0;
			}
			
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_menu SET men_nazwa = ?, men_wazne_od = ?, men_wazne_do = ?, men_czy_aktualne = ?
						WHERE men_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Menu'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Menu'));
		}
		//usunięcie wiązań z potrawami
		$sql = "DELETE FROM hotel_menu_x_potrawa WHERE menxpot_men_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$sql = "DELETE FROM hotel_menu WHERE men_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		Router::przekierowanie(array('controller' => 'Menu'));
	}
}