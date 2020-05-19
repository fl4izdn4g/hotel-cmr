<?php
class PotrawyController {
	public function lista() {
		View::ustaw('tytul_strony', 'Potrawy');
		View::ustaw('podtytul_strony', 'Lista potraw');
		
		$wszystkie_potrawy = $this->model->pobierz_wszystkie_potrawy();
		
		$przetworzone_potrawy = array();
		foreach ($wszystkie_potrawy as $potrawa) {
			$element = $potrawa;
			if(!empty($potrawa['pot_wegetarianska'])) {
				$element['pot_wegetarianska'] = 'tak';
			}
			else {
				$element['pot_wegetarianska'] = 'nie';
			}
				
			if(!empty($potrawa['pot_bezglutenowa'])) {
				$element['pot_bezglutenowa'] = 'tak';
			}
			else {
				$element['pot_bezglutenowa'] = 'nie';
			}
			
			
			$przetworzone_potrawy[] = $element;
		}	
	
		View::ustaw('potrawy', $przetworzone_potrawy);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Potrawy');
		View::ustaw('podtytul_strony', 'Dodaj potrawę');
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
	
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['potrawaFormularz']) && $_POST['potrawaFormularz'] == 'dodaj') {
		
			if(isset($_POST['pot_wegetarianska']) && $_POST['pot_wegetarianska'] == 'on') {
				$_POST['pot_wegetarianska'] = 1;
			}
			else {
				$_POST['pot_wegetarianska'] = 0;
			}
			
			if(isset($_POST['pot_bezglutenowa']) && $_POST['pot_bezglutenowa'] == 'on') {
				$_POST['pot_bezglutenowa'] = 1;
			}
			else {
				$_POST['pot_bezglutenowa'] = 0;
			}
						
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_potrawy (pot_nazwa, pot_zdjecie, pot_opis, pot_wegetarianska, pot_bezglutenowa) VALUES(?,?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Potrawy'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Potrawy');
		View::ustaw('podtytul_strony', 'Edytuj potrawę');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('pot_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Potrawy'));
		}
			
		$sql = "SELECT * FROM hotel_potrawy WHERE pot_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['potrawaFormularz']) && $_POST['potrawaFormularz'] == 'edytuj') {
			if(isset($_POST['pot_wegetarianska']) && $_POST['pot_wegetarianska'] == 'on') {
				$_POST['pot_wegetarianska'] = 1;
			}
			else {
				$_POST['pot_wegetarianska'] = 0;
			}
				
			if(isset($_POST['pot_bezglutenowa']) && $_POST['pot_bezglutenowa'] == 'on') {
				$_POST['pot_bezglutenowa'] = 1;
			}
			else {
				$_POST['pot_bezglutenowa'] = 0;
			}
			
			
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_potrawy SET pot_nazwa = ?, pot_zdjecie = ?, pot_opis = ?, pot_wegetarianska = ?, pot_bezglutenowa = ?
						WHERE pot_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Potrawy'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Potrawy'));
		}
		
		$sql = "DELETE FROM hotel_potrawa_x_produkt WHERE potxprod_pot_id = ?";
		$parametry = array($id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$sql = "DELETE FROM hotel_potrawy WHERE pot_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		Router::przekierowanie(array('controller' => 'Potrawy'));
	}
}