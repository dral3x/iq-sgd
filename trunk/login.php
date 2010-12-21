<?php

require_once (dirname(__FILE__) . '/classes/login.php');

$login = new Login();

$login->verifyUserLogin();

if ($login->userIsLogged()) {

	// reindirizzamento alla homepage in caso di login mancato
	header("Location: ricerca.php");
}

// form per l'autenticazione
require ('view/login.php');

?>
