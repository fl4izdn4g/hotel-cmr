<?php
class Zdjecia {
	public $pola = array(
		'zdj_tytul',
		'zdj_plik',
	);
	
	public $walidacja =  array(
		'zdj_tytul' => array(
			'nie_pusty',
		),
		'zdj_plik' => array(
			'nie_pusty',
		),
	);
	

}