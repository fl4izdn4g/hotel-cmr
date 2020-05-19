<?php
class Installation {
	
	private $role = array(
		array(
			'rol_kod' => 'ADMINISTRATOR_GLOWNY',
			'rol_nazwa' => 'Administrator',
			'rol_opis' => 'Główny administrator systemu'
		),	
		array(
			'rol_kod' => 'ADMINISTRATOR_RESTAURACJI',
			'rol_nazwa' => 'Administrator restauracji',
			'rol_opis' => 'Główny administrator restauracji'
		),
		array(
			'rol_kod' => 'ADMINISTRATOR_HOTELU',
			'rol_nazwa' => 'Administrator hotelu',
			'rol_opis' => 'Główny administrator hotelu'
		),
		array(
			'rol_kod' => 'GOSC_HOTELU',
			'rol_nazwa' => 'Gość hotelu',
			'rol_opis' => 'Gość hotelu'
		),
		array(
			'rol_kod' => 'GOSC_RESTAURACJI',
			'rol_nazwa' => 'Gość restauracji',
			'rol_opis' => 'Gość restauracji'
		),
	);
	
	private $admin_account = array(
		'uzytkownik' => array(
			'uzy_imie' => 'Jan',
			'uzy_nazwisko' => 'Kowalski'
		),
		'konto' => array(
			'kuz_email' => 'admin@admin.pl',
			'kuz_haslo' => 'zaq1@WSX'
		),
		'stanowisko' => 'Główny administrator',
		'zdjecie' => 'blank.png'
	);
	
	
	public function should_install() {
		$sql = "SELECT * FROM hotel_role WHERE rol_kod = 'ADMINISTRATOR_GLOWNY'";
		$result = Model::wykonaj_zapytanie_sql($sql);
		
		$role_not_installed = false;
		if(count($result) == 0) {
			$role_not_installed = true;
		}
		
		return $role_not_installed;
	}
	
	public function install() {
		foreach ($this->role as $r) {
			$sql = "SELECT rol_kod FROM hotel_role WHERE rol_kod = ?";
			$params = array($r['rol_kod']);
			$result = Model::wykonaj_zapytanie_sql($sql, $params);
			
			if(empty($result)) {
				$sql = "INSERT INTO hotel_role (rol_kod, rol_nazwa, rol_opis)
							VALUES (?,?,?)";
				$params = array($r['rol_kod'], $r['rol_nazwa'], $r['rol_opis']);
				Model::wykonaj_zapytanie_sql($sql, $params);
			}
		}
		
		$sql = "SELECT rol_id FROM hotel_role WHERE rol_kod = 'ADMINISTRATOR_GLOWNY'";
		$result = Model::wykonaj_zapytanie_sql($sql);
		$rol_id = $result[0]['rol_id'];
		
		$uzytkownik_model = Model::zaladuj_model('Uzytkownicy');
		$konto_model = Model::zaladuj_model('KontaUzytkownikow');
		
		$konto = array(
			'email' => $this->admin_account['konto']['kuz_email'],
			'haslo' => $this->admin_account['konto']['kuz_haslo'],
			'rola' => $rol_id
		);
		
		$konto_id = $konto_model->dodaj_konto_uzytkownika($konto);
		$konto_model->ustaw_jako_aktywne($konto_id);
		
		$uzytkownik = array(
			'imie' => $this->admin_account['uzytkownik']['uzy_imie'],
			'nazwisko' => $this->admin_account['uzytkownik']['uzy_nazwisko'],
		);
		
		$uzytkownik_id = $uzytkownik_model->dodaj_uzytkownika($uzytkownik, $konto_id);
		
		$administrator = array(
			'stanowisko' => $this->admin_account['stanowisko'],
			'zdjecie' => $this->admin_account['zdjecie'],
			'uzytkownik' => $uzytkownik_id
		);
		
		$administrator_model = Model::zaladuj_model('Administratorzy');
		$administrator_model->dodaj_adminstratora($administrator);
	}
}