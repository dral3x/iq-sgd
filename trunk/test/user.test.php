<?php
require_once('simpletest/autorun.php');
require_once('../classes/user.php');
require_once('../classes/security_levels.php');

class TestOfUser extends UnitTestCase {
    
	private $chiave;
	
	function setUp() {
		$chiave = 't43ghj98jg98cng8ghj8g2gu40fr0gjn245';
	}
	
	function tearDown() {
		unset($chiave);
	}
	
    function testConstructorWithNoSecurityLevel() {
		$user = new User('codice', 'username', 'password');
		
    	$this->assertEqual($user->getSecurityLevel(), SecurityLevel::LPUBLIC);
    }

    function testConstructorWithCustomSecurityLevel() {
    	
		$user = new User('codice', 'username', 'password', $chiave);
		
    	$this->assertEqual($user->getSecurityLevel(), $chiave);
    }
    
    function testConstructorUserID() {
		$user = new User($chiave, 'username', 'password', 'LivelloSicurezzaSpeciale');
		
    	$this->assertEqual($user->user_id, $chiave);
    }
    
    function testConstructorUsername() {
		$user = new User('codice', $chiave, 'password', 'LivelloSicurezzaSpeciale');
		
    	$this->assertEqual($user->username, $chiave);
    }
    
    function testConstructorPassword() {
		$user = new User('codice', 'username', $chiave, 'LivelloSicurezzaSpeciale');
		
    	$this->assertEqual($user->password, $chiave);
    }
    
}

?>