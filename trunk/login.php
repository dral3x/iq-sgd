<?php

// Login controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina,  questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/loginModel.php');

$login = new LoginSession();

// controllo che non sia una richiesta di logout
if (isset($_GET['action']) && (trim($_GET['action']) == 'logout')) {
	$login->logout();
}

// controllo che non siano stati inviati i dati del form di login
if(isset($_POST['submit']) && (trim($_POST['submit']) == "Login")) {

	// controllo sui parametri di autenticazione inviati
	if( !isset($_POST['username']) || $_POST['username']=="" ) {
		$error_message = "Attenzione, inserire la username.";
	} elseif( !isset($_POST['password']) || $_POST['password'] =="") {
		$error_message = "Attenzione, inserire la password.";
		$username = $_POST['username'];
	} else {
		// salvataggio dei parametri dopo il filtraggio
		$username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
		$password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
		$password = sha1($password);

		if ($login->verifyUsernameAndPassword($username, $password)) {
			// login eseguito con successo!
			// reindirizzamento alla homepage in caso di login mancato
			header("Location: ricerca.php");
		} else {
			// login fallito...
			$error_message = $login->getErrorMessage();
		}
	}
}

// carico la vista da mostrare all'utente
require ('view/loginView.php');

?>
