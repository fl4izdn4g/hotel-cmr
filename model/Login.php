<?php
class Login {
	public $pola = array(
		'rej_typ',
		'rej_imie',
		'rej_nazwisko',
		'rej_email',
		'rej_haslo',
		'rej_haslo_powtorz',
		'rej_regulamin',
		'rej_zgoda'
	);
		
	public $walidacja = array(
		'rej_typ' => array(
			'nie_pusty',	
		),
		'rej_imie' => array(
			'nie_pusty',
		),
		'rej_nazwisko' => array(
			'nie_pusty',
		),
		'rej_email' => array(
			'nie_pusty',
			'email',
			'pole_unikalne' => array(
				'pole' => 'kuz_email',
				'tabela' => 'hotel_konta_uzytkownikow'
			)
		),
		'rej_haslo' => array(
			'nie_pusty',
			'dlugosc' => array(
				'min' => 8
			),
			'haslo_to_samo' => array(
				'pole' => 'rej_haslo_powtorz'
			)
		),
		'rej_haslo_powtorz' => array(
			'nie_pusty',
			'haslo_to_samo' => array(
				'pole' => 'rej_haslo'
			)
		),
		'rej_regulamin' => array(
			'nie_pusty'	
		),
		'rej_zgoda' => array(
			'nie_pusty'
		)	
	);
	
	public function walidacja_remind_email() {
		$this->pola = array('email');
		$this->walidacja = array(
			'email' => array(
				'nie_pusty',
				'email',
			)
		);
	}
	
	public function pobierz_typy_rejestracji() {
		return array(
			'GOSC_HOTELU' => 'Chcę zarezerwować pokój',
			'GOSC_RESTAURACJA' => 'Chcę zarezerwować stolik w restauracji',
			'GOSC_POTRAWA' => 'Chcę zamówić potrawę'
		);
	}
	
	
	
	
}