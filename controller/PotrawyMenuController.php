<?php
class PotrawyMenuController {
	private function sprawdz_kontekst_uzytkownika() {
		if(!isset($_GET['menu_id'])) {
			Session::ustaw_alert('warning', 'Widok "Potrawy w menu" może być uruchamiany wyłącznie w kontekście menu');
			Router::przekierowanie(array('controller' => 'Menu'));
		}
		else {
			View::ustaw('menu_id', $_GET['menu_id']);
		}
	}
	
	private function pobierz_nazwa_menu($menu_id) {
		$sql = "SELECT men_nazwa FROM hotel_menu WHERE men_id = ?";
		$parametry = array($menu_id);
		$grupa = Model::wykonaj_zapytanie_sql($sql, $parametry);
		return $grupa[0]['men_nazwa'];
	}
	
	public function lista() {
		$this->sprawdz_kontekst_uzytkownika();
		$menu_id = $_GET['menu_id'];
		$nazwa_menu = $this->pobierz_nazwa_menu($menu_id);	
		View::ustaw('tytul_strony', 'Potrawy dla menu');
		View::ustaw('podtytul_strony', 'Lista potraw dla '.$nazwa_menu);
		
		$potrawy = $this->model->pobierz_potrawy_dla_menu($menu_id);
		View::ustaw('potrawy', $potrawy);
	}
	
	public function dodaj() {
		$this->sprawdz_kontekst_uzytkownika();
		$menu_id = $_GET['menu_id'];
		$nazwa_menu = $this->pobierz_nazwa_menu($menu_id);	
		View::ustaw('tytul_strony', 'Potrawy dla menu');
		View::ustaw('podtytul_strony', 'Dodaj potrawę dla '.$nazwa_menu);
		
		$potrawy_model = Model::zaladuj_model('Potrawy');
		$wszystkie_potrawy = $potrawy_model->pobierz_wszystkie_potrawy();
		
		$przetworzone_potrawy = array();
		foreach ($wszystkie_potrawy as $p) {
			$przetworzone_potrawy[$p['pot_id']] = $p['pot_nazwa'];
		}
		View::ustaw('wszystkie_potrawy', $przetworzone_potrawy);
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['potrawaFormularz']) && $_POST['potrawaFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $menu_id;
				
				$sql = "INSERT INTO hotel_menu_x_potrawa (menxpot_pot_id, menxpot_cena_netto, menxpot_men_id) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'PotrawyMenu', 'menu_id' => $menu_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		$this->sprawdz_kontekst_uzytkownika();
		$menu_id = $_GET['menu_id'];
		$nazwa_menu = $this->pobierz_nazwa_menu($menu_id);
		View::ustaw('tytul_strony', 'Potrawy dla menu');
		View::ustaw('podtytul_strony', 'Edytuj potrawę dla '.$nazwa_menu);
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('menxpot_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'PotrawyMenu', 'menu_id' => $menu_id));
		}
			
		$potrawy_model = Model::zaladuj_model('Potrawy');
		$wszystkie_potrawy = $potrawy_model->pobierz_wszystkie_potrawy();
		
		$przetworzone_potrawy = array();
		foreach ($wszystkie_potrawy as $p) {
			$przetworzone_potrawy[$p['pot_id']] = $p['pot_nazwa'];
		}
		View::ustaw('wszystkie_potrawy', $przetworzone_potrawy);
		
		$sql = "SELECT * FROM hotel_menu_x_potrawa WHERE menxpot_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['potrawaFormularz']) && $_POST['potrawaFormularz'] == 'edytuj') {
		
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
		
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
		
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
		
				$sql = "UPDATE hotel_menu_x_potrawa SET menxpot_pot_id = ?, menxpot_cena_netto = ?
						WHERE menxpot_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'PotrawyMenu', 'menu_id' => $menu_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function usun() {
		$this->sprawdz_kontekst_uzytkownika();
		
		$menu_id = $_GET['menu_id'];
		
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'PotrawyMenu', 'menu_id' => $menu_id));
		}
		
		$sql = "SELECT * FROM hotel_menu_x_potrawa WHERE menxpot_men_id = ?";
		$parametry = array($menu_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(count($result) > 1) {
			$sql = "DELETE FROM hotel_menu_x_potrawa WHERE menxpot_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('warning', 'Nie można usunąć potrawy z menu. Menu musi posiadać przynajmniej jedną potrawę');
		}
		Router::przekierowanie(array('controller' => 'PotrawyMenu', 'menu_id' => $menu_id));
	}
}