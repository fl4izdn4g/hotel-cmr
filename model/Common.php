<?php
class Common {
	public function pobierz_wojewodztwa() {
		return array(
				'DOLNOSLASKIE' => 'dolnośląskie',
				'KUJAWSKO_POMORSKIE' =>'kujawsko-pomorskie',
				'LUBELSKIE' =>'lubelskie',
				'LUBUSKIE' =>'lubuskie',
				'LODZKIE' =>'łódzkie',
				'MALOPOLSKIE' =>'małopolskie',
				'MAZOWIECKIE' =>'mazowieckie',
				'OPOLSKIE' =>'opolskie',
				'PODKARPACKIE' =>'podkarpackie',
				'PODLASKIE' =>'podlaskie',
				'POMORSKIE' =>'pomorskie',
				'SLASKIE' =>'śląskie',
				'SWIETOKRZYSKIE' =>'świętokrzyskie',
				'WARMINSKO_MAZURSKIE' =>'warmińsko-mazurskie',
				'WIELKOPOLSKIE' =>'wielkopolskie',
				'ZACHODNIOPOMORSKIE' =>'zachodniopomorskie'
		);
	}
	
	public function pobierz_nazwe_wojewodztwa($kod_wojewodztwa) {
		$wojewodztwa = $this->pobierz_wojewodztwa();
		return $wojewodztwa[$kod_wojewodztwa];
	}
}