<?php
require_once(dirname(__FILE__).'/../simpletest/autorun.php');
require_once(dirname(__FILE__).'/../simpletest/unit_tester.php');

require_once(dirname(__FILE__).'/../../classes/model.php');

class TestOfModelClass extends UnitTestCase {
    
	private $chiave;
	private $valid_user_id;
	
	function setUp() {
		$chiave = 't43ghj98jg98cng8ghj8g2gu40fr0gjn245';
		$valid_model_id = 1;
	}
	
	function tearDown() {
		unset($chiave);
		unset($valid_model_id);
	}
	
    function testConstructorWithCustomID() {
		$model = new Model($valid_user_id, $chiave);
		
    	$this->assertEqual($model->getID(), $valid_model_id);
    }

	function testConstructorWithCustomIDNoName() {
		$model = new Model(42);
		
    	$this->assertEqual($model->getID(), 42);
    }
    
	function testConstructorWithCustomName() {
    	$model = new Model(42, 't43ghj98jg98cng8ghj8g2gu40fr0gjn245');
    	
    	$this->assertIdentical($model->getName(), 't43ghj98jg98cng8ghj8g2gu40fr0gjn245');
    }
    
    function testRetrieveModelNameFromDB() {
    	$model = new Model(42);
    	$string = $model->getName();
    	
    	$this->assertIdentical($string, "NomeModello42");
    }
    
}

?>