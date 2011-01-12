<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/loginModel.php');

require_once (dirname(__FILE__) . '/db_connector.php');
require_once (dirname(__FILE__) . '/document.php');
class Ricerca extends Page {
	
	protected $search_result;
	public $search_error;
	protected $search_type;
	
	public function __construct() {
		parent::__construct();
		
		// ricerca di tipo semplice per default
		$this->setSimpleSearch();
	}
	
	// ricerca semplice: crea la query da sottoporre a interrogateDB()
	public function doSimpleSearch($strings) {
		
		//TODO: ric.semplice, controllare se "to lower case" serve
		$strings = $strings;
		
		//splittare stringa
		$keywords = explode(" ",$strings);
		
		$queryString = "SELECT DISTINCT d.id FROM documento AS d ".
						"INNER JOIN valori_campo_small AS vcs ON d.id = vcs.id_doc ".
						"INNER JOIN valori_campo_medium AS vcm ON d.id = vcm.id_doc ".
						"INNER JOIN valori_campo_long AS vcl ON d.id = vcl.id_doc ".
						"INNER JOIN campo AS c ON d.id = c.id ".
						"INNER JOIN classe_documenti AS cd ON cd.id = d.classe ".
						"WHERE ";
		
		//numero di parole inserite (per controllare se è già stata inserita una parola e serve AND)
		$j = 0;
		
		foreach ( $keywords  as $key ) {
			if ( $j > 0 ) {$queryString .= " AND "; }
			
			$key = "'%$key%'";
			
			$queryString .= "( vcs.valore_it LIKE  $key OR vcs.valore_eng LIKE  $key ".
			"OR vcs.valore_de LIKE  $key OR vcm.valore_it LIKE  $key ".
			"OR vcm.valore_eng LIKE  $key OR vcm.valore_de LIKE  $key ".
			"OR vcl.valore_it LIKE  $key OR vcl.valore_eng LIKE  $key ".
			"OR vcl.valore_de LIKE  $key OR c.nome_it LIKE  $key ".
			"OR c.nome_eng LIKE  $key OR c.nome_de LIKE  $key ".
			"OR cd.nome LIKE  $key ) ";
			
	   		$j++;
		}
		
		$this->interrogateDB($queryString);
		return $this->search_result;
	}
	
	// ricerca avanzata: crea la query da sottoporre a interrogateDB()
	public function doAdvancedSearch($partialQuery) {
		$queryString = "SELECT DISTINCT d.id FROM documento AS d ".$partialQuery ;
		
		$this->interrogateDB($queryString);
		return $this->search_result;
	}
	
	
	//esegue l'interrogazione del DB attraverso la query fornita
	public function interrogateDB($queryString) {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$raw_data = $dbc->query($queryString);
		
		/* hack momentaneo finché il db non è pronto */
		//$this->search_result = array('Documento DQ','Allegato A1');
		/* hack momentaneo finché il db non è pronto */
	
		if ($dbc->rows($raw_data)>0) {
			// chiamata alla funzione per l'estrazione dei dati
			$this->search_result = array();
			while ($doc = $dbc->extract_object($raw_data)) {
				array_push($this->search_result, new Document($doc->id));
			}
			unset($search_error);
		} else {
			$this->search_error = "La ricerca non ha prodotto risultati";
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}	
	
	public function setSimpleSearch() {
			$this->search_type = "simple";
	}
	
	public function setAdvancedSearch() {
			$this->search_type = "advanced";
	}
	
	public function getTypeOfSearch() {
		return $this->search_type;
	}
	
	/*
	public function addError($err) {
		$this->search_error .= $err;
	}
	
	public function getError() {
		return $this->search_error;
	}
	*/
}

?>