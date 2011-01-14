<?php

// classe wrapper che contiene tutti i parametri necessari per eseguire le chiamate al DB
class DBConfig {
	
	// ip del server su cui gira il database
	// default: localhost 
	const hostname = "localhost";
	
	// nome del database che sarˆ usato da questo software
	// default: demo
	const name = "demo";
	
	// nome dell'utente che vuole accedere al database
	// default: root
	const username = "root";
	
	// password dell'utente per accedere al database
	// default: 
	const password = "";
	
}

?>