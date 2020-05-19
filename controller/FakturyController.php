<?php
class FakturyController {
	public function lista() {
		View::ustaw('tytul_strony', 'Faktury');
		View::ustaw('podtytul_strony', 'Lista wystawionych faktur');
		
		
		$faktury = $this->model->pobierz_wszystkie_faktury();
		
		$przetworzone_faktury = array();
		foreach ($faktury as $f) {
			$element = $f;
			
			$element['fak_anulowana'] = 'Nie';
			if(!empty($f['fak_czy_usunieto'])) {
				$element['fak_anulowana'] = 'Tak';
			}
			
			$element['fak_odbiorca'] = $f['fak_odbiorca_imie'].' '.$f['fak_odbiorca_nazwisko'];
			
			$przetworzone_faktury[] = $element;
		}
		View::ustaw('faktury', $przetworzone_faktury);
	}
	
	private function utworz_pozycje_do_pdf($pozycje) {
		$pozycje_pdf = '';
		foreach ($pozycje as $p) {
			$row = '<tr>';
			$row .= '<td class="with-light-border-cell" style="height: 35px">'.$p['ilosc'].'</td>';
			$row .= '<td class="with-light-border-cell" >'.$p['nazwa'].'</td>';
			$row .= '<td class="with-light-border-cell centered-cell">'.$p['netto'].'</td>';
			$row .= '<td class="with-light-border-cell centered-cell">'.$p['podatek'].'</td>';
			$row .= '<td class="with-light-border-cell centered-cell">'.$p['wartosc_podatku'].'</td>';
			$row .= '<td class="with-light-border-cell centered-cell">'.$p['brutto'].'</td>';
			$row .= '</tr>';
					
			$pozycje_pdf .= $row;
		}
		
		return $pozycje_pdf;
	}
	
	private function przygotuj_szablon($szablon, $mapowanie) {
		$przekonwertowany = $szablon;
		
		foreach ($mapowanie as $klucz => $wartosc) {
			$przekonwertowany = str_replace($klucz, $wartosc, $przekonwertowany);		
		}
		
		return $przekonwertowany;
	}
	
	public function pdf() {
		$id = $_GET['id'];
		if(empty($id)) {
			Session::ustaw_alert('error', 'Brak faktury o takim identyfikatorze');
			Router::przekierowanie(array('controller' => 'Faktury'));
		}
		
		include 'vendor/mpdf/mpdf.php';
		
		$szablon_faktury = file_get_contents('view/tmpl_pdf_faktura.php');
		$parametry = $this->przygotuj_dane_do_faktury($id);
		
		$mapowanie = array(
			'[[DATA_WYSTAWIENIA]]' => $parametry['faktura']['data_wystawienia'],
			'[[NADAWCA_NAZWA]]' => $parametry['nadawca']['nazwa'],
			'[[NADAWCA_ULICA]]' => $parametry['nadawca']['ulica'],
			'[[NADAWCA_KOD_POCZTOWY]]' => $parametry['nadawca']['kod_pocztowy'],
			'[[NADAWCA_MIEJSCOWOSC]]' => $parametry['nadawca']['miejscowosc'],
			'[[NADAWCA_EMAIL]]' => $parametry['nadawca']['email'],
			'[[ODBIORCA_NAZWA]]'  => $parametry['odbiorca']['nazwa'],
			'[[ODBIORCA_ULICA]]'   => $parametry['odbiorca']['ulica'],
			'[[ODBIORCA_KOD_POCZTOWY]]'   => $parametry['odbiorca']['kod_pocztowy'],
			'[[ODBIORCA_MIEJSCOWOSC]]'   => $parametry['odbiorca']['miejscowosc'],
			'[[FAKTURA_TYP]]' => $parametry['faktura']['typ'],
			'[[FAKTURA_NUMER]]' => $parametry['faktura']['numer'],
			'[[ZAMOWIENIE_IDENTYFIKATOR]]' => $parametry['zamowienie']['identyfikator'],
			'[[FAKTURA_DATA_ZAPLATY]]' => $parametry['faktura']['data_do_zaplaty'],
            '[[FAKTURA_POZYCJE]]' => $this->utworz_pozycje_do_pdf($parametry['faktura']['pozycje']),
	        '[[FAKTURA_RAZEM_NETTO]]' => $parametry['faktura']['razem_netto'],
			'[[FAKTURA_PODATEK]]' => $parametry['faktura']['podatek'],
			'[[FAKTURA_RAZEM_PODATEK]]' => $parametry['faktura']['razem_podatek'],
			'[[FAKTURA_RAZEM_BRUTTO]]' => $parametry['faktura']['razem_brutto'],
		);
		
		$szablon_faktury = $this->przygotuj_szablon($szablon_faktury, $mapowanie);
		
		$mpdf=new mPDF();
		$mpdf->Bookmark('Poczatek dokumentu');
		$mpdf->WriteHTML($szablon_faktury);
		$mpdf->Output($parametry['faktura']['numer'].'.pdf','D');
		die;
		
	}
	
	private function przygotuj_dane_do_faktury($id) {
		$faktura = $this->model->pobierz_fakture($id);
		$faktura = $faktura[0];
		
		$pozycje = $this->model->pobierz_pozycje_faktury($id);
		
		$przetworzone_pozycje = array();
		foreach ($pozycje as $p) {
			$element = array(
					'ilosc' => $p['poz_liczba'],
					'nazwa' => $p['poz_nazwa'],
					'netto' => $p['poz_cena_netto'],
					'podatek' => $p['poz_podatek'],
					'wartosc_podatku' => $p['poz_kwota_podatku'],
					'brutto' => $p['poz_cena_brutto'],
			);
				
			$przetworzone_pozycje[] = $element;
		}
		
		
		$zamowienie = $this->model->pobierz_identyfikator_zamowienia_na_podstawie_faktury($id);
		
		global $konfiguracja;
		$nadawca = $konfiguracja['hotel'];
		$podatek = $konfiguracja['tax']['pokoj'];
		
		$parametry = array(
				'faktura' => array(
						'data_wystawienia' => $faktura['fak_data_wystawienia'],
						'typ' => $faktura['fak_rodzaj'],
						'numer' => $faktura['fak_numer_identyfikacyjny'],
						'data_do_zaplaty' => $faktura['fak_data_zaplaty'],
						'pozycje' => $przetworzone_pozycje,
						'razem_netto' => $faktura['fak_naleznosc_ogolem_netto'],
						'razem_podatek' => $faktura['fak_naleznosc_ogolem_brutto'] - $faktura['fak_naleznosc_ogolem_netto'],
						'razem_brutto' => $faktura['fak_naleznosc_ogolem_brutto'],
						'podatek' => $podatek,
				),
				'zamowienie' => array(
						'identyfikator' => $zamowienie
				),
				'odbiorca' => array(
						'nazwa' => $faktura['fak_odbiorca_imie'].' '.$faktura['fak_odbiorca_nazwisko'],
						'ulica' => $faktura['fak_odbiorca_adres'],
						'kod_pocztowy' => $faktura['fak_odbiorca_kod_pocztowy'],
						'miejscowosc' => $faktura['fak_odbiorca_miejscowosc'],
				),
				'nadawca' => array(
						'nazwa' => $nadawca['nazwa'],
						'ulica' => $nadawca['ulica'],
						'kod_pocztowy' => $nadawca['kod_pocztowy'],
						'miejscowosc' => $nadawca['miejscowosc'],
						'email' => $nadawca['email']
				),
					
		);
		
		return $parametry;
	}
	 
	public function podglad() {
		View::ustaw('tytul_strony', 'Faktury');
		View::ustaw('podtytul_strony', 'Podgląd faktury');
		
		$id = $_GET['id'];
		if(empty($id)) {
			//
			Router::przekierowanie(array('controller' => 'Faktury'));
		}
		
		$parametry = $this->przygotuj_dane_do_faktury($id);
		View::ustaw('parametry', $parametry);
	}
	
}