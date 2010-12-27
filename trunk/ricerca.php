<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, Ë questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/ricercaModel.php');

// inizializzo il modello per la ricerca
$ricerca = new Ricerca();

// controllo che tipo di ricerca vuole fare l'utente
if (isset($_GET['type'])) {
	if (($_GET['type'] == "simple") || ($_GET['type'] == "advanced")) {
		$ricerca->setSearchType($_GET['type']);
	}
} elseif (isset($_POST['search_type'])) {
	if (($_POST['search_type'] == "simple") || ($_POST['search_type'] == "advanced")) {
		$ricerca->setSearchType($_POST['search_type']);
	}
}

/* controlla se l'utente ha inviato qualche parametro di ricerca e la esegue */

//l'utente ha inviato qualcosa?
if (isset($_POST['submit'])) {
	
	//l'utente ha richiesto una ricerca semplice?
	if ($ricerca->typeOfSearch() == "simple") {
		
		//controlla se il parametro di ricerca non Ë stato impostato
		if (trim($_POST['parametroRicerca']) == "") {
			$search_error = "Nessuna chiave di ricerca inserita";
		} else {
			$ricerca->doSimpleSearch($_POST['parametroRicerca']);
		}
	}
	//l'utente ha richiesto una ricerca avanzata?
	elseif ($ricerca->typeOfSearch() == "advanced") {
		
		/* chiama metodo che controlla se nessun parametro Ë stato impostato
		 *  fa il corrispettivo di
		 *  if (trim($_POST['parametriRicerca']) == "") {
		 *  per la ricerca avanzata che ha pi˘ parametri
		 *  TODO: è da fare però!
		 */
		if (noParameterIsSet()) {
			$search_error = "Nessuna chiave di ricerca inserita";
		} else {
			$ricerca->doAdvancedSearch();
		}
	}
	
}

// se c'è un solo risultato, faccio un redirect su visualizza.php?document_id=<id documento>
// se ci sono più risultati, allora mostro l'elenco tramite la view risultatiView.php
// in tutti gli altri casi, mostro la view standard ricercaView.php

// carico la vista da mostrare all'utente
require ('view/ricercaView.php');

?>