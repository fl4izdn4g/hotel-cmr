<?php
class StanMagazynowy {
	
	public $pola = array(
		'stm_prod_id',
		'stm_aktualny_stan',
		'stm_minimalny_dopuszczalny_stan'
	);
	
	public $walidacja = array(
			'stm_prod_id' => array(
				'nie_pusty'
			),
			'stm_aktualny_stan' => array(
				'nie_pusty',
				'liczba',
				'wiekszy_od' => 0
			),
			'stm_minimalny_dopuszczalny_stan' => array(
					'nie_pusty',
					'liczba',
					'wiekszy_od' => 0,
			)
	);	
	
	public function pobierz_stany_magazynowe() {
		$sql = "SELECT * FROM hotel_stan_magazynu";
		return Model::wykonaj_zapytanie_sql($sql);
	}
	
	public function pobierz_nazwe_produktu_dla_stanu_magazynowego($state_id) {
		$sql = "SELECT prod_nazwa FROM hotel_stan_magazynu JOIN hotel_produkty ON stm_prod_id = prod_id WHERE stm_id = ?";
		$parametry = array($state_id);
		$stan = Model::wykonaj_zapytanie_sql($sql, $parametry);
		
		return $stan[0];
	}
	
	public function aktualizuj_stan_magazynowy($state_id, $ilosc, $data_aktualizacji) {
		$sql = "SELECT * FROM hotel_stan_magazynu WHERE stm_id = ?";
		$parametry = array($state_id);
		$result = Model::wykonaj_zapytanie_sql($sql, $parametry);
		$stan = $result[0];
		
		$aktualny_stan = empty($stan['stm_aktualny_stan']) ? 0 : $stan['stm_aktualny_stan'];
		
		if(empty($data_aktualizacji)) {
			$sql = "UPDATE hotel_stan_magazynu SET stm_aktualny_stan = ? WHERE stm_id = ?";
			$nowa_ilosc = (int)$aktualny_stan + $ilosc;
			
			$parametry = array($nowa_ilosc, $state_id);
		}
		else {
			$sql = "UPDATE hotel_stan_magazynu SET stm_aktualny_stan = ?, stm_data_ostatniego_uzupelnienia = ? WHERE stm_id = ?";
			$nowa_ilosc = (int)$aktualny_stan + $ilosc;
			
			$parametry = array($nowa_ilosc, $data_aktualizacji, $state_id);
		}
		
		Model::wykonaj_zapytanie_sql($sql, $parametry);
	}

	
}