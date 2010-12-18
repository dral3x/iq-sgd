<?php
// inizializzazione della sessione
session_start();

// se la sessione di autenticazione 
// è già impostata non sarà necessario effettuare il login
// e il browser verrà reindirizzato alla pagina di scrittura dei post
if (isset($_SESSION['login'])) {

 // reindirizzamento alla homepage in caso di login mancato
 header("Location: ricerca.php");
}

// controllo sul parametro d'invio
if(isset($_POST['submit']) && (trim($_POST['submit']) == "Login")) { 

	// controllo sui parametri di autenticazione inviati
  if( !isset($_POST['username']) || $_POST['username']=="" ) {
    $error_message = "Attenzione, inserire la username.";
  } elseif( !isset($_POST['password']) || $_POST['password'] =="") {
    $error_message = "Attenzione, inserire la password.";
  } else {
    // validazione dei parametri tramite filtro per le stringhe
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $password = sha1($password);
    
    // inclusione del file della classe
    include "classes/db.php";
    // istanza della classe
    $data = new DBConnector();
    // chiamata alla funzione di connessione
    $data->connect();
    // interrogazione della tabella
    $auth = $data->query("SELECT id_login FROM login WHERE username_login = '$username' AND password_login = '$password'");
    // controllo sul risultato dell'interrogazione
    if($data->rows($auth)==0) {
    	$error_message = "Login fallito!";
    } else {
      // chiamata alla funzione per l'estrazione dei dati
      $res =  $data->extract_object($auth);
      // creazione del valore di sessione
      $_SESSION['login'] = $res-> id_login;
      // disconnessione da MySQL
      $data->disconnect();
      // reindirizzamento alla pagina di amministrazione in caso di successo
      header("Location: ricerca.php");     
    }
  } 
}

// form per l'autenticazione
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login</title>
</head>
<body>
<?php
if (isset($error_message)) {
	echo '<p>'.$error_message.'</p>';
}
?>
<h1>Accedi</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
Nome Utente: <input type="text" name="username" size="15" /><br />
Password: <input type="password" name="password" size="15" /><br />
<div align="left">
<p><input type="submit" name="submit" value="Login" /></p>
</div>
</form>
</body>
</html>
