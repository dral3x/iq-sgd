<?php

require_once (dirname(__FILE__) . '/loginClass.php');
require_once (dirname(__FILE__) . '/dbClass.php');

class Ricerca extends Login {
	
	/* NOTA: ho lasciato varie parti provenienti da loginClass.php 
	 * che probabilmente non serviranno solo per avere un riferimento
	 */
	
	protected $a;
	protected $b;
	//CONTROLLARE se si può usare la proprietà omonima della superclasse Login!!!
	//protected $error_message;
	
	public function __construct() {
		parent::__construct();
		
		// controllo sul parametro d'invio
		if(isset($_POST['submit']) && (trim($_POST['submit']) == "Cerca")) {

			// controllo sui parametri inviati (per parametri che sono richiesti)
			if( !isset($_POST['a']) || $_POST['b']=="" ) {
				$this->error_message = "Attenzione, inserire b.";
			} elseif( !isset($_POST['a']) || $_POST['b'] =="") {
				$this->error_message = "Attenzione, inserire a.";
			} else {
				// validazione dei parametri tramite filtro per le stringhe
				$this->a = trim(filter_var($_POST['a'], FILTER_SANITIZE_STRING));
				$this->b = trim(filter_var($_POST['b'], FILTER_SANITIZE_STRING));
				
				unset($this->error_message);
			}
		}
	}

	//cerca i parametri inseriti interrogando il DB tramite una query
	public function dbSearch() {

		if (isset($this->a) && isset($this->b)) {
			
			// istanza della classe
			$dbc = new DBConnector();
			// chiamata alla funzione di connessione
			$dbc->connect();
			// interrogazione della tabella
			$raw_data = $dbc->query('');
			
			if ($dbc->rows($raw_data)>0) {
				// chiamata alla funzione per l'estrazione dei dati
				$res =  $dbc->extract_object($raw_data);
				unset($error_message);
			} else {
				$this->error_message = "La ricerca non ha prodotto risultati";
			}
			// disconnessione da MySQL
			$dbc->disconnect();
			
		}
	}
}