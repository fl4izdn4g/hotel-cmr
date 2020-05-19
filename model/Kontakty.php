<?php
class Kontakty {
	
	
	
	public function pobierz_wszystkie_kontakty($ile = null) {
		$sql = "SELECT * FROM hotel_kontakty ORDER BY CASE WHEN kon_data_przeczytania IS NULL THEN 1 ELSE 0 END DESC, kon_data_dodania DESC ";
		if(!empty($ile) && is_numeric($ile)) {
			$sql .= " LIMIT ".$ile;
		}
		
		return Model::wykonaj_zapytanie_sql($sql);
		
	}
	
	public function pobierz_kontakt($id) {
		$sql = "SELECT kon_email, kon_kategoria, kon_opis, kon_tytul, kon_data_dodania, kon_data_przeczytania, kodp_data_odpowiedzi as kon_data_odpowiedzi FROM hotel_kontakty LEFT JOIN hotel_kontakt_odpowiedz ON kodp_kon_id = kon_id WHERE kon_id = ?";
		$parametry = array($id);
		return Model::wykonaj_zapytanie_sql($sql, $parametry);
	}
	
	public function pobierz_kategorie() {
		return array(
			'OPINIA' => 'Opinia',
			'PROPOZYCJA' => 'Propozycja',
			'SKARGA' => 'Skarga'
		);	
	}
	
	
	public function pobierz_kontakty($ile) {
		$sql = "SELECT * FROM hotel_kontakty";
	
		return array(
				array(
						'kon_id' => 1,
						'kon_tytul' => 'KK1',
						'kon_kategoria' => 'OPINIA',
						'kon_opis' => 'To jest opis',
						'kon_email' => 'test@test.pl',
						'kon_data_dodania' => '2016-03-20',
						'kon_data_przeczytania' => null
				),
				
				array(
						'kon_id' => 1,
						'kon_tytul' => 'KK1',
						'kon_kategoria' => 'OPINIA',
						'kon_opis' => 'To jest opis',
						'kon_email' => 'test@test.pl',
						'kon_data_dodania' => '2016-03-20',
						'kon_data_przeczytania' => null
				),
				
				array(
						'kon_id' => 1,
						'kon_tytul' => 'KK1',
						'kon_kategoria' => 'OPINIA',
						'kon_opis' => 'To jest opis',
						'kon_email' => 'test@test.pl',
						'kon_data_dodania' => '2016-03-20',
						'kon_data_przeczytania' => '2016-03-20'
				),
					
		);
	}
	
	
	public function policz_nieprzeczytane() {
		$sql = "SELECT COUNT(*) as ile FROM hotel_kontakty WHERE kon_data_przeczytania IS NULL";
		$result = Model::wykonaj_zapytanie_sql($sql);
		return $result[0]['ile'];
	}
	
	
}