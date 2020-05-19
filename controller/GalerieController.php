<?php
class GalerieController {
	public function lista() {
		View::ustaw('tytul_strony', 'Galeria');
		View::ustaw('podtytul_strony', 'Lista galerii');
	
		$galerie = $this->model->pobierz_wszystkie_galerie();
	
		$przetworzone = array();
		foreach($galerie as $g) {
			$element = $g;
			if($g['gal_widoczna']) {
				$element['gal_widoczna'] = 'tak';
			}
			else {
				$element['gal_widoczna'] = 'nie';
			}
			
			$przetworzone[] = $element;
		}
		
		View::ustaw('galerie', $przetworzone);
	}
	
	
	public function dodaj() {
		View::ustaw('tytul_strony', 'Galeria');
		View::ustaw('podtytul_strony', 'Dodaj galerię');
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['galeriaFormularz']) && $_POST['galeriaFormularz'] == 'dodaj') {
			if(isset($_POST['gal_widoczna']) && $_POST['gal_widoczna'] == 'on') {
				$_POST['gal_widoczna'] = 1;
			}
			else {
				$_POST['gal_widoczna'] = 0;
			}
			
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$sql = "INSERT INTO hotel_galerie (gal_nazwa, gal_opis, gal_widoczna) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				
				$last_id = $this->model->ostatnie_id();
		
				Router::przekierowanie(array('controller' => 'Galerie', 'action' => 'edytuj', 'id' => $last_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	
	public function  edytuj() {
		View::ustaw('tytul_strony', 'Galeria');
		View::ustaw('podtytul_strony', 'Edytuj galerię');
		
		$parametry_z_formularza = $this->model->pola;
		$id = $_GET['id'];
		View::ustaw('gal_id', $id);

		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
			
		$sql = "SELECT * FROM hotel_galerie WHERE  gal_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $result[0]);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['galeriaFormularz']) && $_POST['galeriaFormularz'] == 'edytuj') {
			
			if(isset($_POST['gal_widoczna']) && $_POST['gal_widoczna'] == 'on') {
				$_POST['gal_widoczna'] = 1;
			}
			else {
				$_POST['gal_widoczna'] = 0;
			}
			
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
			
			$reguly_walidacji = $this->model->walidacja;
	
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			
			if(!$czy_sa_bledy) {
				
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				
				$sql = "UPDATE hotel_galerie SET gal_nazwa = ?, gal_opis = ?, gal_widoczna = ?
						WHERE gal_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Galerie'));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	

	}
	
	
	public function usun() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}

		//najpierw usuwanie wszystkich zdjęć z galerii
		$sql = "DELETE FROM hotel_zdjecia WHERE zdj_gal_id = ?";
		$parametry = array($id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$sql = "DELETE FROM hotel_galerie WHERE gal_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		Session::ustaw_alert('success', 'Usunięto galerię wraz ze zdjęciami');
		Router::przekierowanie(array('controller' => 'Galerie'));
	}
	
	public function galeria_ukryj() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
		
		$sql = "UPDATE * FROM hotel_galerie SET gal_widoczna = 0 WHERE gal_id = ?";
		$parametry = array($id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function galeria_pokaz() {
		$id = empty($_GET['id']) ? null : $_GET['id'];
		if(empty($id) && !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
	
		$sql = "UPDATE * FROM hotel_galerie SET gal_widoczna = 1 WHERE gal_id = ?";
		$parametry = array($id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function zdjecia() {
		
		if(!isset($_GET['gallery_id'])) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
		$id = $_GET['gallery_id'];
		View::ustaw('gallery_id', $id);
		
		$sql = "SELECT * FROM hotel_galerie WHERE gal_id = ?";
		$parametry = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		View::ustaw('tytul_strony', 'Galeria');
		View::ustaw('podtytul_strony', 'Zdjęcia w galerii '.$result[0]['gal_nazwa']);
		
		$sql = "SELECT * FROM hotel_zdjecia WHERE zdj_gal_id = ?";
		$parametry = array($id);
		$zdjecia = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('zdjecia', $zdjecia);
	}
	
	public function zdjecie_edytuj() {
		if(!isset($_GET['gallery_id'])) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
		$galeria_id = $_GET['gallery_id'];
		View::ustaw('gallery_id', $galeria_id);
		
		if(!isset($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Galerie', 'action' => 'zdjecia', 'gallery_id' => $galeria_id));
		}
		$id = $_GET['id'];
		View::ustaw('id', $id);
			
		$sql = "SELECT * FROM hotel_zdjecia WHERE zdj_id = ?";
		$parametry = array($id);
		$zdjecie = Model::wykonaj_zapytanie_sql($sql, $parametry);
		View::ustaw('dane_do_formularza', $zdjecie[0]);
		
		$zdjecia_model = Model::zaladuj_model('Zdjecia');
		$parametry_z_formularza = $zdjecia_model->pola;
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['zdjecieFormularz']) && $_POST['zdjecieFormularz'] == 'edytuj') {
				
			$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
			View::ustaw('dane_do_formularza', $dane_z_formularza);
				
			$reguly_walidacji = $zdjecia_model->walidacja;
		
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
				
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
		
				$sql = "UPDATE hotel_zdjecia SET zdj_tytul = ?, zdj_plik = ?
						WHERE zdj_id = ?";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				Router::przekierowanie(array('controller' => 'Galerie', 'action' => 'zdjecia', 'gallery_id' => $galeria_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
		
		
	}
	
	public function zdjecie_dodaj() {
		if(!isset($_GET['gallery_id'])) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
		$gallery_id = $_GET['gallery_id'];
		View::ustaw('gallery_id', $gallery_id);
		
		$sql = "SELECT * FROM hotel_galerie WHERE gal_id = ?";
		$parametry = array($gallery_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		View::ustaw('tytul_strony', 'Galeria');
		View::ustaw('podtytul_strony', 'Dodaj zdjęcie do galerii '.$result[0]['gal_nazwa']);
		
		$zdjecia_model = Model::zaladuj_model('Zdjecia');
		$parametry_z_formularza = $zdjecia_model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
		
		if(!empty($_POST['zdjecieFormularz']) && $_POST['zdjecieFormularz'] == 'dodaj') {
				
			$reguly_walidacji = $zdjecia_model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $gallery_id;
				$sql = "INSERT INTO hotel_zdjecia (zdj_tytul, zdj_plik, zdj_gal_id) VALUES(?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
		
				Router::przekierowanie(array('controller' => 'Galerie', 'action' => 'zdjecia', 'gallery_id' => $gallery_id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
		}
	}
	
	public function zdjecie_usun() {
		if(!isset($_GET['gallery_id'])) {
			Router::przekierowanie(array('controller' => 'Galerie'));
		}
		$galeria_id = $_GET['gallery_id'];
		View::ustaw('gallery_id', $galeria_id);
		
		if(!isset($_GET['id'])) {
			Router::przekierowanie(array('controller' => 'Galerie', 'action' => 'zdjecia', 'gallery_id' => $galeria_id));
		}
		$id = $_GET['id'];
		
		$sql = "DELETE FROM hotel_zdjecia WHERE zdj_id = ?";
		$p = array($id);
		Model::wykonaj_zapytanie_sql($sql, $p);
		
		Router::przekierowanie(array('controller' => 'Galerie', 'action' => 'zdjecia', 'gallery_id' => $galeria_id));
	}
	

	
}