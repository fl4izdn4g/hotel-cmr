<?php
class ProduktyPotrawyController {
	
	private function sprawdz_kontekst_uzytkownika() {
		if(!isset($_GET['meal_id'])) {
			Session::ustaw_alert('warning', 'Widok "Produkty dla potraw" może być uruchamiany wyłącznie w kontekście potrawy');
			Router::przekierowanie(array('controller' => 'Potrawy'));
		}
		else {
			View::ustaw('meal_id', $_GET['meal_id']);
		}
	}
	
	private function pobierz_nazwa_potrawy($meal_id) {
		$sql = "SELECT pot_nazwa FROM hotel_potrawy WHERE pot_id = ?";
		$parametry = array($meal_id);
		$potrawa = Model::wykonaj_zapytanie_sql($sql, $parametry);
		return $potrawa[0]['pot_nazwa'];
	}
	
	public function lista() {
		$this->sprawdz_kontekst_uzytkownika();
		$meal_id = $_GET['meal_id'];
		$nazwa_potrawy = $this->pobierz_nazwa_potrawy($meal_id);
		View::ustaw('tytul_strony', 'Produkty dla potrawy');
		View::ustaw('podtytul_strony', 'Lista produktów dla '.$nazwa_potrawy);
		
		$produkty = $this->model->pobierz_produkty_potrawy($meal_id);
		View::ustaw('produkty', $produkty);
		
		$produkty_model = Model::zaladuj_model('Produkty');
		$jednostki = $produkty_model->pobierz_wszystkie_jednostki();
		View::ustaw('wszystkie_jednostki', $jednostki);
		
	}
	
	private function ustaw_wszystkie_produkty() {
		$produkty_model = Model::zaladuj_model('Produkty');
		$wszystkie_produkty = $produkty_model->pobierz_wszystkie_produkty();
		
		$przetworzone_produkty = array();
		foreach ($wszystkie_produkty as $p) {
			$przetworzone_produkty[$p['prod_id']] = $p['prod_nazwa'];
		}
		View::ustaw('wszystkie_produkty', $przetworzone_produkty);
	}
	
	public function dodaj() {
		$this->sprawdz_kontekst_uzytkownika();
	
		$this->sprawdz_kontekst_uzytkownika();
		$meal_id = $_GET['meal_id'];
		$nazwa_potrawy = $this->pobierz_nazwa_potrawy($meal_id);
		View::ustaw('tytul_strony', 'Produkty dla potrawy');
		View::ustaw('podtytul_strony', 'Dodaj produkt dla '.$nazwa_potrawy);
		
		$this->ustaw_wszystkie_produkty();
		
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['potrawyFormularz']) && $_POST['potrawyFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $meal_id;
		
				$sql = "INSERT INTO hotel_potrawa_x_produkt (potxprod_prod_id, potxprod_wykorzystywana_ilosc, potxprod_pot_id) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'ProduktyPotrawy', 'meal_id' => $meal_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	
	}
	
	public function edytuj() {
		$this->sprawdz_kontekst_uzytkownika();
	
		$this->sprawdz_kontekst_uzytkownika();
		$meal_id = $_GET['meal_id'];
		$nazwa_potrawy = $this->pobierz_nazwa_potrawy($meal_id);
		View::ustaw('tytul_strony', 'Produkty dla potrawy');
		View::ustaw('podtytul_strony', 'Edytuj produkt dla '.$nazwa_potrawy);
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('potxprod_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'ProduktyPotrawy', 'meal_id' => $meal_id));
		}
			
		$this->ustaw_wszystkie_produkty();
		
		
		$sql = "SELECT * FROM hotel_potrawa_x_produkt WHERE potxprod_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['potrawyFormularz']) && $_POST['potrawyFormularz'] == 'edytuj') {
		
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
		
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
		
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
		
				$sql = "UPDATE hotel_potrawa_x_produkt SET potxprod_prod_id = ?, potxprod_wykorzystywana_ilosc = ?
						WHERE potxprod_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'ProduktyPotrawy', 'meal_id' => $meal_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function usun() {
		$this->sprawdz_kontekst_uzytkownika();
	
		$meal_id = $_GET['meal_id'];
		
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'ProduktyPotrawy', 'meal_id' => $meal_id));
		}
		
		$sql = "SELECT * FROM hotel_potrawa_x_produkt WHERE potxprod_pot_id = ?";
		$parametry = array($menu_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(count($result) > 1) {
			$sql = "DELETE FROM hotel_potrawa_x_produkt WHERE potxprod_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('warning', 'Nie można usunąć produktu z potrawy. Potrawa musi składać się przynajmniej z jednego produktu');
		}
		
		Router::przekierowanie(array('controller' => 'ProduktyPotrawy', 'meal_id' => $meal_id));
	}
	
}