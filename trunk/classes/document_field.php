<?php

class DocumentField {
	const SMALL = "small";
	const MEDIUM = "medium";
	const LONG = "long";
	
	private $id;
	private $name;
	private $type;
	private $optional;
	private $content;

	public function __construct($id, $name, $type, $optional = false, $content = NULL) {
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
		$this->optional = optional;
		if(!is_null($content)) $this->content = $content;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function isOptional() {
		return $this->optional;
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
	
	public function getContent() {
		if (!isset($this->content)) {
			return null;
		} else {
			return $this->content;
		}
	}
}

?>