<?php

require_once (dirname(__FILE__) . '/security_levels.php');

class User {
	
	public $user_id;
	public $name;
	public $surname;
	public $username;
	public $password;
	private $confidential_level;
	
	// necessari user, pass e user_id, il livello di default  il pi basso... ossia accede ai soli documenti pubblici
	public function __construct($user_id, $name, $surname, $user = NULL, $pass = NULL, $level = SecurityLevel::LPUBLIC) {
		
		$this->user_id = $user_id;
		
		$this->name = $name;
		$this->surname = $surname;

		if (!is_null($user)) $this->username = $user;
		if (!is_null($pass)) $this->password = $pass;
			
		$this->confidential_level = $level;
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
    
    public function __toString() {
    	return $this->getDisplayName();
    }
	
	public function getConfidentialLevel() {
		return $this->confidential_level;
	}
	
	public function getDisplayName() {
		return $this->name . ' ' . $this->surname;
	}
	
	public function equals($another) {
		return $another->user_id == $this->user_id;
	}
}