<?php
class WyposazenieGrupyPokoiController {
	private function sprawdz_kontekst_uzytkownika() {
		if(!isset($_GET['group_id'])) {
			Session::ustaw_alert('warning', 'Widok "Wyposażenie dla grupy" może być uruchamiany wyłącznie w kontekście grupy');
			Router::przekierowanie(array('controller' => 'GrupyPokoi'));
		}
		else {
			View::ustaw('group_id', $_GET['group_id']);
		}
	}
	
	private function pobierz_nazwa_grupy($group_id) {
		$sql = "SELECT grp_nazwa FROM hotel_grupy_pokoi WHERE grp_id = ?";
		$parametry = array($group_id);
		$grupa = Model::wykonaj_zapytanie_sql($sql, $parametry);
		return $grupa[0]['grp_nazwa'];
	}
	
	public function lista() {
		$this->sprawdz_kontekst_uzytkownika();
		$group_id = $_GET['group_id'];
		$nazwa_grupy = $this->pobierz_nazwa_grupy($group_id);	
		View::ustaw('tytul_strony', 'Wyposażenie dla grupy');
		View::ustaw('podtytul_strony', 'Lista wyposażenia dla '.$nazwa_grupy);
		
		$wyposazenie = $this->model->pobierz_wyposazenie_grupy_pokoi($group_id);
		View::ustaw('wyposazenie', $wyposazenie);
	}
	
	public function dodaj() {
		$this->sprawdz_kontekst_uzytkownika();
		$group_id = $_GET['group_id'];
		$nazwa_grupy = $this->pobierz_nazwa_grupy($group_id);	
		View::ustaw('tytul_strony', 'Wyposażenie dla grupy');
		View::ustaw('podtytul_strony', 'Dodaj wyposażenie dla '.$nazwa_grupy);
		
		$wyposazenie_model = Model::zaladuj_model('Wyposazenie');
		$wszystkie_wyposazenia = $wyposazenie_model->pobierz_wszystkie_wyposazenia();
		
		$przetworzone_wyposazenia = array();
		foreach ($wszystkie_wyposazenia as $w) {
			$przetworzone_wyposazenia[$w['wyp_id']] = $w['wyp_nazwa'];
		}
		View::ustaw('wszystkie_wyposazenia', $przetworzone_wyposazenia);
		
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
				
				$sql = "SELECT * FROM hotel_grupa_pokoi_x_wyposazenie 
						WHERE gxw_grp_id = ? AND gxw_wyp_id = ?";
				$parametry = array($group_id, $_POST['gxw_wyp_id']);
				$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
				if(empty($result)) {
					$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
					$parametry_do_zapytania[] = $group_id;
					
					$sql = "INSERT INTO hotel_grupa_pokoi_x_wyposazenie (gxw_wyp_id, gxw_ilosc_wyposazenia, gxw_grp_id) VALUES(?,?,?)";
					Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
							
				}
				else {
					Session::ustaw_alert('warning', 'Wyposażenie tego typu isnieje już na liście wyposażenia dla danej grupy pokoi');				
				}
				
				Router::przekierowanie(array('controller' => 'WyposazenieGrupyPokoi', 'group_id' => $group_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		$this->sprawdz_kontekst_uzytkownika();
		$group_id = $_GET['group_id'];
		$nazwa_grupy = $this->pobierz_nazwa_grupy($group_id);
		View::ustaw('tytul_strony', 'Wyposażenie dla grupy');
		View::ustaw('podtytul_strony', 'Edytuj wyposażenie dla '.$nazwa_grupy);
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('gxw_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'WyposazenieGrupyPokoi', 'group_id' => $group_id));
		}
			
		$wyposazenie_model = Model::zaladuj_model('Wyposazenie');
		$wszystkie_wyposazenia = $wyposazenie_model->pobierz_wszystkie_wyposazenia();
		
		$przetworzone_wyposazenia = array();
		foreach ($wszystkie_wyposazenia as $w) {
			$przetworzone_wyposazenia[$w['wyp_id']] = $w['wyp_nazwa'];
		}
		View::ustaw('wszystkie_wyposazenia', $przetworzone_wyposazenia);
		
		
		$sql = "SELECT * FROM hotel_grupa_pokoi_x_wyposazenie WHERE gxw_id = ?";
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
		
				$sql = "SELECT * FROM hotel_grupa_pokoi_x_wyposazenie 
						WHERE gxw_grp_id = ? AND gxw_wyp_id = ?";
				$parametry = array($group_id, $_POST['gxw_wyp_id']);
				$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
				if(empty($result)) {
					$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
					$parametry_do_zapytania[] = $id;
			
					$sql = "UPDATE hotel_grupa_pokoi_x_wyposazenie SET gxw_wyp_id = ?, gxw_ilosc_wyposazenia = ?
							WHERE gxw_id = ?";
					Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				}
				else {
					Session::ustaw_alert('warning', 'Wyposażenie tego typu isnieje już na liście wyposażenia dla danej grupy pokoi');				
				}
				Router::przekierowanie(array('controller' => 'WyposazenieGrupyPokoi', 'group_id' => $group_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function usun() {
		$this->sprawdz_kontekst_uzytkownika();
		
		$group_id = $_GET['group_id'];
		
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'WyposazenieGrupyPokoi', 'group_id' => $group_id));
		}
		
		$sql = "DELETE FROM hotel_grupa_pokoi_x_wyposazenie WHERE gxw_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		Router::przekierowanie(array('controller' => 'WyposazenieGrupyPokoi', 'group_id' => $group_id));
	}
}