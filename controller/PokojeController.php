<?php
class PokojeController {
	
	public function lista() {
		View::ustaw('tytul_strony', 'Pokoje');
		View::ustaw('podtytul_strony', 'Lista pokoi');
		
		$sql = 'SELECT * FROM hotel_pokoje';
		$pokoje = Model::wykonaj_zapytanie_sql($sql);
		
		$sql = 'SELECT * FROM hotel_grupy_pokoi';
		$grupy_pokoi = Model::wykonaj_zapytanie_sql($sql);
		$przetworzone_grupy = array();
		foreach ($grupy_pokoi as $grupa) {
			$przetworzone_grupy[$grupa['grp_id']] = $grupa['grp_nazwa'];
		}
		View::ustaw('wszystkie_grupy', $przetworzone_grupy);
				
		
		View::ustaw('pokoje', $pokoje);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Pokoje');
		View::ustaw('podtytul_strony', 'Dodaj pokój');
		
		$grupy_model = Model::zaladuj_model('GrupyPokoi');
		$wszystkie_grupy_pokoi = $grupy_model->pobierz_wszystkie_grupy_pokoi();
		
		if(empty($wszystkie_grupy_pokoi)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych grup pokoi. Dodaj przynajmniej jedną, aby uzyskać możliwość dodawania pokoi');
			Router::przekierowanie(array('controller' => 'GrupyPokoi', 'action' => 'dodaj'));
		}
		
		$przetworzone_grupy = array();
		foreach ($wszystkie_grupy_pokoi as $grupa) {
			$przetworzone_grupy[$grupa['grp_id']] = $grupa['grp_nazwa'];
		}
		View::ustaw('wszystkie_grupy_pokoi', $przetworzone_grupy);
		
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['pokojFormularz']) && $_POST['pokojFormularz'] == 'dodaj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_pokoje (pok_numer, pok_liczba_osob, pok_pietro, pok_zdjecie, pok_grp_id) VALUES(?,?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Pokoje'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Pokoje');
		View::ustaw('podtytul_strony', 'Edytuj pokój');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('pok_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Pokoje'));
		}
		
		$grupy_model = Model::zaladuj_model('GrupyPokoi');
		$wszystkie_grupy_pokoi = $grupy_model->pobierz_wszystkie_grupy_pokoi();
		
		if(empty($wszystkie_grupy_pokoi)) {
			Session::ustaw_alert('warning', 'Brak zdefiniowanych grup pokoi. Dodaj przynajmniej jedną, aby uzyskać możliwość dodawania pokoi');
			Router::przekierowanie(array('controller' => 'GrupyPokoi', 'action' => 'dodaj'));
		}
		
		$przetworzone_grupy = array();
		foreach ($wszystkie_grupy_pokoi as $grupa) {
			$przetworzone_grupy[$grupa['grp_id']] = $grupa['grp_nazwa'];
		}
		View::ustaw('wszystkie_grupy_pokoi', $przetworzone_grupy);
		
		$sql = "SELECT * FROM hotel_pokoje WHERE pok_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['pokojFormularz']) && $_POST['pokojFormularz'] == 'edytuj') {
		
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			
			if(!$czy_sa_bledy) {
				
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
 
				$sql = "UPDATE hotel_pokoje SET pok_numer = ?, pok_liczba_osob = ?, pok_pietro = ?, pok_zdjecie = ?, pok_grp_id = ?
						WHERE pok_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Pokoje'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Pokoje'));
		}
		
		//sprawdz rezerwacje...
		//jeżeli nie ma aktywnych rezerwacji na ten pokój to można usunąć
		$rezerwacje_model = Model::zaladuj_model('Rezerwacje');
		$aktywne_rezerwacje = $rezerwacje_model->pobierz_rezerwacje_dla_pokoju($id);
		
		if(empty($aktywne_rezerwacje)) {
			$sql = "DELETE FROM hotel_pokoje WHERE pok_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('error', 'Istnieją rezerwacje, które zawierają wybrany pokój. Usuwanie niemożliwe.');	
		}
		
		Router::przekierowanie(array('controller' => 'Pokoje'));
	}
}