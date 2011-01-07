<?php

require_once (dirname(__FILE__) . '/pageModel.php');

require_once (dirname(__FILE__) . '/db_connector.php');
require_once (dirname(__FILE__) . '/model.php');
require_once (dirname(__FILE__) . '/document.php');
require_once (dirname(__FILE__) . '/document_field.php');

class Compilatore extends Page {
	
	private $error_message;
	
	public function getModelsAvailable() {
		$queryString = "SELECT id, nome FROM classe_documenti;";
		// TODO: aggiungere vincolo sul livello di confidenzialitˆ che l'utente ha
		
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$raw_data = $dbc->query($queryString);
		
		$results = array();
		while ($data = $dbc->extract_object($raw_data)) {
			array_push($results, new Model($data->id, $data->nome));
		}
		
		// disconnessione da MySQL
		$dbc->disconnect();
		
		return $results;
	}
	
	public function generateDocumentFromModelWithData($model, $fields) {
		$doc = new Document();
		$doc->setModelID($model->getID());
		$doc->setField($model->getFields());
		
		foreach ($doc->getContent() as $field) {
			if (isset($fields[$field->getID()])) {
				$field->setContent($fields[$field->getID()]);
			}
		}
		
		return $doc;
	}

}