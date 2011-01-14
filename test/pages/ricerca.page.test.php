<?php
require_once(dirname(__FILE__).'/../simpletest/autorun.php');
require_once(dirname(__FILE__).'/../simpletest/web_tester.php');

class RicercaWebTests extends WebTestCase {

	function testRedirectSuUtenteNonLoggato() {
		$this->setMaximumRedirects(0);
		$this->get('http://localhost/iq/ricerca.php');
		$this->assertResponse(array(301, 302, 303, 307));
	}

}

?>