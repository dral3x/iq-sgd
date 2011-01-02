<?php

require_once (dirname(__FILE__) . '/db_connector.php');
require_once (dirname(__FILE__) . '/user.php');
require_once (dirname(__FILE__) . '/security_levels.php');

class LoginSession {

	protected $user;
	protected $error_message;
	
	// costruttore, invocato con la new LoginSession()
	public function __construct() {
		// inizializzazione della sessione
		session_start();
		
		// controllo se l'utente  giˆ stato loggato in precedenza
		if (isset($_SESSION[user_logged])) {
			$this->user = unserialize($_SESSION[user_logged]);
		}
	}
	
	// ritorna true se l'utente  loggato, false altrimenti
	public function userIsLogged() {
		return isset($this->user);
	}

	// esegue la verifica dei valori username e password tramite query nel db
	// restituisce l'esito della verifica: true ok, false login fallito
	public function verifyUsernameAndPassword($username, $password) {
			
			// istanza della classe
			$dbc = new DBConnector();
			// chiamata alla funzione di connessione
			$dbc->connect();
			// interrogazione della tabella
			$raw_data = $dbc->query('SELECT matricola, ruolo FROM utente WHERE username = "' . $username . '" AND passwd = "' . $password . '";');
			// FIXME: la query  sbagliata...
			
			/* hack momentaneo finch il db non  pronto */
			//$this->user = new User('1', $username, $password, SecurityLevel::L0);
			//$_SESSION[user_logged] = serialize($this->user);
			/* hack momentaneo finch il db non  pronto */
			
			if ($dbc->rows($raw_data)==1) {
				// chiamata alla funzione per l'estrazione dei dati
				$res =  $dbc->extract_object($raw_data);
				// 	creazione del valore di sessione
				$this->user = new User($res->matricola, $username, $password, $res->ruolo);
				$_SESSION[user_logged] = serialize($this->user);
				
			} else {
				$this->error_message = "Login fallito!";
			}
			
			// disconnessione da MySQL
			$dbc->disconnect();
			
			return $this->userIsLogged();
	}
	
	
	public function logout() {
		unset($this->user);
		unset($_SESSION[user_logged]);
	}
	
	public function getErrorMessage() {
		if (isset($this->error_message)) {
			return $this->error_message;
		} else {
			return false;
		}
	}

	
	public function getUser() {
		if (isset($this->user)) {
			return $this->user;
		} else {
			return false;
		}
	}
}