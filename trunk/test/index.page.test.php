<?php
require_once('simpletest/autorun.php');
require_once('simpletest/web_tester.php');
SimpleTest::prefer(new TextReporter());

class IndexWebTests extends WebTestCase {

	function testRedirects() {
		// verifico che, bloccando il redirect, il server mi risponda "male"
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
		$this->setMaximumRedirects(0);
		$this->get('http://localhost/iq/index.php');
		$this->assertResponse(array(301, 302, 303, 307));
	}
}