<?php
require_once('simpletest/autorun.php');
require_once('simpletest/web_tester.php');

class RicercaWebTests extends WebTestCase {

	function testRedirectSuUtenteNonLoggato() {
		$this->setMaximumRedirects(0);
		$this->get('http://localhost/iq/ricerca.php');
		$this->assertResponse(array(301, 302, 303, 307));
	}

}

?>