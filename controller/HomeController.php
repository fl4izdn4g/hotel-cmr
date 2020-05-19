<?php
class HomeController {
	public function lista() {
		$user = Session::pobierz_zalogownego_uzytkownika();
		if($user['role_code'] == 'ADMINISTRATOR_RESTAURACJI') {
			Router::przekierowanie(array('controller' => 'Home', 'action' => 'restauracja'));
		}
		else if($user['role_code'] == 'ADMINISTRATOR_HOTELU') {
			Router::przekierowanie(array('controller' => 'Home', 'action' => 'hotel'));
		}
		
		//tutaj statystyki wszystkiego
		View::ustaw('tytul_strony', 'Panel');
		View::ustaw('podtytul_strony', 'Statystyki');
		
		
		$this->statystyka_wszyscy_uzytkownicy();
		$this->statystyka_wszyscy_administratorzy();
		$this->statystyka_wszyscy_goscie();
		
		$this->zestawienie_miesieczne();
		
		$this->ostatnie_rezerwacje_pokoi();
		$this->ostatnie_rezerwacje_stolikow();
		$this->ostatnie_rezerwacje_potraw();
		
	}
	
	private function ostatnie_rezerwacje_pokoi() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$result = $statystyka->ostatnie_rezerwacje_pokoi();
		
		$pokoje_model = Model::zaladuj_model('Pokoje');
		$przetworzone = array();
		foreach ($result as $r) {
			$element = $r;
			if($r['rp_anulowano']) {
				$element['rp_anulowano'] = 'tak';
			}
			else {
				$element['rp_anulowano'] = 'nie';
			}
			$pokoj_informacje = $pokoje_model->pobierz_pokoj_rezerwacje($r['rp_pok_id']);
				
			$element['rp_nazwa'] = $pokoj_informacje[0]['grp_nazwa'].' - pokój '.$pokoj_informacje[0]['pok_numer'];
			$przetworzone[] = $element;
		}
		View::ustaw('ostanie_pokoje', $przetworzone);
		
	}
	
	private function ostatnie_rezerwacje_stolikow() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$result = $statystyka->ostatnie_rezerwacje_stolika();
		
		$stoliki_model = Model::zaladuj_model('Stoliki');
		$przetworzone = array();
		foreach ($result as $r) {
			$element = $r;
			if($r['rs_anulowano']) {
				$element['rs_anulowano'] = 'tak';
			}
			else {
				$element['rs_anulowano'] = 'nie';
			}
			$stolik_informacje = $stoliki_model->pobierz_stolik_rezerwacje($r['rs_sto_id']);
			
			$element['rs_nazwa'] = $stolik_informacje[0]['sar_nazwa'].' - stolik '.$stolik_informacje[0]['sto_numer'];
			$przetworzone[] = $element;
		}
		View::ustaw('ostanie_stoliki', $przetworzone);
	}
	
	private function ostatnie_rezerwacje_potraw() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$result = $statystyka->ostatnie_zamowienia();
		
		$potrawy_model = Model::zaladuj_model('Potrawy');
		$przetworzone = array();
		foreach ($result as $r) {
			$element = $r;
			if($r['zp_anulowano']) {
				$element['zp_anulowano'] = 'tak';
			}
			else {
				$element['zp_anulowano'] = 'nie';
			}
			$potrawy_informacje = $potrawy_model->pobierz_potrawy_rezerwacje($r['zp_id']);
			
			$nazwa = array();
			foreach ($potrawy_informacje as $pot) {
				$nazwa[] = $pot['pot_nazwa'];
			}
			
			$element['zp_nazwa'] = implode(', ', $nazwa);
			$przetworzone[] = $element;
		}
		View::ustaw('ostanie_potrawy', $przetworzone);
		
	}
	
	
	
	
	
	private function zestawienie_miesieczne() {
		$current_month = date('m');
		$current_day = date('d');
		$current_year = date('Y');
		$aktualny_miesiac = $this->miesiace[(int)$current_month];
		View::ustaw('aktualny_miesiac', $aktualny_miesiac);
		View::ustaw('zestawienie_okres', 'Zestawienie: 1 styczeń '.$current_year.' - '.$current_day.' '.$aktualny_miesiac.' '.$current_year);
		
		$this->wygeneruj_skrypt_wykresu((int)$current_month);
		
		$statystyki = Model::zaladuj_model('Statystyki');
		$miesieczne_pokoje = $statystyki->zestawienie_miesieczne_pokoje();
		$this->przelicz_miesieczne($miesieczne_pokoje, 'pokoje');
		
		$miesieczne_stoliki = $statystyki->zestawienie_miesieczne_stoliki();
		$this->przelicz_miesieczne($miesieczne_stoliki, 'stoliki');

		$miesieczne_potrawy = $statystyki->zestawienie_miesieczne_potrawy();
		$this->przelicz_miesieczne($miesieczne_potrawy, 'potrawy');
		
		$this->oblicz_przychody();
		
	}
	
	private function zestawienie_miesieczne_restauracja() {
		$current_month = date('m');
		$current_day = date('d');
		$current_year = date('Y');
		$aktualny_miesiac = $this->miesiace[(int)$current_month];
		View::ustaw('aktualny_miesiac', $aktualny_miesiac);
		View::ustaw('zestawienie_okres', 'Zestawienie: 1 styczeń '.$current_year.' - '.$current_day.' '.$aktualny_miesiac.' '.$current_year);
	
		$this->wygeneruj_skrypt_wykresu_restauracja((int)$current_month);
	
		$statystyki = Model::zaladuj_model('Statystyki');
	
		$miesieczne_stoliki = $statystyki->zestawienie_miesieczne_stoliki();
		$this->przelicz_miesieczne($miesieczne_stoliki, 'stoliki');
	
		$miesieczne_potrawy = $statystyki->zestawienie_miesieczne_potrawy();
		$this->przelicz_miesieczne($miesieczne_potrawy, 'potrawy');
	
		$this->oblicz_przychody_restauracja();
	
	}
	
	private function zestawienie_miesieczne_hotel() {
		$current_month = date('m');
		$current_day = date('d');
		$current_year = date('Y');
		$aktualny_miesiac = $this->miesiace[(int)$current_month];
		View::ustaw('aktualny_miesiac', $aktualny_miesiac);
		View::ustaw('zestawienie_okres', 'Zestawienie: 1 styczeń '.$current_year.' - '.$current_day.' '.$aktualny_miesiac.' '.$current_year);
	
		$this->wygeneruj_skrypt_wykresu_hotel((int)$current_month);
	
		$statystyki = Model::zaladuj_model('Statystyki');
		$miesieczne_pokoje = $statystyki->zestawienie_miesieczne_pokoje();
		$this->przelicz_miesieczne($miesieczne_pokoje, 'pokoje');
	
	}
	
	private function zestawienie_najpopularniejsze_pokoje() {
		$this->wygeneruj_skrypt_wykresu_najpopularniejsze_pokoje();
	}
	
	private function zestawienie_najpopularniejsze_grupy() {
		$this->wygeneruj_skrypt_wykresu_najpopularniejsze_grupy();
	}

	private function wygeneruj_skrypt_wykresu_najpopularniejsze_pokoje() {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("zestawieniePopularnePokojeChart").getContext("2d");';
		$script .= 'var data = [';
		
		$statystyki = Model::zaladuj_model('Statystyki');
		$pokoje = $statystyki->najpopularniejsze_pokoje();
		$dane = array();
		foreach ($pokoje as $p) {
			$hex_string = bin2hex(openssl_random_pseudo_bytes(3));
			$element = '{';
			$element .= 'value: '.$p['ile'].',';
			$element .= 'color: "#'.$hex_string.'",';
			$element .= 'highlight: "#'.$hex_string.'",';
			$element .= 'label: "'.$p['grp_nazwa'].'-'.$p['pok_numer'].'",';
			$element .= '}';
			
			$dane[] = $element;
		}
		$data = implode(',', $dane);
		$script .= $data;
		$script .= '];';
	
		$script .= 'var donutChart = new Chart(ctx).Doughnut(data);';
		$script .= '</script>';
	
		View::zalacz_blok('scripts', $script);
	}
	
	private function wygeneruj_skrypt_wykresu_najpopularniejsze_potrawy() {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("popularnePotrawyChart").getContext("2d");';
		$script .= 'var data = [';
	
		$statystyki = Model::zaladuj_model('Statystyki');
		$pokoje = $statystyki->najpopularniejsze_potrawy();
		$dane = array();
		foreach ($pokoje as $p) {
			$hex_string = bin2hex(openssl_random_pseudo_bytes(3));
			$element = '{';
			$element .= 'value: '.$p['ile'].',';
			$element .= 'color: "#'.$hex_string.'",';
			$element .= 'highlight: "#'.$hex_string.'",';
			$element .= 'label: "'.$p['pot_nazwa'].'",';
			$element .= '}';
				
			$dane[] = $element;
		}
		$data = implode(',', $dane);
		$script .= $data;
		$script .= '];';
	
		$script .= 'var donutChart = new Chart(ctx).Doughnut(data);';
		$script .= '</script>';
	
		View::zalacz_blok('scripts', $script);
	}
	
	private function wygeneruj_skrypt_wykresu_najpopularniejsze_sale() {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("popularneSaleChart").getContext("2d");';
		$script .= 'var data = [';
	
		$statystyki = Model::zaladuj_model('Statystyki');
		$pokoje = $statystyki->najpopularniejsze_sale();
		$dane = array();
		foreach ($pokoje as $p) {
			$hex_string = bin2hex(openssl_random_pseudo_bytes(3));
			$element = '{';
			$element .= 'value: '.$p['ile'].',';
			$element .= 'color: "#'.$hex_string.'",';
			$element .= 'highlight: "#'.$hex_string.'",';
			$element .= 'label: "'.$p['sar_nazwa'].'",';
			$element .= '}';
	
			$dane[] = $element;
		}
		$data = implode(',', $dane);
		$script .= $data;
		$script .= '];';
	
		$script .= 'var donutChart = new Chart(ctx).PolarArea(data);';
		$script .= '</script>';
	
		View::zalacz_blok('scripts', $script);
	}
	
	private function wygeneruj_skrypt_wykresu_najpopularniejsze_grupy() {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("zestawieniePopularneGrupyChart").getContext("2d");';
		$script .= 'var data = [';
	
		$statystyki = Model::zaladuj_model('Statystyki');
		$najpopularniejsze_grupy = $statystyki->najpopularniejsze_grupy_pokoi();
		$dane = array();
		foreach ($najpopularniejsze_grupy as $p) {
			$hex_string = bin2hex(openssl_random_pseudo_bytes(3));
			$element = '{';
			$element .= 'value: '.$p['ile'].',';
			$element .= 'color: "#'.$hex_string.'",';
			$element .= 'highlight: "#'.$hex_string.'",';
			$element .= 'label: "'.$p['grp_nazwa'].'",';
			$element .= '}';
				
			$dane[] = $element;
		}
		$data = implode(',', $dane);
		$script .= $data;
		$script .= '];';
	
		$script .= 'var donutChart = new Chart(ctx).PolarArea(data);';
		$script .= '</script>';
	
		View::zalacz_blok('scripts', $script);
	}
	
	
	private function przelicz_miesieczne($miesieczne, $typ) {
		$suma = 0;
		$suma_zrealizowane = 0;
		foreach ($miesieczne as $p) {
			$suma += $p['ile'];
			if(!$p['anulowano']) {
				$suma_zrealizowane += $p['ile'];
			}
		}
		if($suma > 0) {
			$procent = $suma_zrealizowane / $suma * 100;
		}
		else {
			$procent = 0;
		}
		View::ustaw($typ.'_miesiecznie', $suma_zrealizowane);
		View::ustaw($typ.'_miesiecznie_lacznie', $suma);
		View::ustaw($typ.'_miesiecznie_procent', $procent);
	}
	
	private function oblicz_przychody() {
		$statystyki = Model::zaladuj_model('Statystyki');
		$aktualny_miesiac = date('m');
		$przychod_pokoje = $statystyki->przychod_pokoje($aktualny_miesiac);
		if(empty($przychod_pokoje)) {
			$przychod_pokoje = 0;
		}
		else {
			$przychod_pokoje = $przychod_pokoje[0]['ile'];
		}
		$przychod_pokoje_poprzedni = $statystyki->przychod_pokoje($aktualny_miesiac - 1);
		$przychod_pokoje_poprzedni = $przychod_pokoje_poprzedni[0]['ile'];
		
		$this->przychod($przychod_pokoje, $przychod_pokoje_poprzedni, 'pokoje');
		
		
		$przychod_stoliki = $statystyki->przychod_stoliki($aktualny_miesiac);
		if(empty($przychod_stoliki)) {
			$przychod_stoliki = 0;
		}
		else {
			$przychod_stoliki = $przychod_stoliki[0]['ile'];
		}
		$przychod_stoliki_poprzedni = $statystyki->przychod_stoliki($aktualny_miesiac - 1);
		$przychod_stoliki_poprzedni = $przychod_stoliki_poprzedni[0]['ile'];
		
		$this->przychod($przychod_stoliki, $przychod_stoliki_poprzedni, 'stoliki');
		
		
		$przychod_potrawy = $statystyki->przychod_potrawy($aktualny_miesiac);
		if(empty($przychod_potrawy)){
			$przychod_potrawy = 0;
		}
		else {
			$przychod_potrawy = $przychod_potrawy[0]['ile'];
		}
		$przychod_potrawy_poprzedni = $statystyki->przychod_potrawy($aktualny_miesiac - 1);
		$przychod_potrawy_poprzedni = $przychod_potrawy_poprzedni[0]['ile'];
		
		$this->przychod($przychod_potrawy, $przychod_potrawy_poprzedni, 'potrawy');
		
		
		$przychod_razem = $przychod_pokoje + $przychod_stoliki + $przychod_potrawy;
		$przychod_razem_poprzedni = $przychod_pokoje_poprzedni + $przychod_stoliki_poprzedni + $przychod_potrawy_poprzedni;
		
		$this->przychod($przychod_razem, $przychod_razem_poprzedni, 'razem');
	}
	
	private function oblicz_przychody_restauracja() {
		$statystyki = Model::zaladuj_model('Statystyki');
		$aktualny_miesiac = date('m');
			
		$przychod_stoliki = $statystyki->przychod_stoliki($aktualny_miesiac);
		if(empty($przychod_stoliki)) {
			$przychod_stoliki = 0;
		}
		else {
			$przychod_stoliki = $przychod_stoliki[0]['ile'];
		}
		$przychod_stoliki_poprzedni = $statystyki->przychod_stoliki($aktualny_miesiac - 1);
		if(!empty($przychod_stoliki_poprzedni)) {
			$przychod_stoliki_poprzedni = $przychod_stoliki_poprzedni[0]['ile'];
		}
		else {
			$przychod_stoliki_poprzedni = 0;
		}
	
		$this->przychod($przychod_stoliki, $przychod_stoliki_poprzedni, 'stoliki');
	
	
		$przychod_potrawy = $statystyki->przychod_potrawy($aktualny_miesiac);
		if(empty($przychod_potrawy)){
			$przychod_potrawy = 0;
		}
		else {
			$przychod_potrawy = $przychod_potrawy[0]['ile'];
		}
		$przychod_potrawy_poprzedni = $statystyki->przychod_potrawy($aktualny_miesiac - 1);
		if(!empty($przychod_potrawy_poprzedni)) {
			$przychod_potrawy_poprzedni = $przychod_potrawy_poprzedni[0]['ile'];
		}
		else {
			$przychod_potrawy_poprzedni = 0;
		}
		$this->przychod($przychod_potrawy, $przychod_potrawy_poprzedni, 'potrawy');
		
		$przychod_razem = $przychod_stoliki + $przychod_potrawy;
		$przychod_razem_poprzedni = $przychod_stoliki_poprzedni + $przychod_potrawy_poprzedni;
	
		$this->przychod($przychod_razem, $przychod_razem_poprzedni, 'razem');
	}
	
	private function przychod($obecny, $poprzedni, $typ) {
		$przez = ($poprzedni == 0) ? 1 : $poprzedni;
		
		if($poprzedni == 0) {
			if($obecny == 0) {
				$procentowy = 0;
			}
			else {
				$procentowy = 100;
			}
		}
		else {
			$procentowy = $obecny / $poprzedni * 100;
			$procentowy = $procentowy - 100;
		}
		
		if($procentowy > 0) {
			$klasa_kolor = 'text-green';
			$klasa_strzalka = 'fa-caret-up';
		}
		else if($procentowy < 0) {
			$klasa_kolor = 'text-red';
			$klasa_strzalka = 'fa-caret-down';
		}
		else {
			$klasa_kolor = 'text-yellow';
			$klasa_strzalka = 'fa-caret-left';
		}
		
		View::ustaw($typ.'_klasa_kolor', $klasa_kolor);
		View::ustaw($typ.'_klasa_strzalka', $klasa_strzalka);
		
		View::ustaw($typ.'_przychod', number_format($obecny,2));
		View::ustaw($typ.'_procentowy', number_format(abs($procentowy),2));
	}
	
	
	private function wygeneruj_skrypt_wykresu($aktualny_miesiac) {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("zestawienieChart").getContext("2d");';
		$script .= 'var data = {';
		$script .= 'labels: [';
		for($i = 1; $i <= $aktualny_miesiac; $i++) {
			$script .= '"'. $this->miesiace[$i].'",';
		}
		$script = substr($script, 0, strlen($script) - 1);
		$script .= '],';
		$script .= 'datasets: [';
		
		$script .= '{';
		$script .= 'label: "Rezerwacje pokoi",';
		$script .= 'fillColor: "rgba(0,192,239,0.5)",';
		$script .= 'strokeColor: "rgba(0,192,239,0.8)",';
		$script .= 'highlightFill: "rgba(0,192,239,0.75)",';
		$script .= 'highlightStroke: "rgba(0,192,239,1)",';
		$script .= $this->wykres_dane_pokoje();
		$script .= '},';
		
		$script .= '{';
		$script .= 'label: "Rezerwacje stolików",';
		$script .= 'fillColor: "rgba(221,75,57,0.5)",';
		$script .= 'strokeColor: "rgba(221,75,57,0.8)",';
		$script .= 'highlightFill: "rgba(221,75,57,0.75)",';
		$script .= 'highlightStroke: "rgba(221,75,57,1)",';
		$script .= $this->wykres_dane_stoliki();
		$script .= '},';
		
		$script .= '{';
		$script .= 'label: "Zamówienia potraw",';
		$script .= 'fillColor: "rgba(0,166,90,0.5)",';
		$script .= 'strokeColor: "rgba(0,166,90,0.8)",';
		$script .= 'highlightFill: "rgba(0,166,90,0.75)",';
		$script .= 'highlightStroke: "rgba(0,166,90,1)",';
		$script .= $this->wykres_dane_zamowienia();
		$script .= '}';
		
		$script .= ']';
		$script .= '};';
		
		$script .= 'var barChart = new Chart(ctx).Bar(data);';
		$script .= '</script>';
		
		View::zalacz_blok('scripts', $script);
	}
	
	private function wygeneruj_skrypt_wykresu_restauracja($aktualny_miesiac) {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("restauracjaChart").getContext("2d");';
		$script .= 'var data = {';
		$script .= 'labels: [';
		for($i = 1; $i <= $aktualny_miesiac; $i++) {
			$script .= '"'. $this->miesiace[$i].'",';
		}
		$script = substr($script, 0, strlen($script) - 1);
		$script .= '],';
		$script .= 'datasets: [';
	
		$script .= '{';
		$script .= 'label: "Rezerwacje stolików",';
		$script .= 'fillColor: "rgba(221,75,57,0.5)",';
		$script .= 'strokeColor: "rgba(221,75,57,0.8)",';
		$script .= 'highlightFill: "rgba(221,75,57,0.75)",';
		$script .= 'highlightStroke: "rgba(221,75,57,1)",';
		$script .= $this->wykres_dane_stoliki();
		$script .= '},';
	
		$script .= '{';
		$script .= 'label: "Zamówienia potraw",';
		$script .= 'fillColor: "rgba(0,166,90,0.5)",';
		$script .= 'strokeColor: "rgba(0,166,90,0.8)",';
		$script .= 'highlightFill: "rgba(0,166,90,0.75)",';
		$script .= 'highlightStroke: "rgba(0,166,90,1)",';
		$script .= $this->wykres_dane_zamowienia();
		$script .= '}';
	
		$script .= ']';
		$script .= '};';
	
		$script .= 'var barChart = new Chart(ctx).Bar(data);';
		$script .= '</script>';
	
		View::zalacz_blok('scripts', $script);
	}
	
	private function wygeneruj_skrypt_wykresu_hotel($aktualny_miesiac) {
		$script = '<script>';
		$script .= 'var ctx = document.getElementById("zestawienieHotelChart").getContext("2d");';
		$script .= 'var data = {';
		$script .= 'labels: [';
		for($i = 1; $i <= $aktualny_miesiac; $i++) {
			$script .= '"'. $this->miesiace[$i].'",';
		}
		$script = substr($script, 0, strlen($script) - 1);
		$script .= '],';
		$script .= 'datasets: [';
	
		
		
		$script .= '{';
		$script .= 'label: "Rezerwacje pokoi anulowane",';
		$script .= 'fillColor: "rgba(192,192,192,0.5)",';
		$script .= 'strokeColor: "rgba(192,192,192,0.8)",';
		$script .= 'highlightFill: "rgba(192,192,192,0.75)",';
		$script .= 'highlightStroke: "rgba(192,192,192,1)",';
		$script .= $this->wykres_dane_pokoje_anulowane();
		$script .= '},';
		
		$script .= '{';
		$script .= 'label: "Rezerwacje pokoi",';
		$script .= 'fillColor: "rgba(0,192,239,0.5)",';
		$script .= 'strokeColor: "rgba(0,192,239,0.8)",';
		$script .= 'highlightFill: "rgba(0,192,239,0.75)",';
		$script .= 'highlightStroke: "rgba(0,192,239,1)",';
		$script .= $this->wykres_dane_pokoje_dobre();
		$script .= '}';
	
		$script .= ']';
		$script .= '};';
	
		$script .= 'var barChart = new Chart(ctx).Bar(data);';
		$script .= '</script>';
	
		View::zalacz_blok('scripts', $script);
	}
	
	
	private function wykres_dane_pokoje() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$dane = $statystyka->zestawienie_roczne_pokoje_do_dzisiaj();
		return $this->wygeneruj_dane_do_wykresu($dane);		
	}
	
	private function wykres_dane_pokoje_dobre() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$dane = $statystyka->zestawienie_roczne_pokoje_do_dzisiaj_dobre();
		return $this->wygeneruj_dane_do_wykresu($dane);
	}
	
	private function wykres_dane_pokoje_anulowane() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$dane = $statystyka->zestawienie_roczne_pokoje_do_dzisiaj_anulowane();
		return $this->wygeneruj_dane_do_wykresu($dane);
	}
	
	private function wygeneruj_dane_do_wykresu($dane) {
		$aktualny_miesiac = (int)date('m');
		
		$dane_do_wykresu = array();
		if(empty($dane)) {
			for ($i = 1; $i <= $aktualny_miesiac; $i++) {
				$dane_do_wykresu[] = 0;
			}
		}
		else {
			$slownik_miesiecy = array();
			foreach ($dane as $s) {
				$slownik_miesiecy[$s['miesiac']] = $s['ile'];
			}
				
			for($i = 1; $i <= $aktualny_miesiac; $i++) {
				if(array_key_exists($i, $slownik_miesiecy)) {
					$dane_do_wykresu[] = $slownik_miesiecy[$i];
				}
				else {
					$dane_do_wykresu[] = 0;
				}
			}
		}
		
		return 'data: ['.implode(',', $dane_do_wykresu).']';
	}
	
	private function wykres_dane_stoliki() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$dane = $statystyka->zestawienie_roczne_stoliki_do_dzisiaj();
		return $this->wygeneruj_dane_do_wykresu($dane);	
	}
	
	private function wykres_dane_zamowienia() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$dane = $statystyka->zestawienie_roczne_potrawy_do_dzisiaj();
		return $this->wygeneruj_dane_do_wykresu($dane);	
	}
		
	private $miesiace = array(
		1 => 'styczeń',
		2 => 'luty',
		3 => 'marzec',
		4 => 'kwiecień',
		5 => 'maj',
		6 => 'czerwiec',
		7 => 'lipiec',
		8 => 'sierpień',
		9 => 'wrzesień',
		10 => 'październik',
		11 => 'listopad',
		12 => 'grudzień',
	);
	
	private function statystyka_wszyscy_uzytkownicy() {
		$statystyki = Model::zaladuj_model('Statystyki');
		$liczba_wszystkich_uzytkownikow = $statystyki->pobierz_liczbe_uzytkownikow('WSZYSCY');
		$uzytkownicy_wszyscy_suma = 0;
		$uzytkownicy_aktywni_suma = 0;
		foreach($liczba_wszystkich_uzytkownikow as $el) {
			$uzytkownicy_wszyscy_suma += $el['ile'];
			if($el['kuz_status_konta'] == 'AKTYWNE') {
				$uzytkownicy_aktywni_suma += $el['ile'];
			}
		}
		
		$procent_aktywnych = number_format($uzytkownicy_aktywni_suma/$uzytkownicy_wszyscy_suma * 100,2);
		View::ustaw('wszyscy_procent_aktywnych', $procent_aktywnych);
		View::ustaw('wszyscy_suma', $uzytkownicy_wszyscy_suma);
	}
	
	private function statystyka_wszyscy_administratorzy() {
		$statystyki = Model::zaladuj_model('Statystyki');
		
		$liczba_wszystkich_uzytkownikow = $statystyki->pobierz_liczbe_uzytkownikow('ADMINISTRATORZY');
		$uzytkownicy_wszyscy_suma = 0;
		$uzytkownicy_aktywni_suma = 0;
		foreach($liczba_wszystkich_uzytkownikow as $el) {
			$uzytkownicy_wszyscy_suma += $el['ile'];
			if($el['kuz_status_konta'] == 'AKTYWNE') {
				$uzytkownicy_aktywni_suma += $el['ile'];
			}
		}
		
		$procent_aktywnych = number_format($uzytkownicy_aktywni_suma/$uzytkownicy_wszyscy_suma * 100,2);
		View::ustaw('administratorzy_procent_aktywnych', $procent_aktywnych);
		View::ustaw('administratorzy_suma', $uzytkownicy_wszyscy_suma);
	}
	
	private function statystyka_wszyscy_goscie() {
		$statystyki = Model::zaladuj_model('Statystyki');
		
		$liczba_wszystkich_uzytkownikow = $statystyki->pobierz_liczbe_uzytkownikow('GOSCIE');
		$uzytkownicy_wszyscy_suma = 0;
		$uzytkownicy_aktywni_suma = 0;
		foreach($liczba_wszystkich_uzytkownikow as $el) {
			$uzytkownicy_wszyscy_suma += $el['ile'];
			if($el['kuz_status_konta'] == 'AKTYWNE') {
				$uzytkownicy_aktywni_suma += $el['ile'];
			}
		}
		
		$procent_aktywnych = number_format($uzytkownicy_aktywni_suma/$uzytkownicy_wszyscy_suma * 100,2);
		View::ustaw('goscie_procent_aktywnych', $procent_aktywnych);
		View::ustaw('goscie_suma', $uzytkownicy_wszyscy_suma);
	}
	
	public function restauracja() {
		//tutaj statystyki tylko restauracji
		View::ustaw('tytul_strony', 'Panel');
		View::ustaw('podtytul_strony', 'Statystyki restauracji');
		
		$this->ile_restauracja();
		$this->zestawienie_miesieczne_restauracja();
		
		$this->ostatnie_rezerwacje_stolikow();
		$this->ostatnie_rezerwacje_potraw();
		
		$this->wygeneruj_skrypt_wykresu_najpopularniejsze_potrawy();
		$this->wygeneruj_skrypt_wykresu_najpopularniejsze_sale();
		
		//$this->przygotuj_stan_magazynowy();
	}
	
	private function ile_restauracja() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$ile_stoliki = $statystyka->liczba_stolikow();
		View::ustaw('ile_stoliki', $ile_stoliki[0]['ile']);
		
		$ile_potrawy = $statystyka->liczba_potraw();
		View::ustaw('ile_potrawy', $ile_potrawy[0]['ile']);
		
		$ile_zamowienia = $statystyka->liczba_zamowien();
		View::ustaw('ile_zamowienia', $ile_zamowienia[0]['ile']);
	}
	
	public function hotel() {
		//tutaj statystyki tylko hotelu
		View::ustaw('tytul_strony', 'Panel');
		View::ustaw('podtytul_strony', 'Statystyki hotelu');
		
		$statystyki = Model::zaladuj_model('Statystyki');
		$ile_pokoi = $statystyki->liczba_pokoi();
		View::ustaw('liczba_pokoi', $ile_pokoi[0]['ile']);
		
		$ile_gosci = $statystyki->liczba_gosci();
		View::ustaw('liczba_gosci', $ile_gosci[0]['ile']);
		
		$ile_gosci = $statystyki->liczba_rezerwacji();
		View::ustaw('liczba_rezerwacji', $ile_gosci[0]['ile']);
		
		$this->zestawienie_miesieczne_hotel();
		
		$this->zestawienie_najpopularniejsze_pokoje();
		$this->zestawienie_najpopularniejsze_grupy();
		
		$this->ostatnie_rezerwacje_pokoi();

	}
	
	private function przygotuj_stan_magazynowy() {
		$statystyka = Model::zaladuj_model('Statystyki');
		$stany = $statystyka->produkty_na_wyczerpaniu();
		
		$procent = 0.05;
		$przetworzone = array();
		foreach ($stany as $s) {
			$element = $s;
			if($s['stm_aktualny_stan'] <= $s['stm_minimalny_dopuszczalny_stan']) {
				$element['stm_status'] = 'ERROR';
			}
			else {
				$element['stm_status'] = "WARNING";
			}
			
			$przetworzone[] = $element;
		}
			
		View::ustaw('stan_magazynowy', $przetworzone);
	}
}