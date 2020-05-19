<?php
class KontaktyController {
	
	public function lista() {
		View::ustaw('tytul_strony', 'Wiadomości');
		View::ustaw('podtytul_strony', 'Lista wiadomości od użytkowników');
		
		$kategorie = $this->model->pobierz_kategorie();
		View::ustaw('kategorie', $kategorie);
		
		
		
		$kontakty = $this->model->pobierz_wszystkie_kontakty();
		
		//$kontakty = $this->model->pobierz_kontakty(5);
		View::ustaw('kontakty', $kontakty);
	}
	
	public function wyswietl() {
		View::ustaw('tytul_strony', 'Wiadomości');
		View::ustaw('podtytul_strony', 'Wyświetl wiadomość');
		
		$id = $_GET['id'];
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Kontakty'));
		}
		View::ustaw('kon_id', $id);
		
		$kontakt = $this->model->pobierz_kontakt($id);
		if(empty($kontakt)) {
			Session::ustaw_alert('error', 'Kontakt o takim identyfikatorze nie istnieje');
			Router::przekierowanie(array('controller' => 'Kontakty'));
		}
		
		View::ustaw('dane_do_formularza', $kontakt[0]);
	
		$obecna_data = date('Y-m-d H:i:s');
		$sql = "UPDATE hotel_kontakty SET kon_data_przeczytania = ? WHERE kon_id = ?";
		$parametry = array($obecna_data, $id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
	}
	
	public function odpowiedz() {
		View::ustaw('tytul_strony', 'Wiadomości');
		View::ustaw('podtytul_strony', 'Odpowiedz na wiadomość');
		
		$id = $_GET['id'];
		if(empty($id) || !is_numeric($id)) {
			Router::przekierowanie(array('controller' => 'Kontakty'));
		}
		
		
		
		
	}
	
	
}