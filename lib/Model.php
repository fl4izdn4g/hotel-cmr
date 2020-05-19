<?php
class Model {
	
	public static function przygotuj_parametry_do_zapytania($parametry_z_formularza) {
		$parametry_z_post = array();
		foreach ($parametry_z_formularza as $parametr) {
			$parametry_z_post[] = $_POST[$parametr];
		}
	
		return $parametry_z_post;
	}
	
	public static function przygotuj_dane_do_formularza($parametry_z_formularza) {
		$parametry_z_post = array();
		foreach ($parametry_z_formularza as $parametr) {
			$parametry_z_post[$parametr] = isset($_POST[$parametr]) ? $_POST[$parametr] : '';
		}
	
		return $parametry_z_post;
	}
	
	public static function przygotuj_puste_bledy($parametry_z_formularza) {
		$parametry = array();
		foreach ($parametry_z_formularza as $parametr) {
			$parametry[$parametr] = '';
		}
		
		return $parametry;
	}
	
	public static function sprawdz_poprawnosc_danych($parametry_z_formularza, $reguly_walidacji) {
		$bledy_formularza = array();
		foreach ($parametry_z_formularza as $parametr) {
			if(isset($reguly_walidacji[$parametr])) {
				foreach ($reguly_walidacji[$parametr] as $regula => $walidator) {
					$nazwa_reguly = $regula;
					if(is_numeric($regula)) {
						$nazwa_reguly = $walidator;
					}
					
					if($nazwa_reguly == 'nie_pusty') {
						if(empty($_POST[$parametr])) {
							$bledy_formularza[$parametr][] = 'Pole wymagane nie może być puste';
						}
					}
	
					if($nazwa_reguly == 'dlugosc') {
						$reguly_dlugosc = $reguly_walidacji[$parametr]['dlugosc'];
						if(isset($reguly_dlugosc['max'])) {
							if(mb_strlen($_POST[$parametr]) > $reguly_dlugosc['max']) {
								$bledy_formularza[$parametr][] = 'Pole nie może być dłuższe niż '.$reguly_dlugosc['max'].' znaków';
							}
						}
							
						if(isset($reguly_dlugosc['min'])) {
							if(mb_strlen($_POST[$parametr]) < $reguly_dlugosc['min']) {
								$bledy_formularza[$parametr][] = 'Pole nie może być krótsze niż '.$reguly_dlugosc['min'].' znaków';
							}
						}
						
						if(isset($reguly_dlugosc['rowne'])) {
							if(mb_strlen($_POST[$parametr]) == $reguly_dlugosc['rowne']) {
								$bledy_formularza[$parametr][] = 'Pole musi mieć długość '.$reguly_dlugosc['rowne'].' znaków';
							}
						}
					}
	
					if($nazwa_reguly == 'kod_pocztowy') {
						if(!preg_match('/[0-9][0-9]-[0-9][0-9][0-9]/', $_POST[$parametr])) {
							$bledy_formularza[$parametr][] = 'Pole nie posiada struktury kodu pocztowego: 00-000';
						}
					}
	
					if($nazwa_reguly == 'liczba') {
						if(!is_numeric($_POST[$parametr])) {
							$bledy_formularza[$parametr][] = 'Pole nie jest liczbą';
						}
					}
				
					if($nazwa_reguly == 'haslo_to_samo') {
						$pole_do_porownania = $reguly_walidacji[$parametr]['haslo_to_samo']['pole'];
						
						if($_POST[$parametr] != $_POST[$pole_do_porownania]) {
							$bledy_formularza[$parametr][] = 'Wpisane hasła muszą być identyczne';
						}
					}
								
					if($nazwa_reguly == 'email') {
						if(!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $_POST[$parametr])) {
							$bledy_formularza[$parametr][] = 'Pole nie posiada prawidłowej struktury email';
						}
					}
					
					if($nazwa_reguly == 'telefon') {
						if(!preg_match('/\+[0-9]{2}\s[0-9]{3}\s[0-9]{3}\s[0-9]{3}/', $_POST[$parametr])) {
							$bledy_formularza[$parametr][] = 'Pole nie posiada prawidłowej struktury numeru telefonu +00 000 000 000';
						}
					}
					
					if($nazwa_reguly == 'data') {
						if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST[$parametr])) {
							$bledy_formularza[$parametr][] = 'Pole nie posiada prawidłowej struktury daty YYYY/MM/DD';
						}
					}
					
					if($nazwa_reguly == 'wczesniej_od') {
						$pole = $reguly_walidacji[$parametr]['wczesniej_od']['pole'];
						if(!empty($_POST[$pole])) {
							if($_POST[$parametr] > $_POST[$pole]) {
								$bledy_formularza[$parametr][] = 'Pole powinno posiadać datę wcześniejszą od '.$_POST[$pole];
							}
						}
					}
					
					if($nazwa_reguly == 'pozniej_od') {
						$pole = $reguly_walidacji[$parametr]['pozniej_od']['pole'];
						if(!empty($_POST[$pole])) {
							if($_POST[$parametr] < $_POST[$pole]) {
								$bledy_formularza[$parametr][] = 'Pole powinno posiadać datę późniejszą od '.$_POST[$pole];
							}
						}
					}
					
					if($nazwa_reguly == 'nie_rowna_data_od') {
						$pole = $reguly_walidacji[$parametr]['nie_rowna_data_od']['pole'];
						if(!empty($_POST[$pole])) {
							if($_POST[$parametr] == $_POST[$pole]) {
								$bledy_formularza[$parametr][] = 'Pole powinno posiadać różną datę od '.$_POST[$pole];
							}
						}
					}
					
					if($nazwa_reguly == 'nie_pozniej_niz_dzisiaj') {
						$dzisiaj = date('Y-m-d').' 00:00:00';
						if($_POST[$parametr] > $dzisiaj) {
							$bledy_formularza[$parametr][] = 'Pole powinno posiadać datę wcześniejszą niż dzisiaj';
						}
					}
					
					if($nazwa_reguly == 'nie_wczesniej_niz_dzisiaj') {
						$dzisiaj = date('Y-m-d').' 00:00:00';
						if($_POST[$parametr] < $dzisiaj) {
							$bledy_formularza[$parametr][] = 'Pole powinno posiadać datę poźniejszą niż dzisiaj';
						}
					}
					
					if($nazwa_reguly == 'wiekszy_od_pola') {
						$pole = $reguly_walidacji[$parametr]['wiekszy_od_pola']['pole'];
						if(!empty($_POST[$pole])) {
							if($_POST[$parametr] < $_POST[$pole]) {
								$bledy_formularza[$parametr][] = 'Pole powinno posiadać wartość większą od '.$_POST[$pole];
							}
						}
					}
					
					if($nazwa_reguly == 'mniejszy_od_pola') {
						$pole = $reguly_walidacji[$parametr]['mniejszy_od_pola']['pole'];
						if(!empty($_POST[$pole])) {
							if($_POST[$parametr] > $_POST[$pole]) {
								$bledy_formularza[$parametr][] = 'Pole powinno posiadać wartość mniejszą od '.$_POST[$pole];
							}
						}
					}
					
					if($nazwa_reguly == 'mniejszy_od') {
						$wartosc = $reguly_walidacji[$parametr]['mniejszy_od'];
						if(!empty($_POST[$pole])) {
							if($_POST[$parametr] > $wartosc) {
								$bledy_formularza[$parametr][] = 'Pole powinno posiadać wartość mniejszą od '.$wartosc;
							}
						}
					}
					
					if($nazwa_reguly == 'wiekszy_od') {
						$wartosc = $reguly_walidacji[$parametr]['wiekszy_od'];
						if($_POST[$parametr] < $wartosc) {
							$bledy_formularza[$parametr][] = 'Pole powinno posiadać wartość większą od '.$wartosc;
						}
					}
					
					if($nazwa_reguly == 'pole_unikalne') {
						$reguly_pole_unikalne = $reguly_walidacji[$parametr]['pole_unikalne'];
						$pole = $reguly_pole_unikalne['pole'];
						$tabela = $reguly_pole_unikalne['tabela'];
						
						$sql = "SELECT * FROM ".$tabela." WHERE ".$pole."= ?";
				
						$parametry = array($_POST[$parametr]);
						$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
						if(!empty($result)) {
							$bledy_formularza[$parametr][] = 'Pole nie jest unikalne';
						}
					}
					
					if($nazwa_reguly == 'pesel') {
						if(!empty($_POST[$parametr])) {
							$wagi = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3); 
							$suma = 0;
							for ($i = 0; $i < 10; $i++)
							{
								$suma += $wagi[$i] * $_POST[$parametr][$i]; 
							}
							$checksum = 10 - $suma % 10; 
							$to_check = ($checksum == 10) ? 0: $checksum;
							if ($to_check != $_POST[$parametr][10]) {
								$bledy_formularza[$parametr][] = 'Pole posiada nieprawidłowy numer PESEL';
							}
						}
					}
				}
				
				if(empty($bledy_formularza[$parametr])) {
					$bledy_formularza[$parametr] = array();
				}
			}
			else {
				$bledy_formularza[$parametr] = array();
			}
		}
	
		return $bledy_formularza;
	}
	
	public static function czy_sa_bledy($bledy_walidacji) {
		if(empty($bledy_walidacji)) {
			return false;
		}
		
		$czy_sa_bledy = false;
		foreach ($bledy_walidacji as $blad) {
			if(!empty($blad)) {
				$czy_sa_bledy = true;
				break;
			}
		}
				
		return $czy_sa_bledy;
	}
    
	public static function aktualna_wartosc_po_post($pole_formularza, $wartosc_domyslna) {
		if(isset($_POST[$pole_formularza])) {
			return $_POST[$pole_formularza];
		}
	
		return $wartosc_domyslna;
	}
	
	public static function zaladuj_model($nazwa) {
		$model_path = 'model/'.$nazwa.'.php';
		if(file_exists($model_path)) {
			require_once $model_path;
			
			$model = new $nazwa();
			
			return $model;
		}
		
		return null;
	}
	
	public static function wykonaj_zapytanie_sql($sql, $params = array()) {
		global $link;
		global $errors;
	
		global $konfiguracja;
		
		$errors = array();
		
		$dsn = 'mysql:host='.$konfiguracja['database_address'].';dbname='.$konfiguracja['database_name'].';charset=utf8mb4';
		try {
			$db = new PDO($dsn, 
						  $konfiguracja['database_login'], $konfiguracja['database_password'],
					      array(PDO::ATTR_EMULATE_PREPARES => false, 
					      		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
			);
		
			$results = array();	
			if(!empty($params)) {
				$stmt = $db->prepare($sql);
				$exec = $stmt->execute($params);
				if(strpos($sql, 'SELECT') === 0) {
					$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
			}
			else {
				$stmt = $db->query($sql);
				if(strpos($sql, 'SELECT') === 0) {
					$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				};
			}
		
			return $results;
		}
		catch(PDOException $ex) {
			echo 'Wyjatek';
			var_dump($ex); die;
		}
	}
	
	
	
}