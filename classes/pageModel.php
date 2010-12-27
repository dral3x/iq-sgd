<?php

require_once (dirname(__FILE__) . '/loginModel.php');

class Page {
	
	private $session;
	 
	public function __construct() {
		// inizializzazione della sessione
		session_start();
		
		// controllo che la sessione sia valida
		$this->session = new LoginSession();
		if (!$this->session->userIsLogged()) { // un utente  arrivato qua senza essere loggato, lo rispedisco a fare il login
			header("Location: login.php");
		}
	}
	
	public function getSessionUser() {
		return $this->session->getUser();
	}

}

?>