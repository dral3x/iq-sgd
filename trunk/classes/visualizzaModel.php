<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/document.php');
require_once (dirname(__FILE__) . '/security_levels.php');

class VisualizzaDocumento extends Page {
	
	public function __construct() {
		parent::__construct();

	}
	
	// cerca nel db tutti i documenti il cui stato è Draft e l'autore è uguale all'utente loggato in questo momento
	// restituisce un array di documenti
	public function retrieveDraftDocuments() {
//			// istanza della classe
//			$dbc = new DBConnector();
//			// chiamata alla funzione di connessione
//			$dbc->connect();
//			// interrogazione della tabella
//			$raw_data = $dbc->query('SELECT document_id, title, level FROM documents WHERE author = "' . $this->getSessionUser()->user_id . '" AND state="Draft";');		
//			
//			if ($dbc->rows($raw_data)>0) {
//				$documents = array();
//				// chiamata alla funzione per l'estrazione dei dati
//				while($res = $dbc->extract_object($raw_data, "Document")) {
//					// 	creazione del valore di sessione
//					$documents = new User($res->id_login, $username, $password, $res->level);
//				}
//			} else {
//				$documents = array();
//			}
//			
//			// disconnessione da MySQL
//			$dbc->disconnect();
		
			/* hack momentaneo finchè il db non è pronto */
			$document = new Document("ID1", "Titolo documento draft", SecurityLevel::LPUBLIC);
			$documents = array($document);
			/* hack momentaneo finchè il db non è pronto */
			
			return $documents;
	}
	
	// cerca nel db tutti i documenti il cui stato è WaitingApproval e il responsabile per l'approvazione è uguale all'utente loggato in questo momento
	// restituisce un array di documenti
	public function retrieveWaitingApprovalDocuments() {
//			// istanza della classe
//			$dbc = new DBConnector();
//			// chiamata alla funzione di connessione
//			$dbc->connect();
//			// interrogazione della tabella
//			$raw_data = $dbc->query('SELECT document_id, title, level FROM documents WHERE author = "' . $this->getSessionUser()->user_id . '" AND state="Draft";');		
//			
//			if ($dbc->rows($raw_data)>0) {
//				$documents = array();
//				// chiamata alla funzione per l'estrazione dei dati
//				while($res = $dbc->extract_object($raw_data, "Document")) {
//					// 	creazione del valore di sessione
//					$documents = new User($res->id_login, $username, $password, $res->level);
//				}
//			} else {
//				$documents = array();
//			}
//			
//			// disconnessione da MySQL
//			$dbc->disconnect();
		
			/* hack momentaneo finchè il db non è pronto */
			$document = new Document("ID2", "Titolo documento waitapproval", SecurityLevel::LPUBLIC);
			$documents = array($document);
			/* hack momentaneo finchè il db non è pronto */
			
			return $documents;		
	}
}