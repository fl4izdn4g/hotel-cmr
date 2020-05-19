<?php
class WyposazenieController {
	public function lista() {
		View::ustaw('tytul_strony', 'Wyposażenie');
		View::ustaw('podtytul_strony', 'Lista wyposażenia');
	
		$wyposazenie = $this->model->pobierz_wszystkie_wyposazenia();
	
		View::ustaw('wyposazenie', $wyposazenie);
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Wyposażenie');
		View::ustaw('podtytul_strony', 'Dodaj wyposażenie');
	
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
	
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
	
		if(!empty($_POST['wyposazenieFormularz']) && $_POST['wyposazenieFormularz'] == 'dodaj') {
	
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				var_dump($parametry_do_zapytania);
				$sql = "INSERT INTO hotel_wyposazenie (wyp_nazwa, wyp_opis, wyp_ikona) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
	
				Router::przekierowanie(array('controller' => 'Wyposazenie'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function  edytuj() {
		View::ustaw('tytul_strony', 'Wyposażenie');
		View::ustaw('podtytul_strony', 'Edytuj wyposażenie');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('wyp_id', $id);
	
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Wyposazenie'));
		}
			
		$sql = "SELECT * FROM hotel_wyposazenie WHERE wyp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
	
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
	
		if(!empty($_POST['wyposazenieFormularz']) && $_POST['wyposazenieFormularz'] == 'edytuj') {
	
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
	
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
	
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
	
				$sql = "UPDATE hotel_wyposazenie SET wyp_nazwa = ?, wyp_opis = ?, wyp_ikona = ?
						WHERE wyp_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Wyposazenie'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	
	
	}
	
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Wyposazenie'));
		}
		
		//usunięcie wiązania wyposazenia z grupami pokojow
		$sql = "SELECT * FROM hotel_grupa_pokoi_x_wyposazenie WHERE gxw_wyp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		if(!empty($result)) {
			$sql = "DELETE FROM hotel_grupa_pokoi_x_wyposazenie WHERE gxw_wyp_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
			
			Session::ustaw_alert('warning', 'Wraz z wyposażeniem usunięto jego powiązania z grupami pokoi');
		}

		$sql = "DELETE FROM hotel_wyposazenie WHERE wyp_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		Router::przekierowanie(array('controller' => 'Wyposazenie'));
	}
	
}