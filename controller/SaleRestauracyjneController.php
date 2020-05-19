<?php
class SaleRestauracyjneController {
	public function lista() {
		View::ustaw('tytul_strony', 'Sale restauracyjne');
		View::ustaw('podtytul_strony', 'Lista sal');
		
		$wszystkie_sale = $this->model->pobierz_wszystkie_sale();
		
		$przetworzone_sale = array();
		foreach ($wszystkie_sale as $sala) {
			$element = $sala;
			if(!empty($sala['sar_dla_palacych'])) {
				$element['sar_dla_palacych'] = 'tak';
			}
			else {
				$element['sar_dla_palacych'] = 'nie';
			}
			
			$przetworzone_sale[] = $element;
		}
		
		
		View::ustaw('sale', $przetworzone_sale);
	}
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Sale restauracyjne');
		View::ustaw('podtytul_strony', 'Dodaj salę');
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['saleFormularz']) && $_POST['saleFormularz'] == 'dodaj') {
			if(isset($_POST['sar_dla_palacych']) && $_POST['sar_dla_palacych'] == 'on') {
				$_POST['sar_dla_palacych'] = 1;
			}
			else {
				$_POST['sar_dla_palacych'] = 0;
			}
			
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_sale_restauracyjne (sar_nazwa, sar_opis, sar_zdjecie, sar_dla_palacych) VALUES(?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'SaleRestauracyjne'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function edytuj() {
		View::ustaw('tytul_strony', 'Sale restauracyjne');
		View::ustaw('podtytul_strony', 'Edytuj salę');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('sar_id', $id);
		
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'SaleRestauracyjne'));
		}
			
		$sql = "SELECT * FROM hotel_sale_restauracyjne WHERE sar_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['saleFormularz']) && $_POST['saleFormularz'] == 'edytuj') {
			if(isset($_POST['sar_dla_palacych']) && $_POST['sar_dla_palacych'] == 'on') {
				$_POST['sar_dla_palacych'] = 1;
			}
			else {
				$_POST['sar_dla_palacych'] = 0;
			}
			
			
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $this->model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				
				
			if(!$czy_sa_bledy) {
		
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
		
				$sql = "UPDATE hotel_sale_restauracyjne SET sar_nazwa = ?, sar_opis = ?, sar_zdjecie = ?, sar_dla_palacych = ?
						WHERE sar_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'SaleRestauracyjne'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'SaleRestauracyjne'));
		}
		
		//można tylko usunąć jeżeli nie ma stolików w danej grupie
		$sql = "SELECT * FROM hotel_stoliki WHERE sto_sar_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		if(empty($result)) {
			$sql = "DELETE FROM hotel_sale_restauracyjne WHERE sar_id = ?";
			$parametry = array($id);
			$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		}
		else {
			Session::ustaw_alert('error', 'Nie można usunąć sali restauracyjnej, ponieważ istnieją stoliki z nią powiązane');
		}
	
		Router::przekierowanie(array('controller' => 'SaleRestauracyjne'));
	}
}