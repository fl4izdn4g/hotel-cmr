<?php
class RoleUzytkownikaController {
	private function sprawdz_kontekst_uzytkownika() {
		if(!isset($_GET['user_id'])) {
			Router::przekierowanie(array('Uzytkownicy'));
		}
	}
	
	public function lista() {
		$this->sprawdz_kontekst_uzytkownika();
	
	
	}
	
	public function dodaj() {
		$this->sprawdz_kontekst_uzytkownika();
	
	}
	
	public function edytuj() {
		$this->sprawdz_kontekst_uzytkownika();
	
	}
	
	public function usun() {
		$this->sprawdz_kontekst_uzytkownika();
	}
}