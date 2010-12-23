<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, è questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/ricercaModel.php');

// inizializzo il modello per la ricerca
$ricerca = new Ricerca();


/*controlla se l'utente ha inviato qualche parametro di ricerca e la esegue*/

//l'utente ha inviato qualcosa?
if (isset($_POST['submit']) {
	
	//l'utente ha richiesto una ricerca semplice?
	if (trim($_POST['submit']) == "Ricerca Semplice") {
		
		//controlla se il parametro di ricerca non è stato impostato
		if (trim($_POST['parametroRicerca']) == "") {
			$search_error = "Nessuna chiave di ricerca inserita";
		} else {
			$ricerca->doSimpleSearch($_POST['parametroRicerca']);
		}
	}
	//l'utente ha richiesto una ricerca avanzata?
	elseif (trim($_POST['submit']) == "Ricerca Avanzata")) {
		
		/* chiama metodo che controlla se nessun parametro è stato impostato
		 *  fa il corrispettivo di
		 *  if (trim($_POST['parametriRicerca']) == "") {
		 *  per la ricerca avanzata che ha più parametri
		 */
		if (noParameterIsSet()) {
			$search_error = "Nessuna chiave di ricerca inserita";
		} else {
			$ricerca->doAdvancedSearch();
		}
	}
	
}

//	se ci sono risultati in $search_result visualizza i risultati magari utilizzando
//		###> header("Location: risultati.php"); <###
//	dove il controller risultati.php si appoggia a view/risultatiView.php
// altrimenti si può usare un metodo di ricercaModel


// carico la vista da mostrare all'utente
require ('view/ricercaView.php');

?>
