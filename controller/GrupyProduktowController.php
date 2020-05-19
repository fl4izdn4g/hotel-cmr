<?php
class GrupyProduktowController {
	public function lista() {
		View::ustaw('tytul_strony', 'Grupy produktów');
		View::ustaw('podtytul_strony', 'Lista grup');
	
		$grupy_produktow = $this->model->pobierz_wszystkie_grupy_produktow();
	
		View::ustaw('grupy_produktow', $grupy_produktow);
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Grupy produktów');
		View::ustaw('podtytul_strony', 'Dodaj grupę');
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['grupaFormularz']) && $_POST['grupaFormularz'] == 'dodaj') {

			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_grupy_produktow (grpp_nazwa, grpp_opis, grpp_ikona) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'GrupyProduktow'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function  edytuj() {
		View::ustaw('tytul_strony', 'Grupy produktów');
		View::ustaw('podtytul_strony', 'Edytuj grupę');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('grpp_id', $id);

		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'GrupyProduktow'));
		}
			
		$sql = "SELECT * FROM hotel_grupy_produktow WHERE grpp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['grupaFormularz']) && $_POST['grupaFormularz'] == 'edytuj') {
	
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			$reguly_walidacji = $this->model->walidacja;
	
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			
			
			if(!$czy_sa_bledy) {
				
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_grupy_produktow SET grpp_nazwa = ?, grpp_opis = ?, grpp_ikona = ?
						WHERE grpp_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'GrupyProduktow'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	

	}
	
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'GrupyProduktow'));
		}
	
		//zobacz czy są produkty podpięte pod grupę produktów
		$sql = "SELECT * FROM hotel_produkty WHERE prod_grpp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(empty($result)) {
			$sql = "DELETE FROM hotel_grupy_produktow WHERE grpp_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('error', 'Nie można usunąć grupy produktów, ponieważ istnieją produkty, które są podpięte pod tą grupę');
		}
		Router::przekierowanie(array('controller' => 'GrupyProduktow'));
	}
}