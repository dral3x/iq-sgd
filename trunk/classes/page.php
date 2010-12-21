<?php

class Page {
	
	public function __construct() {
		// inizializzazione della sessione
		session_start();
	}
	
	public static function getHeader() {
		return "<div id=\"header\">Questo è l'header della pagina</div>\n";
	}
	
	public static function getFooter() {
		return "<div id=\"footer\"></div>\n";
	}
	
}

?>