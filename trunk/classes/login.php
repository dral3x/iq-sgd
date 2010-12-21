<?php

require_once (dirname(__FILE__) . '/page.php');
require_once (dirname(__FILE__) . '/db.php');

class Login extends Page {

	protected $username;
	protected $password;
	protected $error_message;
	
	public function __construct() {
		parent::__construct();
		
		// controllo sul parametro d'invio
		if(isset($_POST['submit']) && (trim($_POST['submit']) == "Login")) {

			// controllo sui parametri di autenticazione inviati
			if( !isset($_POST['username']) || $_POST['username']=="" ) {
				$this->error_message = "Attenzione, inserire la username.";
			} elseif( !isset($_POST['password']) || $_POST['password'] =="") {
				$this->error_message = "Attenzione, inserire la password.";
			} else {
				// validazione dei parametri tramite filtro per le stringhe
				$this->username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
				$this->password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
				$this->password = sha1($this->password);
				unset($this->error_message);
			}
		}
	}
	
	public function userIsLogged() {
		return isset($_SESSION[login_id]);
	}

	
	public function verifyUserLogin() {

		if (isset($this->username) && isset($this->password)) {
			
			// istanza della classe
			$dbc = new DBConnector();
			// chiamata alla funzione di connessione
			$dbc->connect();
			// interrogazione della tabella
			$raw_data = $dbc->query('SELECT user_id FROM users WHERE username = "' . $this->username . '" AND password = "' . $this->password . '";');
			
			if ($dbc->rows($raw_data)==1) {
				// chiamata alla funzione per l'estrazione dei dati
				$res =  $dbc->extract_object($raw_data);
				// 	creazione del valore di sessione
				$_SESSION[login_id] = $res->id_login;
				unset($error_message);
			} else {
				$this->error_message = "Login fallito!";
			}
			// disconnessione da MySQL
			$dbc->disconnect();
			 
		}
	}
	
	public function getErrorMessage() {
		if (isset($this->error_message)) {
			return $this->error_message;
		} else {
			return false;
		}
	}
	
	public function getUsername() {
		if (isset($this->username))
			return $this->username;
		else
			return false;
	}
}