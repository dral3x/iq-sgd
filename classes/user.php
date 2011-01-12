<?php

require_once (dirname(__FILE__) . '/security_levels.php');
require_once (dirname(__FILE__) . '/db_connector.php');
class User {
	
	public $user_id;
	public $name;
	public $surname;
	public $username;
	public $password;
	private $confidential_level;
	
	// necessari user, pass e user_id, il livello di default  il pi basso... ossia accede ai soli documenti pubblici
	public function __construct($user_id, $name = NULL, $surname = NULL, $user = NULL, $pass = NULL, $level = SecurityLevel::LPUBLIC) {
		
		$this->user_id = $user_id;

		if (!is_null($user)) $this->username = $user;
		if (!is_null($pass)) $this->password = $pass;
			
		$this->confidential_level = $level;
		
		if (is_null($name) || is_null($surname)) {
			$this->retrieveBasicInformationFromDB();
		} else {
			$this->name = $name;
			$this->surname = $surname;
		}
	}
	
	// serializzazione di uno User
	public function __sleep() {
        // serializzazione delle proprietˆ
        return array('user_id', 'name', 'surname', 'username', 'password', 'confidential_level');
    }
     
    // deserializzazione di uno User
    public function __wakeup() {
        //$this->connect();
    }
    
//    public function __toString() {
//    	return $this->getDisplayName();
//    }
	
	public function getConfidentialLevel() {
		return $this->confidential_level;
	}
	
	public function getDisplayName() {
		if (!isset($this->name) || !isset($this->surname)) {
			$this->retrieveBasicInformationFromDB();
		}
		return $this->name . ' ' . $this->surname;
	}
	
	public function equals($another) {
		return $another->user_id == $this->user_id;
	}
	
	public function is_in($array) {
		$found = false;
		foreach ($array as $element) {
			$found = $this->equals($element);
			if ($found) break;
		}
		return $found;
	}
	
	private function retrieveBasicInformationFromDB() {
		// query sul db per estrarre le seguenti informazioni sull'utente
		// nome
		// cognome

		// istanza della classe
		$dbc = new DBConnector();
		// chiamata alla funzione di connessione
		$dbc->connect();
		// interrogazione della tabella
		$sql = "SELECT nome, cognome ".
				"FROM utente ".
				"WHERE utente.matricola = ".$this->user_id.";";
		$raw_data = $dbc->query($sql);
			
		if ($dbc->rows($raw_data)==1) {
			// chiamata alla funzione per l'estrazione dei dati
			$res = $dbc->extract_object($raw_data);
			
			$this->name = $res->nome;
			$this->surname = $res->cognome;
		}
			
		// disconnessione da MySQL
		$dbc->disconnect();
	}
}