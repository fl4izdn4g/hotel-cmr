<?php

global $view_data;
global $view_blocks;

class View {
	public static function ustaw($key, $value) {
		global $view_data;
	
		$view_data[$key] = $value;
	}
	
	public static function pobierz($key) {
		global $view_data;
		if(!empty($view_data[$key])) {
			return $view_data[$key];
		}
		
		return null;
	}
	
	public static function pokaz_element($nazwa) {
		$partial_file = $nazwa.'_partial.php';
		include dirname(dirname(__FILE__)).'/view/'.$partial_file;
	}
	
	public static function zalacz_blok($nazwa_bloku, $zawartosc) {
		global $view_blocks;
		$view_blocks[$nazwa_bloku][] = $zawartosc;
	}
	
	public static function wyswietl_blok($nazwa_bloku) {
		global $view_blocks;
		if(!empty($view_blocks[$nazwa_bloku])) {
			foreach ($view_blocks[$nazwa_bloku] as $blok) {
				echo $blok;
			}
		}
	}
}