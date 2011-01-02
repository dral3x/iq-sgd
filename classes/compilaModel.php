<?php

require_once (dirname(__FILE__) . '/pageModel.php');

require_once (dirname(__FILE__) . '/db_connector.php');
require_once (dirname(__FILE__) . '/model.php');

class Compilatore extends Page {
	
	private $error_message;
	
	public function getModelsAvailable() {
		$queryString = "SELECT id, nome FROM classe_documenti;";
		// TODO: aggiungere vincolo sul livello di segretezza che l'utente ha
		
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
	
	
	public function getDocumentModel($id) {
		// creo un oggetto Document con tutti i campi del modello, ma vuoti
		// e lo restituisco
		
		return null;
	}
	
	private function doInsertionQuerysAsTransaction($querys) {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		
		// inizio la transazione
		$dbc->begin_transaction();
		
		$success = true;
		// ciclo di query
		foreach ($querys as $query) {
			// interrogazione della tabella
			$success = $dbc->query($query, true);
			if (!$success) {
				$this->error_message = $dbc->getErrorMessage();
				break;
			}
		}

		if (!$success) {
			$dbc->rollback_transaction();
		} else {
			$dbc->commit();
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
		
		return $success;
	}
	
	// restituisce true se l'operazione  andata a buon fine, false altrimenti
	public function saveDocument($document) {
		// controlla il documento
		
		// generale le query SQL da fare
		$querys = array();

		// eseguo tutte le query o niente
		$success = $this->doInsertionQuerysAsTransaction($querys);
		
		return $success;
	}
}