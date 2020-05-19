<?php
class StanMagazynowyController {
	public function lista() {
		View::ustaw('tytul_strony', 'Stan magazynowy');
		View::ustaw('podtytul_strony', 'Lista magazynowa');
		
		$produkty_model = Model::zaladuj_model('Produkty');
		$produkty = $produkty_model->pobierz_wszystkie_produkty();
		
		$przetworzone_produkty = array();
		foreach ($produkty as $p) {
			$przetworzone_produkty[$p['prod_id']] = $p['prod_nazwa'];
		}
		View::ustaw('produkty', $przetworzone_produkty);
		

		
		
		$stany = $this->model->pobierz_stany_magazynowe();
		
		$przetworzone_stany = array();
		foreach ($stany as $s) {
			$element = $s;
			
			$granica = $s['stm_minimalny_dopuszczalny_stan'] * 0.05;
			
			if($s['stm_aktualny_stan'] > $s['stm_minimalny_dopuszczalny_stan'] + $granica) {
				$element['stm_ilosc_status'] = 'OK';
			}
			else if($s['stm_aktualny_stan'] < $s['stm_minimalny_dopuszczalny_stan'] + $granica && $s['stm_aktualny_stan'] > $s['stm_minimalny_dopuszczalny_stan']) {
				$element['stm_ilosc_status'] = 'WARNING';
			}
			else {
				$element['stm_ilosc_status'] = 'ERROR';
			}
			
			$przetworzone_stany[] = $element;
		}
		
		
		
		View::ustaw('stany', $przetworzone_stany);
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Stan magazynowy');
		View::ustaw('podtytul_strony', 'Dodaj produkt do magazynu');
		
		$produkty_model = Model::zaladuj_model('Produkty');
		$produkty = $produkty_model->pobierz_wszystkie_produkty();
		
		$przetworzone_produkty = array();
		foreach ($produkty as $p) {
			$przetworzone_produkty[$p['prod_id']] = $p['prod_nazwa'];
		}
		View::ustaw('produkty', $przetworzone_produkty);
		
		if(empty($produkty)) {
			Session::ustaw_alert('error', 'Nie można dodać stanu magazynowego, ponieważ nie zdefiniowano przynajmniej jednego produktu');
			Router::przekierowanie(array('controller'=> 'Produkty', 'action' => 'dodaj'));
		}
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['stanFormularz']) && $_POST['stanFormularz'] == 'dodaj') {
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = date('Y-m-d H:i:s');
				
				$sql = "INSERT INTO hotel_stan_magazynu (stm_prod_id,
														   stm_aktualny_stan,
														   stm_minimalny_dopuszczalny_stan,
														   stm_data_ostatniego_uzupelnienia) VALUES(?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
			
				Router::przekierowanie(array('controller' => 'StanMagazynowy'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Stan magazynowy');
		View::ustaw('podtytul_strony', 'Edytuj produkt w magazynie');
		
		$id = $_GET['id'];
		View::ustaw('stm_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'StanMagazynowy'));
		}
		
		$produkty_model = Model::zaladuj_model('Produkty');
		$produkty = $produkty_model->pobierz_wszystkie_produkty();
		
		$przetworzone_produkty = array();
		foreach ($produkty as $p) {
			$przetworzone_produkty[$p['prod_id']] = $p['prod_nazwa'];
		}
		View::ustaw('produkty', $przetworzone_produkty);
		
		
		$parametry_z_formularza = $this->model->pola;
		
		
		$sql = "SELECT * FROM hotel_stan_magazynu WHERE stm_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		array_shift($parametry_z_formularza);
		
		if(!empty($_POST['stanFormularz']) && $_POST['stanFormularz'] == 'edytuj') {
			$reguly_walidacji = $this->model->walidacja;
			unset($reguly_walidacji['stm_prod_id']);
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_stan_magazynu SET stm_aktualny_stan = ?,
												   	   stm_minimalny_dopuszczalny_stan = ?
						WHERE stm_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
					
				Router::przekierowanie(array('controller' => 'StanMagazynowy'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'StanMagazynowy'));
		}
	
		//usuniecie zdarzen powiazanych ze stanem magazynowym
		$sql = "DELETE FROM hotel_zdarzenia_magazynowe WHERE zdm_stm_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
				
		$sql = "DELETE FROM hotel_stan_magazynu WHERE stm_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		Router::przekierowanie(array('controller' => 'StanMagazynowy'));
	}
	
	public function zdarzenia() {
		$state_id = $_GET['state_id'];
		if(empty($state_id)) {
			Router::przekierowanie(array('controller' => 'StanMagazynowy'));
		}
		View::ustaw('state_id', $state_id);
		
		$stan_magazynowy = $this->model->pobierz_nazwe_produktu_dla_stanu_magazynowego($state_id);
				
		View::ustaw('tytul_strony', 'Zdarzenia magazynowe');
		View::ustaw('podtytul_strony', 'Lista zdarzeń magazynowych dla '.$stan_magazynowy['prod_nazwa']);
		
		$zdarzenia_model = Model::zaladuj_model('ZdarzeniaMagazynowe');
		
		$typy = $zdarzenia_model->pobierz_typy_zdarzen();
		View::ustaw('typy_zdarzen', $typy);

	
		$zdarzenia = $zdarzenia_model->pobierz_zdarzenia($state_id);
		
		$zdarzenia_przetworzone = array();
		foreach ($zdarzenia as $z) {
			$element = $z;
			$element['zdm_typ'] = $typy[$z['zdm_typ']];
			
			if($z['zdm_typ'] == 'POBRANIE') {
				$element['zdm_ilosc'] = '-'.$z['zdm_ilosc'];
			}
			else {
				$element['zdm_ilosc'] = '+'.$z['zdm_ilosc'];
			}
			
			$element['zdm_data_wystapienia'] = date('Y-m-d', strtotime($z['zdm_data_wystapienia']));
			
			$zdarzenia_przetworzone[] = $element;
		}
		View::ustaw('zdarzenia', $zdarzenia_przetworzone);
	}
	
	public function zdarzenie() {
		$state_id = $_GET['state_id'];
		if(empty($state_id)) {
			Router::przekierowanie(array('controller' => 'StanMagazynowy'));
		}
		View::ustaw('state_id', $state_id);
		
		$stan_magazynowy = $this->model->pobierz_nazwe_produktu_dla_stanu_magazynowego($state_id);
				
		View::ustaw('tytul_strony', 'Zdarzenia magazynowe');
		View::ustaw('podtytul_strony', 'Dodaj zdarzenie magazynowe dla '.$stan_magazynowy['prod_nazwa']);
		
		$zdarzenia_model = Model::zaladuj_model('ZdarzeniaMagazynowe');
		
		$typy = $zdarzenia_model->pobierz_typy_zdarzen();
		View::ustaw('typy_zdarzen', $typy);
		
		$parametry_z_formularza = $zdarzenia_model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['zdarzenieFormularz']) && $_POST['zdarzenieFormularz'] == 'zdarzenie') {
			$reguly_walidacji = $zdarzenia_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $state_id;
		
				$result = $zdarzenia_model->dodaj_zdarzenie_magazynowe($state_id, $_POST);
				if(!empty($result)) {
					if($_POST['zdm_typ'] == 'UZUPELNIENIE') {
						$ilosc = (int)$_POST['zdm_ilosc'];
						$data_aktualizacji = $_POST['zdm_data_wystapienia'];
					}
					else {
						$ilosc = (int)$_POST['zdm_ilosc'];
						$ilosc = -$ilosc;
						$data_aktualizacji = null;
					}
					
					$this->model->aktualizuj_stan_magazynowy($state_id, $ilosc, $data_aktualizacji);
				}
					
				Router::przekierowanie(array('controller' => 'StanMagazynowy', 'action' => 'zdarzenia', 'state_id' => $state_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
}