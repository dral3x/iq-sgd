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
	
	public function getAllPossibleAuthors() {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT u.matricola, u.nome, u.cognome ".
				"FROM utente AS u;";
		$raw_data = $dbc->query($sql);
			
		$users = array();
		// chiamata alla funzione per l'estrazione dei dati
		while ($res = $dbc->extract_object($raw_data)) {
			array_push($users, new User($res->matricola, $res->nome, $res->cognome));
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();	

		return $users;
	}
	
	public function generateDocumentFromModelWithData($model, $fields) {
		// genero il nuovo documento
		$doc = new Document(-1); // -1 significa che non ha ancora un ID prooprio, non  stato ancora salvato nel DB
		
		// dico al documento che modello ha
		$doc->setModelID($model->getID());
		
		// inserisco le informazioni base
		// data di creazione
		$doc->setCreationDate($fields['creation_day'], $fields['creation_month'], $fields['creation_year']);
		// versione
		$doc->setVersion($fields['versione']);
		// sede di archiviazione
		$doc->setLocation($fields['sede']);
		// livello di confidenzialitˆ
		$doc->setConfidentialLevel($fields['liv_conf']);
		
		// inserisco i campi generici dal modello
		$doc->setField($model->getFields());
		foreach ($doc->getContent() as $field) {
			if (isset($fields[$field->getID()])) {
				$field->setContent($fields[$field->getID()]);
			}
		}
		
		// aggiungo gli autori
		$possible_author = $this->getAllPossibleAuthors();
		foreach ($possible_author as $a) {
			if ($fields['autore_' . $a->user_id] == "on")
				$doc->addAuthor($a);			
		}
		
		// approvatore
		$doc->setApprover($fields['approvatore']);
		
		return $doc;
	}

}