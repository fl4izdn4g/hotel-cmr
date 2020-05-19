<?php
class GrupyPokoiController {
	public function lista() {
		View::ustaw('tytul_strony', 'Grupy pokoi');
		View::ustaw('podtytul_strony', 'Lista grup');
	
		$grupy_pokoi = $this->model->pobierz_wszystkie_grupy_pokoi();
	
		View::ustaw('grupy_pokoi', $grupy_pokoi);
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Grupy pokoi');
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
				$sql = "INSERT INTO hotel_grupy_pokoi (grp_nazwa, grp_cena_netto, grp_opis, grp_ikona) VALUES(?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				
				$last_id = $this->model->ostatnie_id();
		
				Router::przekierowanie(array('controller' => 'GrupyPokoi', 'action' => 'edytuj', 'id' => $last_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function  edytuj() {
		View::ustaw('tytul_strony', 'Grupy pokoi');
		View::ustaw('podtytul_strony', 'Edytuj grupę');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('grp_id', $id);

		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'GrupyPokoi'));
		}
			
		$sql = "SELECT * FROM hotel_grupy_pokoi WHERE grp_id = ?";
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
				
				$sql = "UPDATE hotel_grupy_pokoi SET grp_nazwa = ?, grp_cena_netto = ?, grp_opis = ?, grp_ikona = ?
						WHERE grp_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'GrupyPokoi'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	

	}
	
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'GrupyPokoi'));
		}
		
		$sql = "SELECT * FROM hotel_pokoje WHERE pok_grp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		if(!empty($result)) {
			Session::ustaw_alert('error', 'Nie można usunąć wybranej grupy pokoi, ponieważ istnieją pokoje, które są przypisane do tej grupy');
			Router::przekierowanie(array('controller' => 'Pokoje'));
		}
		
	
		$sql = "DELETE FROM hotel_grupy_pokoi WHERE grp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		Router::przekierowanie(array('controller' => 'GrupyPokoi'));
	}
}