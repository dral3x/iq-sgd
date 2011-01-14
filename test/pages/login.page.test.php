<?php
require_once(dirname(__FILE__).'/../simpletest/autorun.php');
require_once(dirname(__FILE__).'/../simpletest/web_tester.php');

class LoginWebTests extends WebTestCase {

	function testElementiStandard() {
		// la pagina deve contenere i seguenti elementi (testi e campi)
		$this->get('http://localhost/iq/login.php');
		// parole
		$this->assertText('Accedi');
		$this->assertText('Nome Utente');
		$this->assertText('Password');

		// campi
		$this->assertField('username', '');
		$this->assertField('password', '');
	}

	function testNotificaErroreConFormVuoto() {
		$this->get('http://localhost/iq/login.php');
		$this->clickSubmit('Login');
		$this->assertText('Attenzione');
	}

	function testNotificaErroreConSoloUsernameInserito() {
		$this->get('http://localhost/iq/login.php');
		$this->setField('username', 'UtenteDiProva');
		$this->clickSubmit('Login');
		$this->assertText('Attenzione');
	}

	function testNotificaErroreConUsernameEPasswordInserite() {
		//$this->setMaximumRedirects(0);
		$this->get('http://localhost/iq/login.php');
		$this->setField('username', 'abcdefghi');
		$this->setField('password', 'abcdefghi');
		$this->clickSubmit('Login');
		
		$this->assertText('fallito');
		//$this->assertResponse(array(301, 302, 303, 307));
	}
	
	function testLoginValido() {
		$this->setMaximumRedirects(0);
		$this->get('http://localhost/iq/login.php');
		$this->setField('username', 'UtenteDiProva');
		$this->setField('password', 'PasswordDiProva');
		$this->clickSubmit('Login');
		
		$this->assertResponse(array(301, 302, 303, 307));
	}
}

?>