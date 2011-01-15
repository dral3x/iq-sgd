<?php
require_once (dirname(__FILE__) . '/document_field.php');

class Model {
	
	private $id;
	private $version;
	private $name;
	private $fields;
	
	public function __construct($id, $version = NULL, $name = NULL) {
		$this->id = $id;
		if (!is_null($version)) {
			$this->version = $version;	
		} else {
			$this->retrieveLastVersion();
		}
		if (!is_null($name)) $this->name = $name;
	}
	
	private function retrieveLastVersion() {
		// query sul db per estrarre l'ultima versione di questo modello
		
		// FIXME: il db considera il campo versione come una stringa... come faccio a calcolare quale sia l'ultima versione?
		// per la demo considero una sola versione per ogni modello ma questa funzione andrˆ rivista in futuro.
		$this->version = "1.0";	
	}
	
	private function retrieveGeneralInformation() {
		// query sul db per estrarre le seguenti informazioni sul modello

		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT id, nome ".
				"FROM classe_documenti AS c ".
				"WHERE c.id = ".$this->id." AND c.versione = ".$this->version.";";
		$raw_data = $dbc->query($sql);
			
		if ($dbc->rows($raw_data)==1) {
			// chiamata alla funzione per l'estrazione dei dati
			$res = $dbc->extract_object($raw_data);
			$this->name = $res->nome;
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}
	
	private function retrieveFields() {
		// query sul db per estrarre tutte le informazioni mancanti sul documento
		// tutti i contenuti inseriti nel documento, organizzati per chiavi (le chiavi saranno i nomi dei campi)
		
		$sql = "SELECT c.id, c.nome_it, c.tipo, cc.opzionale ".
				"FROM campo as c ".
				"INNER JOIN campo_classe as cc ON cc.id_campo = c.id ".
				"WHERE cc.id_classe = ".$this->id." AND cc.versione = ".$this->version.";";
		
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$raw_data = $dbc->query($sql);
		
		$this->fields = array();
		while ($data = $dbc->extract_object($raw_data)) {
			array_push($this->fields, new DocumentField($data->id, $data->nome_it, $data->tipo, $data->opzionale==1));
		}
		
		// disconnessione da MySQL
		$dbc->disconnect();
	}
	
	public function getName() {
		if (!isset($this->name)) {
			$this->retrieveGeneralInformation();
		}
		return $this->name;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getVersion() {
		return $this->version;
	}
	
	public function getFields() {
		if (!isset($this->fields)) {
			$this->retrieveFields();
		}
		return $this->fields;		
	}
}