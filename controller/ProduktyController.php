<?php
class ProduktyController {
	public function lista() {
		View::ustaw('tytul_strony', 'Produkty');
		View::ustaw('podtytul_strony', 'Lista produktów');
		
		$wszystkie_produkty = $this->model->pobierz_wszystkie_produkty();
		View::ustaw('produkty', $wszystkie_produkty);
		
		$wszystkie_jednostki = $this->model->pobierz_wszystkie_jednostki();
		View::ustaw('wszystkie_jednostki', $wszystkie_jednostki);
		
		$grupy_prod_model = Model::zaladuj_model('GrupyProduktow');
		$wszystkie_grupy_produktow = $grupy_prod_model->pobierz_wszystkie_grupy_produktow();
		
		$przetworzone_grupy = array();
		foreach ($wszystkie_grupy_produktow as $grupa) {
			$przetworzone_grupy[$grupa['grpp_id']] = $grupa['grpp_nazwa'];
		}
		View::ustaw('wszystkie_grupy_produktow', $przetworzone_grupy);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Produkty');
		View::ustaw('podtytul_strony', 'Dodaj produkt');
		
		$grupy_prod_model = Model::zaladuj_model('GrupyProduktow');
		$wszystkie_grupy_produktow = $grupy_prod_model->pobierz_wszystkie_grupy_produktow();
		
		$przetworzone_grupy = array();
		foreach ($wszystkie_grupy_produktow as $grupa) {
			$przetworzone_grupy[$grupa['grpp_id']] = $grupa['grpp_nazwa'];
		}
		View::ustaw('wszystkie_grupy_produktow', $przetworzone_grupy);
		
		if(empty($wszystkie_grupy_produktow)) {
			Session::ustaw_alert('error', 'Nie można dodać produktu, ponieważ nie zdefiniowano przynajmniej grupy produktu');
			Router::przekierowanie(array('controller' => 'GrupyProduktow', 'action' => 'dodaj'));
		}
		
		$wszystkie_jednostki = $this->model->pobierz_wszystkie_jednostki();
		View::ustaw('wszystkie_jednostki', $wszystkie_jednostki);
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
	
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['produktyFormularz']) && $_POST['produktyFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_produkty (prod_nazwa, prod_opis, prod_ikona, prod_cena_jednostkowa_netto, prod_jednostka, prod_grpp_id) VALUES(?,?,?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Produkty'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Produkty');
		View::ustaw('podtytul_strony', 'Edytuj produkt');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('prod_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Produkty'));
		}
			
		$sql = "SELECT * FROM hotel_produkty WHERE prod_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		
		$grupy_prod_model = Model::zaladuj_model('GrupyProduktow');
		$wszystkie_grupy_produktow = $grupy_prod_model->pobierz_wszystkie_grupy_produktow();
		
		$przetworzone_grupy = array();
		foreach ($wszystkie_grupy_produktow as $grupa) {
			$przetworzone_grupy[$grupa['grpp_id']] = $grupa['grpp_nazwa'];
		}
		View::ustaw('wszystkie_grupy_produktow', $przetworzone_grupy);
		
		
		$wszystkie_jednostki = $this->model->pobierz_wszystkie_jednostki();
		View::ustaw('wszystkie_jednostki', $wszystkie_jednostki);
		
		if(!empty($_POST['produktyFormularz']) && $_POST['produktyFormularz'] == 'edytuj') {
		
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_produkty SET prod_nazwa = ?, prod_opis = ?, prod_ikona = ?, prod_cena_jednostkowa_netto = ?, prod_jednostka = ?, prod_grpp_id = ?
						WHERE prod_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Produkty'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Produkty'));
		}
		
		$sql = "SELECT * FROM hotel_potrawa_x_produkt WHERE potxprod_prod_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(empty($result)) {
			//usuwanie stanu magazynowego powiązanego z produktem oraz zdarzeń magazynowych 
			$sql = "SELECT * FROM hotel_stan_magazynu WHERE stm_prod_id = ?";
			$parametry = array($id);
			$stan = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			foreach($stan as $s) {
				//zdarzenia				
				$sql = "DELETE FROM hotel_zdarzenia_magazynowe WHERE zdm_stm_id = ?";
				$parametry = array($s['stm_id']);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
				
				$sql = "DELETE FROM hotel_stan_magazynu WHERE stm_id = ?";
				$parametry = array($s['stm_id']);
				Model::wykonaj_zapytanie_sql($sql, $parametry);
			}
			
			$sql = "DELETE FROM hotel_produkty WHERE prod_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}	
		else {
			Session::ustaw_alert('error', 'Nie można usunąć elementu, ponieważ istnieją już potrawy wykorzystujące dany produkt');
		}
		Router::przekierowanie(array('controller' => 'Produkty'));
	}
}