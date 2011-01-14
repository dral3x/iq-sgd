<?php
require_once(dirname(__FILE__).'/../simpletest/autorun.php');
require_once(dirname(__FILE__).'/../simpletest/unit_tester.php');

require_once(dirname(__FILE__).'/../../classes/user.php');
require_once(dirname(__FILE__).'/../../classes/security_levels.php');

class TestOfUser extends UnitTestCase {
    
	private $chiave;
	private $valid_user_id;
	
	function setUp() {
		$chiave = 't43ghj98jg98cng8ghj8g2gu40fr0gjn245';
		$valid_user_id = 1;
	}
	
	function tearDown() {
		unset($chiave);
		unset($valid_user_id);
	}
	
    function testConstructorWithNoConfidentialLevel() {
		$user = new User($valid_user_id, 'nome', 'cognome', 'username', 'password');
		
    	$this->assertEqual($user->getConfidentialLevel(), SecurityLevel::LPUBLIC);
    }

    function testConstructorWithCustomConfidentialLevel() {
    	
		$user = new User($valid_user_id, 'nome', 'cognome', 'username', 'password', $chiave);
		
    	$this->assertEqual($user->getConfidentialLevel(), $chiave);
    }
    
    function testConstructorUserID() {
		$user = new User(52, 'nome', 'cognome', 'username', 'password', 'LivelloSicurezzaSpeciale');
		
    	$this->assertEqual($user->user_id, 52);
    }
    
    function testConstructorUsername() {
		$user = new User('codice', 'nome', 'cognome', $chiave, 'password', 'LivelloSicurezzaSpeciale');
		
    	$this->assertEqual($user->username, $chiave);
    }
    
    function testConstructorPassword() {
		$user = new User('codice', 'nome', 'cognome', 'username', $chiave, 'LivelloSicurezzaSpeciale');
		
    	$this->assertEqual($user->password, $chiave);
    }
    
    function testSerializeAndDeserialized() {
    	$user = new User('dfhs463a4aa', 'fdg5whhfsg554', 'dfgsy5ghgfhj76uj', 'lkuo78otk', 'asfdt345ty45ysfd5y564syh', 'dfgety45yhsthtrs5');
    	$user_serialized = serialize($user);
    	
    	$user_deserialized = unserialize($user_serialized);
    	$this->assertEqual($user, $user_deserialized);
    }
    
    function testRetrieveNameAndSurnameFromDB() {
    	$user = new User(42);
    	$string = $user->getDisplayName();
    	
    	$this->assertIdentical($string, "Nome Cognome");
    	
    }
    
}

?>