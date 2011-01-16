<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Installazione SGD</title>
</head>
<body>
<h1>Installazione SGD</h1>
<?php

require_once (dirname(__FILE__) . '/classes/db_connector.php');
require_once (dirname(__FILE__) . '/config/db_config.php');

function parseSQLFile($sql_file) {
	$querys = array();
	
	// creo il db nel caso non ci sia...
	array_push($querys, "CREATE DATABASE IF NOT EXISTS ".DBConfig::name.";");
	array_push($querys, "USE ".DBConfig::name.";");

	$contents = file_get_contents($sql_file);
	
	// Remove C style and inline comments
	$comment_patterns = array(	'/\/\*.*(\n)*.*(\*\/)?/', //C comments
								'/\s*--.*\n/', //inline comments start with --
								'/\s*#.*\n/', //inline comments start with #
							);

	$contents = preg_replace($comment_patterns, "\n", $contents);
	
	//Retrieve sql statements
	$statements = explode(";\n", $contents);
	$statements = preg_replace("/\s/", ' ', $statements);

	foreach ($statements as $query) {
		if (trim($query) != '') {
			//echo '<p>' . $query . "</p>\n";
			array_push($querys, $query);
		}
	}

	return $querys;
}



$absolute_path = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], "/")+1);

if (isset($_GET['action']) && $_GET['action'] == "install") {

		// recupero le query dal file demo.sql
		$absolute_path .= "config/demo.sql";
		$querys = parseSQLFile($absolute_path);
	
		// eseguo le query di installazione
		$dbc = new DBConnector();
		$dbc->connect(false);
		
		// inizio la transazione
		$dbc->begin_transaction();
		
		$success = true;
		// eseguo la singola query e verifico vada in porto
		foreach ($querys as $query) {
			 //echo "<p>$query";
			// eseguo la singola query e verifico vada in porto
			$success = $dbc->query($query, true);
			if (!$success) {
				echo "<p>ERRORE: ".$dbc->getErrorMessage()."</p>\n";
				break;
			}
		}

		// termino la transazione, bene o mane a seconda del successo delle query
		if (!$success) {
			$dbc->rollback_transaction();
		} else {
			$dbc->commit_transaction();
		}
			
		// disconnessione dal db
		$dbc->disconnect();
		
		if ($success) {
		?>
<h2>Installazione completata con successo!</h2>
<p>Attenzione: per motivi di sicurezza, rimuovi dal server i file seguenti:</p>
<ul>
<li><pre><?php echo $_SERVER['SCRIPT_FILENAME']; ?></pre></li>
<li><pre><?php echo $absolute_path; ?></pre></li>
</ul>
<h2><a href="index.php">Accedi a SGD &gt;</a></h2>
		<?php
 		} else {
 			// installazione non riuscita
 			$absolute_path .= "config/db_config.php"; 			
		?>
<p>Assicurati che i parametri nel file &quot;<?php echo $absolute_path; ?>&quot; siano corretti e che il DBMS sia attivo.</p>
<h2><a href="installa.php?action=install">Riprova &gt;</a></h2>
		<?php
		}

} else {
	$absolute_path .= "config/db_config.php";
?>
<p>Questa pagina eseguir&agrave; l'installazione dei dati di default del software SGD.</p>
<p>Prima di procedere assicurati che i parametri nel file &quot;<?php echo $absolute_path; ?>&quot; siano corretti e che il DBMS sia attivo.</p>
<h2><a href="installa.php?action=install">Avanti &gt;</a></h2>
<?php	   
}
?>
</body>
</html>