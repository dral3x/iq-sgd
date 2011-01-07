<?php
require_once (dirname(__FILE__) .'/document_field.php');

class DocumentState {
	const BOZZA = "bozza";
	const DA_APPROVARE = "approvazione";
	const DA_DISTRIBUIRE = "distribuzione";
	const APPROVATO = "approvato";
	const OBSOLETO = "obsoleto";
}

class Document {
	
	private $id; // id del documento nel db
	private $identifier; // identificatore standard, previsto dalla documentazione
	private $model_id; // classe del documento
	
	private $state; // DocumentState::BOZZA ad esempio
	private $confidential_level;
	private $approvatore;
	private $authors;
	
	private $day;
	private $month;
	private $year;
	
	private $content; // array di DocumentField
	
	public function __construct($id = NULL, $identifier = NULL, $confidential_level = NULL, $content = NULL) {
		if (!is_null($id)) $this->id = $id;
		if (!is_null($identifier)) $this->identifier = $identifier;
		if (!is_null($confidential_level)) $this->confidential_level = $confidential_level;
		if (!is_null($content)) $this->content = $content;
	}
	
	public function isValidID() {
		// controlla che il documento esista nel db
		$exist = false;

		if (isset($this->id)) {
			// istanza della classe
			$dbc = new DBConnector();
			// chiamata alla funzione di connessione
			$dbc->connect();
			// interrogazione della tabella
			$sql = "SELECT COUNT(d.id) as conteggio ".
					"FROM documento AS d ".
					"WHERE d.id = ".$this->id.";";
			$raw_data = $dbc->query($sql);
				
			if ($dbc->rows($raw_data)==1) {
				// chiamata alla funzione per l'estrazione dei dati
				$res = $dbc->extract_object($raw_data);
				$exist = ($res->conteggio == 1);
			}
				
			// disconnessione da MySQL
			$dbc->disconnect();
		}

		return $exist;
	}
	
	private function classID($id) {
		$code = "Unknown";
		switch($id) {
			case 1:
				$code = "A1";
				break;
			case 3:
				$code = "OA";
				break;
			case 4:
				$code = "PO";
				break;
			case 9:
				$code = "LN";
				break;
		
		}
		return $code;
		
		// TODO: da finire!
	}
	
	public function getIdentifier() {
		if (!isset($this->identifier)) {
			$this->retrieveGeneralInformation();
		}
		return $this->identifier;
	}
	
	private function generateIdentificator($classe, $revisione, $anno, $progressivo) {
		return $this->classID($classe).'-'.$revisione.'-'.$anno.'-'.str_pad($progressivo, 5, "0", STR_PAD_LEFT);
	}
	
	private function retrieveGeneralInformation() {
		// query sul db per estrarre le seguenti informazioni sul documento
		// titolo del documento
		// data di approvazione
		// stato
		// livello di confidenzialitˆ

		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT * ".
				"FROM documento AS d ".
				"WHERE d.id = ".$this->id.";";
		$raw_data = $dbc->query($sql);
			
		if ($dbc->rows($raw_data)==1) {
			// chiamata alla funzione per l'estrazione dei dati
			$res = $dbc->extract_object($raw_data);
			$this->identifier = $this->generateIdentificator($res->classe, $res->versione, $res->anno, $res->cont); // creo l'identificatore "classico"
			$this->state = $res->stato;
			$this->confidential_level = $res->liv_conf;
			$this->model_id = $res->classe;
			$this->day = $res->giorno;
			$this->month = $res->mese;
			$this->year = $res->anno;
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}
	
	private function retrieveCompleteDocument() {
		// query sul db per estrarre tutte le informazioni mancanti sul documento
		// tutti i contenuti inseriti nel documento, organizzati per chiavi (le chiavi saranno i nomi dei campi)
		$sql = "SELECT c.id, c.nome_it, c.tipo, cc.opzionale ".
				"FROM campo as c ".
				"INNER JOIN campo_classe as cc ON cc.id_campo = c.id ".
				"WHERE cc.id_classe = ".$this->model_id.";";
		
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$raw_data = $dbc->query($sql);
		
		$this->content = array();
		while ($data = $dbc->extract_object($raw_data)) {
			array_push($this->content, new DocumentField($data->id, $data->nome_it, $data->tipo, $data->opzionale==1));
		}
	
		// cerco i contenuti per i campi
		foreach ($this->content as $field) {
			$sql = "SELECT valore_it ".
			"FROM valori_campo_".$field->getType()." as t ".
			"WHERE t.id_doc = ".$this->id." AND t.id_campo = ".$field->getID().";";
			//echo $sql. "<br />\n";
			$raw_data = $dbc->query($sql);
			$data = $dbc->extract_object($raw_data);
			
			$field->setContent($data->valore_it);
		}		
		
		// disconnessione da MySQL
		$dbc->disconnect();	
		
	}
	
	public function getCreationDay() {
		if (!isset($this->day)) {
			$this->retrieveGeneralInformation();
		}
		return $this->day;
	}
	
	public function getCreationMonth() {
		if (!isset($this->month)) {
			$this->retrieveGeneralInformation();
		}
		return $this->month;
	}
	
	public function getCreationYear() {
		if (!isset($this->year)) {
			$this->retrieveGeneralInformation();
		}
		return $this->year;
	}
	
	public function getContent() {
		if (!isset($this->content)) {
			$this->retrieveCompleteDocument();
		}
		
		return $this->content;
	}
	
	public function setField($content) {
		$this->content = $content;
	}
	
	public function getConfidentialLevel() {
		if (!isset($this->$confidential_level)) {
			$this->retrieveGeneralInformation();
		}
		
		return $this->confidential_level;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function setModelID($model) {
		$this->model_id = $model;
	}
	
	public function getModelID() {
		return $this->model_id;
	}
	
	public function setState($state) {
		$this->state = $state;
	}
	
	private function retrieveAuthors() {
		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT u.matricola, u.nome, u.cognome ".
				"FROM autore AS a ".
				"INNER JOIN utente AS u ON a.mat_utente = u.matricola ".
				"WHERE a.id_doc = ".$this->id.";";
		$raw_data = $dbc->query($sql);
			
		$this->authors = array();
		
		if ($dbc->rows($raw_data)>0) {
			// chiamata alla funzione per l'estrazione dei dati
			while ($res = $dbc->extract_object($raw_data)) {
				array_push($this->authors, new User($res->matricola, $res->nome, $res->cognome));
			}
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}
	
	public function getAuthor() {
		if (!isset($this->authors)) {
			$this->retrieveAuthors();
		}
	
		$authors_string = "";
		foreach ($this->authors as $author) {
			$authors_string .= $author->getDisplayName() . ' ';
		}
		return $authors_string;
	}

	public function canBeEditedBy($author) {
		
		// il documento  ancora una bozza?
		if (!isset($this->state))
			$this->retrieveGeneralInformation();
		if ($this->state != DocumentState::BOZZA)
			return false;
			
		// il livello di confidenza dell'utente  sufficiente?
		if (!isset($this->confidential_level))
			$this->retrieveGeneralInformation();
		if ($author->getConfidentialLevel() > $this->confidential_level)
			return false;
		
		// l'utente  tra gli autori?
		if (!isset($this->authors))
			$this->retrieveAuthors();
		$found = false;
		foreach ($this->authors as $a) {
			$found = $found || ($a->equals($author));
		}
		
		return $found;
	}
	
	// esegue una serie di insert all'interno di una transazione
	// cos“ se un insert fallisce, tutto viene riportato allo stato pre-inserimento 
	private function doInsertionQuerysAsTransaction($querys) {

		$dbc = new DBConnector();
		$dbc->connect();
		
		// inizio la transazione
		$dbc->begin_transaction();
		
		$success = true;
		// ciclo di query
		foreach ($querys as $query) {
			// eseguo la singola query e verifico vada in porto
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
	public function saveDocumentIntoDB() {
		// controlla il documento
		
		// trovo il nuovo id per il documento se manca
		if (isset($this->id)) {
			$dbc = new DBConnector();
			$dbc->connect();
			$sql = "SELECT COUNT(id) as count FROM documento;";
			$raw_data = $dbc->query($query, true);
			$data = $dbc->extract_object($raw_data);
			$this->id = $data->count + 1;
			$dbc->disconnect();	
		}
		
		// trovo il progressivo per il documento in quella categoria
		$dbc = new DBConnector();
		$dbc->connect();
		$sql = "SELECT MAX(cont) as max FROM documento WHERE classe = ".$this->model_id.";";
		$raw_data = $dbc->query($query, true);
		$data = $dbc->extract_object($raw_data);
		$progressivo = $data->max + 1;
		$dbc->disconnect();	
		
		// generale le query SQL da fare
		$querys = array();
		
		// inserimento documento
		$sql = "INSERT INTO documento(id,cont,versione, anno,classe,mese,giorno,sede,stato,allegati,liv_conf,supp_it,supp_eng,supp_de,approvatore) ".
				"VALUES ('".$this->id."','".$progressivo."','".$this->revision."','".$this->year."','".$this->model_id."','".$this->month."','".$this->day."','$sede','".$this->state."','$allegati','$liv_conf','1','0','0', '".$this->approvatore->user_id."');";
		
		// inserimento gruppo di documento
		// es INSERT INTO doc_gruppo(id_gruppo,id_doc) VALUES ('$id_gruppo','$id')
		// TODO: in che gruppo dovrei mettere il documento? lo sceglie l'utente? Non  mica il livello di confidenzialitˆ questo...
		
		// inserimento autori
		foreach ($this->authors as $a) {
			$sql = "INSERT INTO autore (id_doc, mat_utente) ".
					"VALUES ('".$this->id."','".$a->user_id."');";
			array_push($querys, $sql);
		}
		
		// inserimento campi
		foreach ($this->content as $c) {
			$sql = "INSERT INTO valori_campo_".$c->getType()." (id_doc,id_campo,valore_it,valore_eng,valore_de) ".
					"VALUES ('".$this->id."','".$c->getID()."','".$c->getContent()."', NULL, NULL)";
			array_push($querys, $sql);
		}

		// eseguo tutte le query o niente
		$success = $this->doInsertionQuerysAsTransaction($querys);
		
		return $success;
	}

}