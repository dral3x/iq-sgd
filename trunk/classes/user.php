<?php

require_once (dirname(__FILE__) . '/security_levels.php');

class User {
	
	public $user_id;
	public $username;
	public $password;
	public $security_level;
	
	// necessari user, pass e user_id, il livello di default  il pi basso... ossia accede ai soli documenti pubblici
	public function __construct($user_id, $user, $pass, $level = SecurityLevel::LPUBLIC) {
		$this->username = $user;
		$this->password = $pass;
		$this->user_id = $user_id;
		$this->security_level = $level;
	}
	
	// serializzazione di uno User
	public function __sleep() {
        // serializzazione di 4 proprietˆ
        return array('user_id', 'username', 'password', 'security_level');
    }
     
    // deserializzazione di uno User
    public function __wakeup() {
        //$this->connect();
    }
    
    public function __toString() {
    	return $this->getDisplayName();
    }
	
	public function getSecurityLevel() {
		return $this->security_level;
	}
	
	public function getDisplayName() {
		return 'Mr ' . $this->username;
	}
}