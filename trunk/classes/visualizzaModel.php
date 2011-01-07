<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/document.php');
require_once (dirname(__FILE__) . '/security_levels.php');

class VisualizzaDocumento extends Page {
	
	public function __construct() {
		parent::__construct();

	}
	
	// cerca nel db tutti i documenti il cui stato  Draft e l'autore  uguale all'utente loggato in questo momento
	// restituisce un array di documenti
	public function retrieveDraftDocuments() {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT DISTINCT d.id, d.versione, d.sede, d.liv_conf, a.mat_utente ".
				"FROM documento AS d ".
				"INNER JOIN autore AS a ON d.id = a.id_doc ".
				"WHERE a.mat_utente = ".$this->getSessionUser()->user_id." AND d.stato LIKE '".DocumentState::BOZZA."'";
		$raw_data = $dbc->query($sql);
			
		$documents = array();
		
		if ($dbc->rows($raw_data)>0) {
			// aggiungo tutti i documenti all'array da restituire
			while($res = $dbc->extract_object($raw_data)) {
				array_push($documents, new Document($res->id));
			}
		}
					
		// disconnessione da MySQL
		$dbc->disconnect();
			
		return $documents;
	}
	
	// cerca nel db tutti i documenti il cui stato  WaitingApproval e il responsabile per l'approvazione  uguale all'utente loggato in questo momento
	// restituisce un array di documenti
	public function retrieveWaitingApprovalDocuments() {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT DISTINCT d.id ".
				"FROM documento AS d ".
				"WHERE d.approvatore LIKE '".$this->getSessionUser()->user_id."' AND d.stato LIKE '".DocumentState::DA_APPROVARE."';";
		$raw_data = $dbc->query($sql);
		
		$documents = array();
		
		if ($dbc->rows($raw_data)>0) {
			// aggiungo tutti i documenti all'array da restituire
			while($res = $dbc->extract_object($raw_data)) {
				array_push($documents, new Document($res->id));
			}
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
			
		return $documents;	
	}
	
	// cerca nel db tutte le revisioni dello stesso documento specificato
	// restituisce un array di documenti
	public function retrieveAllRevisionsOfDocuments($document_id) {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT DISTINCT d.id ".
				"FROM documento AS do ".
				"INNER JOIN documento AS d ON do.classe = d.classe AND do.cont = d.cont ".
				"WHERE do.id = ".$document_id.";";
		$raw_data = $dbc->query($sql);
		
		$documents = array();
		
		if ($dbc->rows($raw_data)>0) {
			// aggiungo tutti i documenti all'array da restituire
			while($res = $dbc->extract_object($raw_data)) {
				array_push($documents, new Document($res->id));
			}
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
			
		return $documents;			
	}
}