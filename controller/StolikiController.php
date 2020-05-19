<?php
class StolikiController {
	public function lista() {
		View::ustaw('tytul_strony', 'Stoliki');
		View::ustaw('podtytul_strony', 'Lista stolików');
		
		$sale_model = Model::zaladuj_model('SaleRestauracyjne');
		$wszystkie_sale = $sale_model->pobierz_wszystkie_sale();
		
		$sale_przetworzone = array();
		if(!empty($wszystkie_sale)) {
			foreach ($wszystkie_sale as $sala) {
				$sale_przetworzone[$sala['sar_id']] = $sala['sar_nazwa'];
			}
		}
		
		View::ustaw('wszystkie_sale', $sale_przetworzone);
		
		
		$wszystkie_stoliki = $this->model->pobierz_wszystkie_stoliki();
		View::ustaw('stoliki', $wszystkie_stoliki);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Stoliki');
		View::ustaw('podtytul_strony', 'Dodaj stolik');
		
		$sale_model = Model::zaladuj_model('SaleRestauracyjne');
		$wszystkie_sale = $sale_model->pobierz_wszystkie_sale();
	
		if(empty($wszystkie_sale)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych sale restauracyjnych w systemie. Dodaj przynajmniej jedną salę restauracyjną');
			Router::przekierowanie(array('controller' => 'SaleRestauracyjne', 'action' => 'dodaj'));		
		}
		
		
		$przetworzone_sale = array();
		foreach ($wszystkie_sale as $sala) {
			$przetworzone_sale[$sala['sar_id']] = $sala['sar_nazwa'];
		}
		View::ustaw('wszystkie_sale', $przetworzone_sale);
		
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['stolikiFormularz']) && $_POST['stolikiFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_stoliki (sto_numer, sto_liczba_miejsc, sto_polozenie, sto_sar_id, sto_cena_netto) VALUES(?,?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Stoliki'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}

	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Stoliki');
		View::ustaw('podtytul_strony', 'Edytuj stolik');
		
		$id = $_GET['id'];
		View::ustaw('sto_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Stoliki'));
		}
		
		$sale_model = Model::zaladuj_model('SaleRestauracyjne');
		$wszystkie_sale = $sale_model->pobierz_wszystkie_sale();
		
		if(empty($wszystkie_sale)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych sale restauracyjnych w systemie. Dodaj przynajmniej jedną salę restauracyjną');
			Router::przekierowanie(array('controller' => 'SaleRestauracyjne', 'action' => 'dodaj'));
		}
		
		
		$przetworzone_sale = array();
		foreach ($wszystkie_sale as $sala) {
			$przetworzone_sale[$sala['sar_id']] = $sala['sar_nazwa'];
		}
		View::ustaw('wszystkie_sale', $przetworzone_sale);
		
		$sql = "SELECT * FROM hotel_stoliki WHERE sto_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$parametry_z_formularza = $this->model->pola;
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['stolikiFormularz']) && $_POST['stolikiFormularz'] == 'edytuj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_stoliki SET sto_numer = ?, sto_liczba_miejsc = ?, sto_polozenie = ?, sto_sar_id = ?, sto_cena_netto = ?
						WHERE sto_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Stoliki'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Stoliki'));
		}
		
		$sql = "SELECT * FROM hotel_rezerwacja_stolika WHERE rs_sto_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(empty($result)) {
			$sql = "DELETE FROM hotel_stoliki WHERE sto_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('error', 'Nie można usunąć elementu, ponieważ ten stolik jest częścią rezerwacji stolika');
		}
		Router::przekierowanie(array('controller' => 'Stoliki'));
	}
}