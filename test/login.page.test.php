<?php
require_once('simpletest/autorun.php');
require_once('simpletest/web_tester.php');
SimpleTest::prefer(new TextReporter());

class LoginWebTests extends WebTestCase {

	function testElementiStandard() {
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
		$this->get('http://localhost/iq/login.php');
		$this->setField('username', 'UtenteDiProva');
		$this->setField('password', 'PasswordDiProva');
		$this->clickSubmit('Login');
		$this->assertText('Ricerca');
	}
}

?>