<?php
class KontaktyOdpowiedzController {
	
	public function odpowiedz() {
		View::ustaw('tytul_strony', 'Odpowiedź');
		View::ustaw('podtytul_strony', 'Dodaj odpowiedźna wiadomość');
		
		$id = $_GET['id'];
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Kontakty'));
		}
		View::ustaw('kon_id', $id);
		
		$kontakt_model = Model::zaladuj_model('Kontakty');
		$kontakt = $kontakt_model->pobierz_kontakt($id);
		if(empty($kontakt)) {
			Session::ustaw_alert('error', 'Kontakt o takim identyfikatorze nie istnieje');
			Router::przekierowanie(array('controller' => 'Kontakty'));
		}
		$kontakt = $kontakt[0];
		
		
		
		$parametry_z_formularza = $this->model->pola;
		$dane_z_formularza = Model::przygotuj_dane_do_formularza($parametry_z_formularza);
		if(empty($dane_z_formularza['kodp_tytul'])) {
			$dane_z_formularza['kodp_tytul'] = 'Re: '.$kontakt['kon_tytul'];
		}
		
		View::ustaw('dane_do_formularza', $dane_z_formularza);
		
		$bledy_walidacji = Model::przygotuj_puste_bledy($parametry_z_formularza);
		View::ustaw('bledy_walidacji', $bledy_walidacji);
	
		if(!empty($_POST['odpowiedzFormularz']) && $_POST['odpowiedzFormularz'] == 'wyslij') {
			
			$reguly_walidacji = $this->model->walidacja;
			$bledy_walidacji = Model::sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji);
			$czy_sa_bledy = Model::czy_sa_bledy($bledy_walidacji);
			if(!$czy_sa_bledy) {
				$data_odpowiedz = date('Y-m-d H:i:s');
				//$admin_id = '';
				
				
				$parametry_do_zapytania = Model::przygotuj_parametry_do_zapytania($parametry_z_formularza);
				$parametry_do_zapytania[] = $id;
				$parametry_do_zapytania[] = $data_odpowiedz;
				//$parametry_do_zapytania[] = $admin_id;
				
				$sql = "INSERT INTO hotel_kontakt_odpowiedz (kodp_tytul, kodp_tresc, kodp_kon_id, kodp_data_odpowiedzi) VALUES(?,?,?,?)";
				Model::wykonaj_zapytanie_sql($sql, $parametry_do_zapytania);
				
				$sql = "SELECT kodp_id FROM hotel_kontakt_odpowiedz ORDER BY kodp_id DESC LIMIT 1";
				$result = Model::wykonaj_zapytanie_sql($sql);
				$odpowiedz_id = $result[0]['kodp_id'];
				
				Mail::wyslij_mail_odpowiedz_na_kontakt($odpowiedz_id);
				
				Session::ustaw_alert('success', 'Wysłano odpowiedź');
				Router::przekierowanie(array('controller' => 'Kontakty', 'action' => 'wyswietl', 'id' => $id));
			}
			else {
				View::ustaw('bledy_walidacji', $bledy_walidacji);
			}
			
			
		}
		
		
	}
		
}