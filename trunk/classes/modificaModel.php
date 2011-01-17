<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/db_connector.php');
require_once (dirname(__FILE__) . '/user.php');

class Edit extends Page {
	
	public function getAllPossibleAuthors() {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT u.matricola, u.nome, u.cognome ".
				"FROM utente AS u ".
				"WHERE matricola > 1;"; // escludo l'amministratore di sistema
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
	
	public function generateUpdatedDocument($document_id, $fields) {
		
		// genero il nuovo documento
		$doc = new Document($document_id); // -1 significa che non ha ancora un ID prooprio, non  stato ancora salvato nel DB
		$doc->retrieveDocumentFromDB();
				
		// inserisco le informazioni base
		// data di creazione
		$doc->setCreationDate($fields['creation_day'], $fields['creation_month'], $fields['creation_year']);
		// revisione
		$doc->setRevision($fields['revisione']);
		// sede di archiviazione
		$doc->setLocation($fields['sede']);
		// livello di confidenzialitˆ
		$doc->setConfidentialLevel($fields['liv_conf']);
		
		// inserisco i campi generici dal modello
		foreach ($doc->getContent() as $field) {
			if (isset($fields[$field->getID()])) {
				$field_content = $fields[$field->getID()];
				$field_content = trim(filter_var($field_content, FILTER_SANITIZE_STRING));
				$field->setContent($field_content);
			}
		}
		
		// aggiungo gli autori
		$possible_author = $this->getAllPossibleAuthors();
		foreach ($possible_author as $a) {
			if ($fields['autore_' . $a->user_id] == "on")
				$doc->addAuthor($a);			
		}
		
		// approvatore
		$doc->setApprover(new User($fields['approvatore']));
		
		return $doc;
	}
	
}