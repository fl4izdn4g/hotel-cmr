<?php
class KontaUzytkownikow {
	public $pola = array(
		'kuz_email',
		'kuz_haslo',
		'kuz_rol_id'
	);
	
	public $walidacja = array(
		'kuz_email' => array(
			'nie_pusty',
			'email',
			'pole_unikalne' => array(
				'pole' => 'kuz_email',
				'tabela' => 'hotel_konta_uzytkownikow'
			)
		),
		'kuz_haslo' => array(
			'nie_pusty',
			'dlugosc' => array(
				'min' => 8
			),
			'haslo_to_samo' => array(
				'pole' => 'kuz_haslo_powtorz'
			)
		),
		'kuz_haslo_powtorz' => array(
			'nie_pusty',
			'haslo_to_samo' => array(
				'pole' => 'kuz_haslo'
			)
		),
		'kuz_rol_id' => array(
			'nie_pusty',
		)
	);
	
	public function walidacja_uzytkownicy_rezerwacja() {
		$this->pola = array(
			'kuz_email',
		);
		
		$this->walidacja = array(
			'kuz_email' => array(
					'nie_pusty',
					'email',
					'pole_unikalne' => array(
							'pole' => 'kuz_email',
							'tabela' => 'hotel_konta_uzytkownikow'
					)
			),
		);
	}
	
	
	public function pobierz_konto_uzytkownika($konto_id) {
		$sql = "SELECT kuz_id, kuz_email, kuz_rol_id FROM hotel_konta_uzytkownikow WHERE kuz_id = ?";
		$parametry = array($konto_id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function dodaj_konto_uzytkownika_goscia($konto) {
		if(empty($konto['email']) || empty($konto['rola'])) {
			return false;
		}
	
		$email = $konto['email'];
		
		global $konfiguracja;
		$application_salt = $konfiguracja['password_salt'];
		$password = md5($application_salt.date('Y-m-d H:i:s').$application_salt);
	
		$current_date = date('Y-m-d H:i:s');
	
		$haslo_elementy = $this->przygotuj_haslo_sol($password, $email, $current_date);
	
	
		$konto_status = 'NOWE';
		$data_rejestracji = $current_date;
	
		$kod_aktywacyjny = sha1($email.$current_date);
		$wymus_zmiane_hasla = 1;
	
		$sql = "INSERT INTO hotel_konta_uzytkownikow (kuz_email, kuz_haslo, kuz_haslo_sekret, kuz_status_konta, kuz_data_rejestracji, kuz_data_modyfikacji_konta, kuz_kod_aktywacyjny, kuz_wymus_zmiane_hasla, kuz_rol_id)
				VALUES (?,?,?,?,?,?,?,?,?)";
		$parametry = array(
				$email,
				$haslo_elementy['haslo'],
				$haslo_elementy['sol'],
				$konto_status,
				$data_rejestracji,
				$current_date,
				$kod_aktywacyjny,
				$wymus_zmiane_hasla,
				$konto['rola']
		);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		$sql = "SELECT kuz_id FROM hotel_konta_uzytkownikow WHERE kuz_email = ? ORDER BY kuz_id DESC LIMIT 1";
		$parametry = array(
				$email
		);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
	
		if(!empty($result)) {
			$id = $result[0]['kuz_id'];
			return $id;
		}
	
		return false;
	}
	
	public function dodaj_konto_uzytkownika($konto) {
		if(empty($konto['email']) || empty($konto['haslo']) || empty($konto['rola'])) {
			return false;
		}
		
		$email = $konto['email'];
		$password = $konto['haslo'];
		
		$current_date = date('Y-m-d H:i:s');
		
		$haslo_elementy = $this->przygotuj_haslo_sol($password, $email, $current_date);
		
		
		$konto_status = 'NOWE';
		$data_rejestracji = $current_date;
		
		$kod_aktywacyjny = sha1($email.$current_date);
		$wymus_zmiane_hasla = 1;
				
		$sql = "INSERT INTO hotel_konta_uzytkownikow (kuz_email, kuz_haslo, kuz_haslo_sekret, kuz_status_konta, kuz_data_rejestracji, kuz_data_modyfikacji_konta, kuz_kod_aktywacyjny, kuz_wymus_zmiane_hasla, kuz_rol_id) 
				VALUES (?,?,?,?,?,?,?,?,?)";
		$parametry = array(
			$email,
			$haslo_elementy['haslo'],
			$haslo_elementy['sol'],
			$konto_status,
			$data_rejestracji,
			$current_date,
			$kod_aktywacyjny,
			$wymus_zmiane_hasla,
			$konto['rola']
		);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		$sql = "SELECT kuz_id FROM hotel_konta_uzytkownikow WHERE kuz_email = ? ORDER BY kuz_id DESC LIMIT 1";
		$parametry = array(
			$email
		);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		if(!empty($result)) {
			$id = $result[0]['kuz_id'];	
			return $id;
		}
		
		return false;
	}
	 
	private function przygotuj_haslo_sol($haslo, $email, $current_date) {
		global $konfiguracja;
		
		
		//przygotuj sol i haslo
		$sol = sha1($current_date.$haslo.$konfiguracja['password_salt'].$email);
		$haslo_sol = sha1($haslo.$sol);
		
		return array(
			'haslo' => $haslo_sol,
			'sol' => $sol
		);
	}
	
	
	public function zmien_haslo($nowe_haslo, $email, $wymus_zmiane_hasla) {
		$current_date = date('Y-m-d H:i:s');
		
		$haslo_elementy = $this->przygotuj_haslo_sol($nowe_haslo, $email, $current_date);
		$data_modyfkacji = $current_date;
		
		$zmana_hasla = '0';
		if(!empty($wymus_zmiane_hasla)) {
			$zmana_hasla = '1';
		}
		
		
		
		$sql = "UPDATE hotel_konta_uzytkownikow SET kuz_haslo = ?, 
													kuz_haslo_sekret = ?, 
													kuz_data_modyfikacji_konta = ?, 
													kuz_wymus_zmiane_hasla = ? 
				WHERE kuz_email = ?";
		
		$parametry = array(
			$haslo_elementy['haslo'],
			$haslo_elementy['sol'],
			$data_modyfkacji,
			$zmana_hasla,
			$email
		);
		
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function aktualizuj_role($rola_id, $email) {
		$current_date = date('Y-m-d H:i:s');
		
		$sql = "UPDATE hotel_konta_uzytkownikow SET kuz_rol_id = ?, kuz_data_modyfikacji_konta = ?
				WHERE kuz_email = ?";
	
		$parametry = array(
			$rola_id,
			$current_date,
			$email
		);
	
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function waliduj($post) {
		$pola_z_formularza = $this->pola;
		$pola_z_formularza[] = 'kuz_haslo_powtorz';
		return Model::sprawdz_poprawnosc_danych($pola_z_formularza, $this->walidacja);
	}
	
	public function ustaw_jako_aktywne($konto_id) {
		$sql = "UPDATE hotel_konta_uzytkownikow SET kuz_status_konta = 'AKTYWNE' WHERE kuz_id = ?";
		$parametry = array($konto_id);
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function wygeneruj_kod_resetujacy_haslo($id) {
		$current_date = date('Y-m-d H:i:s');
		global $konfiguracja;
		
		$sql = "SELECT * FROM hotel_konta_uzytkownikow WHERE kuz_id = ?";
		$p = array($id);
		$result = Model::wykonaj_zapytanie_sql($sql, $p);
		
		$kod_resetujacy = sha1($konfiguracja['password_salt'].$result['kuz_email'].$current_date);
		
		$sql = "UPDATE hotel_konta_uzytkownikow SET kuz_kod_resetujacy_haslo = ?, kuz_data_resetowania_hasla = ?
				WHERE kuz_id = ?";
		$p = array($kod_resetujacy, $current_date, $id);
		Model::wykonaj_zapytanie_sql($sql,$p);
	}
	
}