<?php

require_once (dirname(__FILE__) . '/pageModel.php');
require_once (dirname(__FILE__) . '/loginModel.php');

class Ricerca extends Page {
	
	private $session;
	
	public function __construct() {
		parent::__construct();

		// controllo che la sessione sia valida
		$this->session = new LoginSession();
		if (!$this->session->userIsLogged()) { // un utente  arrivato qua senza essere loggato, lo rispedisco a casa
			header("Location: login.php");
		}

	}
	
	public function getSessionUser() {
		return $this->session->getUser();
	}
	
	public function doBasicSearch($keys) {
		return false;
	}
	
	public function typeOfSearch() {
		return "simple";
		// bisogna gestire il caso in cui si voglia una ricerca acanzata
		// return "advanced";
	}
	
	
}

?>