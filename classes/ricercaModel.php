<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/loginModel.php');

require_once (dirname(__FILE__) . '/db_connector.php');

class Ricerca extends Page {
	
	protected $search_result;
	protected $search_error;
	protected $search_type;
	
	public function __construct() {
		parent::__construct();
		
		// ricerca di tipo semplice per default
		$this->setSimpleSearch();
	}
	
	// ricerca semplice: crea la query da sottoporre a interrogateDB()
	public function doSimpleSearch($keys) {
		$queryString = 'SELECT ';
		
		/* TODO: estrae chiavi di ricerca da $keys e le concatena a $queryString
		 */
		
		$this->interrogateDB($queryString);
	}
	
	// ricerca avanzata: crea la query da sottoporre a interrogateDB()
	public function doAdvancedSearch($partialQuery) {
		$queryString = 'SELECT '.$partialQuery ;
		
		/* TODO: concatena la query parziale a $queryString
		 */
		
		$this->interrogateDB($queryString);
	}
	
	
	//esegue l'interrogazione del DB attraverso la query fornita
	public function interrogateDB($queryString)
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$raw_data = $dbc->query($queryString);
		
		/* hack momentaneo finchщ il db non ш pronto */
		$this->search_result = array('Documento DQ','Allegato A1');
		/* hack momentaneo finchщ il db non ш pronto */
		
	/*
		if ($dbc->rows($raw_data)>0) {
			// chiamata alla funzione per l'estrazione dei dati
			$search_result =  $dbc->extract_object($raw_data);
			unset($search_error);
		} else {
			$this->search_error = "La ricerca non ha prodotto risultati";
		}
	*/
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}
	
	
	// true se nessun parametro della ricerca avanzata ш stato impostato
	// false se almeno un parametro della ricerca avanzato ш stato impostato
	//	NB: usa $_POST per comoditра (DA CONTROLLARE SE FUNZIONA!)
	// ATTENZIONE!!!!
	// il modello non deve accedere alle variabili globali $_POST o $_GET... solo il controller deve farlo.
	// questo controllo va fatto in ricerca.php ... crea una funzione a posta e usala lИ!
	public function noParameterIsSet() {
		//controlli sui vari $_POST['']
		
		
		
		return false;
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
	
}

?>