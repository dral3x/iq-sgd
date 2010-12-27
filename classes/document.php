<?php

class Document {
	
	private $id;
	private $title;
	private $security_level;
	
	private $content; // array associativo ... array("campo" => "contenuto");
	
	public function __construct($id, $title = NULL, $security_level = NULL, $content = NULL) {
		$this->id = $id;
		if (!is_null($title)) $this->title = $title;
		if (!is_null($security_level)) $this->security_level = $security_level;
		if (!is_null($content)) $this->content = $content;
	}
	
	public function isValid() {
		// controlla che il documento esista nel db
		$exist = false;
		return $exist;
	}
	
	private function retrieveGeneralInformation() {
		// query sul db per estrarre le seguenti informazioni sul documento
		// titolo del documento
		// data di approvazione
		// stato
		// livello di riservatezza
	}
	
	private function retrieveCompleteDocument() {
		// query sul db per estrarre tutte le informazioni mancanti sul documento
		// tutti i contenuti inseriti nel documento, organizzati per chiavi (le chiavi saranno i nomi dei campi)
	}
	
	public function getContent() {
		if (!isset($this->$content)) {
			$this->retrieveCompleteDocument();
		}
		
		return $this->$content;
	}
	
	public function getSecurityLevel() {
		if (!isset($this->$security_level)) {
			$this->retrieveGeneralInformation();
		}
		
		return $this->$security_level;
	}
	
	public function getTitle() {
		if (!isset($this->title)) {
			$this->retrieveGeneralInformation();
		}
		
		return $this->title;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getAuthor() {
		if (!isset($this->$content)) {
			$this->retrieveCompleteDocument();
		}
	
		return "Autore";
	}
}