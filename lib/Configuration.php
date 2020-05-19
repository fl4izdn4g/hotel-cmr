<?php
class Configuration {
	
	private $configuration;
	
	public static function load() {
		$this->configuration = include '../konfiguracja.php';
	}
	
	public static function get($element) {
		if(key_exists($element, $this->configuration)) {
			return $this->configuration[$element];
		}
	}
	
	public static function set($element, $value) {
		$this->configuration[$element] = $value;
	}
}