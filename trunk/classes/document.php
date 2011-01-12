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

	private $version;
	private $archived_location;
	
	private $day;
	private $month;
	private $year;
	
	private $content; // array di DocumentField
	
	public function __construct($id, $identifier = NULL, $confidential_level = NULL, $content = NULL) {
		$this->id = $id;
		if (!is_null($identifier)) $this->identifier = $identifier;
		if (!is_null($confidential_level)) $this->confidential_level = $confidential_level;
		if (!is_null($content)) $this->content = $content;
	}
	
	// controlla se l'ID del documento  presente nel db
	public function isValidID() {
		$exist = false;

		if (isset($this->id)) {
			// connessione al db
			$dbc = new DBConnector();
			$dbc->connect();
			
			// interrogazione della tabella
			$sql = "SELECT COUNT(d.id) as conteggio ".
					"FROM documento AS d ".
					"WHERE d.id = ".$this->id.";";
			$raw_data = $dbc->query($sql);
				
			if ($dbc->rows($raw_data)==1) {
				// c' una sola riga, mi pare giusto!
				$res = $dbc->extract_object($raw_data);
				$exist = ($res->conteggio == 1);
			}
				
			// disconnessione dal db
			$dbc->disconnect();
		}

		return $exist;
	}
	
	// restitusce il codice a 2 caratteri che identifica una classe di documento
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
	
	// restitusce l'identificatore di un documento, come previsto dalla relazione sulla documentazione
	// il codice viene generato  retrieveGeneralInformation(), qui viene solo restituito
	public function getIdentifier() {
		if (!isset($this->identifier)) {
			$this->retrieveGeneralInformation();
		}
		return $this->identifier;
	}
	
	// genera l'identificatore di un documento, come previsto dalla relazione sulla documentazione
	// <classe>-<versione>-<anno>-<progressivo>
	private function generateIdentificator($classe, $revisione, $anno, $progressivo) {
		return $this->classID($classe).'-'.$revisione.'-'.$anno.'-'.str_pad($progressivo, 5, "0", STR_PAD_LEFT);
	}
	
	private function retrieveGeneralInformation() {
		// query sul db per estrarre le informazioni base sul documento

		// istanza della classe
		$dbc = new DBConnector();
		$dbc->connect();
		
		// interrogazione della tabella
		$sql = "SELECT * ".
				"FROM documento AS d ".
				"WHERE d.id = ".$this->id.";";
		$raw_data = $dbc->query($sql);
			
		if ($dbc->rows($raw_data)==1) {
			// estraggo dal db...
			$res = $dbc->extract_object($raw_data);
			// estraggo e genero l'identificatore "classico" (vedi documentazione relativa)
			$this->identifier = $this->generateIdentificator($res->classe, $res->versione, $res->anno, $res->cont);
			
			// stato
			$this->state = $res->stato;
			
			// livello di confidenza
			$this->confidential_level = $res->liv_conf;
			
			// classe di appartenenza
			$this->model_id = $res->classe;
			
			// data di creazione 
			$this->day = $res->giorno;
			$this->month = $res->mese;
			$this->year = $res->anno;
			
			// versione del documento
			$this->version = $res->versione;
			
			// sede di archiviazione
			$this->archived_location = $res->sede;
			
			// utente approvatore del documento
			$this->approvatore = new User($res->approvatore);
		}
			
		// disconnessione dal db
		$dbc->disconnect();
	}
	
	// metodo per forzare lo scaricamento delle informazioni di un documento dal db... da usare con parsimonia
	public function retrieveDocumentFromDB() {
		$this->retrieveGeneralInformation();
		$this->retrieveCompleteDocument();
	}
	
	private function retrieveCompleteDocument() {
		// query sul db per estrarre tutti i campi del documento (campi e relativi contenuti)
		// FIXME: per ora scarica solo le scritte in italiano, non supporta ancora le 3 lingue
		
		// istanza della classe
		$dbc = new DBConnector();
		$dbc->connect();
		
		// interrogazione della tabella
		$sql = "SELECT c.id, c.nome_it, c.tipo, cc.opzionale ".
				"FROM campo as c ".
				"INNER JOIN campo_classe as cc ON cc.id_campo = c.id ".
				"WHERE cc.id_classe = ".$this->model_id.";";
		$raw_data = $dbc->query($sql);
		
		$this->content = array();
		while ($data = $dbc->extract_object($raw_data)) {
			array_push($this->content, new DocumentField($data->id, $data->nome_it, $data->tipo, $data->opzionale==1));
		}
	
		// cerco i contenuti dei campi nelle tabelle giuste
		// dipende dalla dimensione del campo (small, medium o large)
		foreach ($this->content as $field) {
			$sql = "SELECT valore_it ".
					"FROM valori_campo_".$field->getType()." as t ".
					"WHERE t.id_doc = ".$this->id." AND t.id_campo = ".$field->getID().";";

			$raw_data = $dbc->query($sql);
			$data = $dbc->extract_object($raw_data);
			
			$field->setContent($data->valore_it);
		}		
		
		// disconnessione dal db
		$dbc->disconnect();	
		
	}
	
	private function retrieveFieldsFromModel() {
		// query sul db per estrarre solo i campi relativi al modello di questo documento (no contenuti)
		
		// istanza della classe
		$dbc = new DBConnector();
		$dbc->connect();

		// interrogazione della tabella
		$sql = "SELECT c.id, c.nome_it, c.tipo, cc.opzionale ".
				"FROM campo as c ".
				"INNER JOIN campo_classe as cc ON cc.id_campo = c.id ".
				"WHERE cc.id_classe = ".$this->model_id.";";
		$raw_data = $dbc->query($sql);
		
		$this->content = array();
		while ($data = $dbc->extract_object($raw_data)) {
			array_push($this->content, new DocumentField($data->id, $data->nome_it, $data->tipo, $data->opzionale==1));
		}
		
		// disconnessione dal db
		$dbc->disconnect();
	}
	
	// ritorna il giorno di creazione del documento
	public function getCreationDay() {
		if (!isset($this->day)) {
			$this->retrieveGeneralInformation();
		}
		return $this->day;
	}

	// ritorna il mese di creazione del documento
	public function getCreationMonth() {
		if (!isset($this->month)) {
			$this->retrieveGeneralInformation();
		}
		return $this->month;
	}
	
	// ritorna l'anno di creazione del documento
	public function getCreationYear() {
		if (!isset($this->year)) {
			$this->retrieveGeneralInformation();
		}
		return $this->year;
	}
	
	// imposta la data (giorno, mese, anno) di creazione del documento
	public function setCreationDate($day, $month, $year) {
		$this->day = $day;
		$this->month = $month;
		$this->year = $year;
	}
	
	// restituisce la versione del documento
	public function getVersion() {
		if (!isset($this->version)) {
			$this->retrieveGeneralInformation();
		}
		return $this->version;		
	}
	
	// imposta il numero di versione del documento
	public function setVersion($version) {
		$this->version = $version;
	}
	
	// restituisce la sede di archiviazione del documento
	public function getLocation() {
		if (!isset($this->archived_location)) {
			$this->retrieveGeneralInformation();
		}
		return $this->archived_location;		
	}
	
	// imposta la sede di archiviazione del documento
	public function setLocation($location) {
		$this->archived_location = $location;
	}
	
	// restituisce l'utente che deve approvare/ha approvato il documento
	public function getApprover() {
		if (!isset($this->approvatore)) {
			$this->retrieveGeneralInformation();
		}
		return $this->approvatore;
	}

	// imposta l'utente approvatore del documento
	public function setApprover($user) {
		$this->approvatore = $user;
	}
	
	// restituisce i campi del documento
	public function getContent() {
		if (!isset($this->content)) {
			$this->retrieveCompleteDocument();
		}
		return $this->content;
	}
	
	// imposta i campi del documento
	public function setField($content) {
		$this->content = $content;
	}
	
	// restituisce il livello di confidenzialitˆ che il documento ha
	public function getConfidentialLevel() {
		if (!isset($this->$confidential_level)) {
			$this->retrieveGeneralInformation();
		}
		return $this->confidential_level;
	}
	
	public function setConfidentialLevel($level) {
		$this->confidential_level = $level;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function setModelID($model) {
		$this->model_id = $model;
		if (!isset($this->content)) {
			$this->retrieveFieldsFromModel();
		}
	}
	
	public function getModelID() {
		return $this->model_id;
	}
	
	public function setState($state) {
		$this->state = $state;
	}
	
	public function getState() {
		if (!isset($this->state)) {
			$this->retrieveGeneralInformation();
		}
		return $this->state;
	}
	
	// recupera dal db l'elenco degli autori del documento
	private function retrieveAuthors() {
		// connessione al db
		$dbc = new DBConnector();
		$dbc->connect();
		
		// interrogazione della tabella
		$sql = "SELECT u.matricola, u.nome, u.cognome ".
				"FROM autore AS a ".
				"INNER JOIN utente AS u ON a.mat_utente = u.matricola ".
				"WHERE a.id_doc = ".$this->id.";";
		$raw_data = $dbc->query($sql);
		
		// estraggo gli utenti e li inserisco nell'array authors
		$this->authors = array();
		while ($res = $dbc->extract_object($raw_data)) {
			array_push($this->authors, new User($res->matricola, $res->nome, $res->cognome));
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}
	
	// ritorna una stringa che descrive gli autori del documento
	// formato: <nome1> <cognome1>, <nome2> <cognome2>, ecc...
	public function getAuthor() {
		if (!isset($this->authors)) {
			$this->retrieveAuthors();
		}
	
		$authors_string = "";
		foreach ($this->authors as $author) {
			if ($authors_string == "")
				$authors_string = $author->getDisplayName();
			else
				$authors_string .= ', ' . $author->getDisplayName();
		}
		return $authors_string;
	}
	
	// restituisce l'elenco degli autori
	public function getAuthors() {
		if (!isset($this->authors)) {
			$this->retrieveAuthors();
		}
		return $this->authors;
	}
	
	// aggiunge un utente alla lista degli autori del documento
	public function addAuthor($author) {
		if (!isset($this->authors))
			$this->authors = array();
		
		array_push($this->authors, $author);
	}

	// esegue dei test per verificare se $author pu˜ effettivamente modificare questo documento
	// controlla lo stato del documento, l'essere tra gli auturi ed avere un livello di confidenzialitˆ sufficiente
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
	
	// esegue una serie di query all'interno di una transazione
	// cos“ se una fallisce, tutto viene riportato allo stato prima dell'esecuzione della prima
	private function doQuerysAsTransaction($querys) {

		$dbc = new DBConnector();
		$dbc->connect();
		
		// inizio la transazione
		$dbc->begin_transaction();
		
		$success = true;
		// ciclo di query
		foreach ($querys as $query) {
			 //echo "<p>$query";
			// eseguo la singola query e verifico vada in porto
			$success = $dbc->query($query, true);
			if (!$success) {
				$this->error_message = $dbc->getErrorMessage();
				//echo " -> ERRORE: ".$this->error_message."</p>\n";
				break;
			//} else {
				//echo " -> OK</p>\n";
			}
		}

		// termino la transazione, bene o mane a seconda del successo delle query
		if (!$success) {
			$dbc->rollback_transaction();
		} else {
			$dbc->commit_transaction();
		}
			
		// disconnessione dal db
		$dbc->disconnect();
		
		return $success;
	}
	
	// restituisce true se l'operazione  andata a buon fine, false altrimenti
	public function saveDocumentIntoDB() {
		// controlla il documento
		$success = false;

		// trovo il nuovo id per il documento se manca
		if ($this->id < 0) { // il documento non  mai stato salvato, devo effettuare degli INSERT
			$dbc = new DBConnector();
			$dbc->connect();
		
			$sql = "SELECT COUNT(id) as count FROM documento;";
			$raw_data = $dbc->query($sql, true);
			$data = $dbc->extract_object($raw_data);
			$this->id = $data->count + 1;

			$sql = "SELECT MAX(cont) as max FROM documento WHERE classe = ".$this->model_id.";";
			$raw_data = $dbc->query($sql, true);
			$data = $dbc->extract_object($raw_data);
			$progressivo = $data->max + 1;
			$dbc->disconnect();

			// generale le query SQL da fare
			$querys = array();

			// inserimento documento
			$sql = "INSERT INTO documento(id,cont,versione, anno,classe,mese,giorno,sede,stato,allegati,liv_conf,supp_it,supp_eng,supp_de,approvatore) ".
					"VALUES ('".$this->id."','".$progressivo."','".$this->version."','".$this->year."','".$this->model_id."','".$this->month."','".$this->day."','".$this->archived_location."','".$this->state."','0','".$this->confidential_level."','1','0','0', '".$this->approvatore->user_id."');";
			array_push($querys, $sql);

			// inserimento gruppo di documento
			// es INSERT INTO doc_gruppo(id_gruppo,id_doc) VALUES ('$id_gruppo','$id')
			// TODO: in che gruppo dovrei mettere il documento? lo sceglie l'utente? Non  mica il livello di confidenzialitˆ questo...
			// lasciamo pure vuoto, questo serve a fare gruppi di documento, direi di non implementarlo per la demo, rischiamo di farlo male

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
			$success = $this->doQuerysAsTransaction($querys);

		} else { // il documento  giˆ presente nel db, devo fare degli UPDATE

			// generale le query SQL da fare
			$querys = array();

			// aggiornamento documento
			$sql = "UPDATE documento SET ".
					"mese = '".$this->month."', giorno = '".$this->day."', anno = '".$this->year."', ".
					"sede = '".$this->archived_location."', stato = '".$this->state."', ".
					"liv_conf = '".$this->confidential_level."', approvatore = '".$this->approvatore->user_id."', ".
					"versione = '".$this->version."' ".
					"WHERE id = '".$this->id."';";
			array_push($querys, $sql);
			// FIXME: non viene aggiornato il numero di allegati, il supporto alle lingue 

			// aggiornamento gruppo di appartenenza
			// es INSERT INTO doc_gruppo(id_gruppo,id_doc) VALUES ('$id_gruppo','$id')
			// TODO: in che gruppo dovrei mettere il documento? lo sceglie l'utente? Non  mica il livello di confidenzialitˆ questo...
			// lasciamo pure vuoto, questo serve a fare gruppi di documento, direi di non implementarlo per la demo, rischiamo di farlo male

			// aggiornamento autori
			// elimino gli autori precedenti
			$sql = "DELETE FROM autore WHERE id_doc = '".$this->id."';";
			array_push($querys, $sql);
			// inserisco i nuovi
			foreach ($this->authors as $a) {
				$sql = "INSERT INTO autore (id_doc, mat_utente) ".
						"VALUES ('".$this->id."','".$a->user_id."');";
				array_push($querys, $sql);
			}

			// aggiorno i campi del documento
			// elimino i campi precedenti
			$sql = "DELETE FROM valori_campo_small WHERE id_doc = '".$this->id."';";
			array_push($querys, $sql);
			$sql = "DELETE FROM valori_campo_medium WHERE id_doc = '".$this->id."';";
			array_push($querys, $sql);
			$sql = "DELETE FROM valori_campo_long WHERE id_doc = '".$this->id."';";
			array_push($querys, $sql);
			// inserisco i nuovi
			foreach ($this->content as $c) {
				$sql = "INSERT INTO valori_campo_".$c->getType()." (id_doc,id_campo,valore_it,valore_eng,valore_de) ".
						"VALUES ('".$this->id."','".$c->getID()."','".$c->getContent()."', NULL, NULL)";
				array_push($querys, $sql);
			}

			// eseguo tutte le query o niente
			$success = $this->doQuerysAsTransaction($querys);
			
			
		}

		return $success;
	}

}