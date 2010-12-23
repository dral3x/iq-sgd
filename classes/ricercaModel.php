<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/loginModel.php');

require_once (dirname(__FILE__) . '/db_connector.php');

class Ricerca extends Page {
	
	private $session;
	
	protected $search_result;
	protected $search_error;
	
	public function __construct() {
		parent::__construct();

		// controllo che la sessione sia valida
		$this->session = new LoginSession();
		if (!$this->session->userIsLogged()) { // un utente ш arrivato qua senza essere loggato, lo rispedisco a casa
			header("Location: login.php");
		}

	}
	
	public function getSessionUser() {
		return $this->session->getUser();
	}
	
	
	// ricerca semplice: crea la query da sottoporre a interrogateDB()
	public function doSimpleSearch($keys) {
		$queryString = 'SELECT ';
		
		/* TO DO:
		 * estrae chiavi di ricerca da $keys e le concatena a $queryString
		 */
		
		interrogateDB($queryString);
	}
	
	// ricerca avanzata: crea la query da sottoporre a interrogateDB()
	//	NB: la ricerca usa $_POST per comoditра (DA CONTROLLARE SE FUNZIONA!)
	public function doAdvancedSearch() {
		$queryString = 'SELECT ';
		
		/* TO DO:
		 * estrae chiavi di ricerca dai vari $_POST[''] e le concatena a $queryString
		 */
		
		interrogateDB($queryString);
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
			$this->search_result = ['Documento DQ','Allegato A1'];
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
	public function noParameterIsSet() {
		//controlli sui vari $_POST['']
		return false;
	}
	
	public function typeOfSearch() {
		return "simple";
		// bisogna gestire il caso in cui si voglia una ricerca acanzata
		// return "advanced";
	}
	
	
}

?>