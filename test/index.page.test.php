<?php
require_once('simpletest/autorun.php');
require_once('simpletest/web_tester.php');
SimpleTest::prefer(new TextReporter());

class IndexWebTests extends WebTestCase {

	function testRedirects() {
		$this->get('http://localhost/iq/index.php');
		$this->assertResponse(200);
	}
}