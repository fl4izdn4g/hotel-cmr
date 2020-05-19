<?php
class Router {
	public function extract() {
		global $konfiguracja;
		
		$get_data = $_GET;
		$request = array();
		
		foreach ($get_data as $key => $value) {
			if($key != 'controller' && $key != 'action') {
				$request['params'][$key] = $value;
			}
			
			$request[$key] = $value;
		}
		
		if(!key_exists('controller', $request)) {
			$controller_name = $konfiguracja['default_controller'];
			$request['controller'] = $controller_name;
		}
		
		if(!key_exists('action', $request)) {
			$request['action'] = 'lista';
		}
				
		return $request;
	}
	
	
	public static function utworz_link($parametry) {
		$href = 'index.php?';
		
		$query_params = array();
		foreach ($parametry as $klucz => $wartosc) {
			$query_params[] = $klucz.'='.$wartosc;
		}
		 
		$href .= implode('&', $query_params);
		
		return $href;
	}
	
	public static function przekierowanie($url, $statusCode = 303)
	{
		$to_redirect = $url;
		if(is_array($url)) {
			$to_redirect = Router::utworz_link($url); 
		}
		
		
		header('Location: ' . $to_redirect, true, $statusCode);
		die();
	}
	
	public static function poprawny_url_obrazka($obrazek, $kontroler, $tryb = 'original') {
		global $konfiguracja;
		
		$obrazek_elementy = explode('.', $obrazek);
		if($tryb == 'small') {
			return $konfiguracja['upload_url'].$konfiguracja['upload_groups'][$kontroler].'/'.$obrazek_elementy[0].'_small.'.$obrazek_elementy[1];
		}
		if($tryb == 'medium') {
			return $konfiguracja['upload_url'].$konfiguracja['upload_groups'][$kontroler].'/'.$obrazek_elementy[0].'_medium.'.$obrazek_elementy[1];
		}
		
		return $konfiguracja['upload_url'].$konfiguracja['upload_groups'][$kontroler].'/'.$obrazek;
	}
	
	public static function pobierz_grupe_obrazkow($kontroler) {
		global $konfiguracja;
		return $konfiguracja['upload_groups'][$kontroler];
	}
	
	public static function zakoduj_link($link) {
		$proper_link = $link;
		if(is_array($link)) {
			$proper_link = Router::utworz_link($link);
		}
		
		return $proper_link;
	}
	
	public static function zdekoduj_link($link) {
		return base64_decode($link);
	}
}