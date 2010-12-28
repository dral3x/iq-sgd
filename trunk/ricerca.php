<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, è questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/ricercaModel.php');

// inizializzo il modello per la ricerca
$ricerca = new Ricerca();

// controllo che tipo di ricerca vuole fare l'utente
if (isset($_GET['type'])) {
	if (($_GET['type'] == "simple")	{
		$ricerca->setSimpleSearch();
	} elseif ($_GET['type'] == "advanced")) {
		$ricerca->setAdvancedSearch();
	}
} elseif (isset($_POST['search_type'])) {
	if (($_POST['search_type'] == "simple")	{
		$ricerca->setSimpleSearch();
	} elseif ($_POST['search_type'] == "advanced")) {
		$ricerca->setAdvancedSearch();
	}
}

//RICORDA: per impostazione default il tipo di ricerca è "simple" (vedi ricercaModel)

/*controlla se l'utente ha inviato qualche parametro di ricerca e la esegue*/

//l'utente ha inviato qualcosa?
if (isset($_POST['submit'])) {
	
	//l'utente ha richiesto una ricerca semplice?
	if ($ricerca->getTypeOfSearch() == "simple") {
		
		//controlla se il parametro di ricerca non è stato impostato
		if (trim($_POST['parametroRicerca']) == "") {
			$search_error = "Nessuna chiave di ricerca inserita";
		} else {
			$ricerca->doSimpleSearch($_POST['parametroRicerca']);
		}
	}
	//l'utente ha richiesto una ricerca avanzata?
	elseif ($ricerca->getTypeOfSearch() == "advanced") {
		
		/* chiama la funzione che controlla se nessun parametro è stato impostato
		 *  fa il corrispettivo di
		 *  if (trim($_POST['parametriRicerca']) == "") {
		 *  per la ricerca avanzata che ha più parametri
		 *  TODO: ? da fare per˜!
		 */
		if (noParameterIsSet()) {
			$search_error = "Nessuna chiave di ricerca inserita";
		} else {
			$ricerca->doAdvancedSearch( getAdvancedKeys() );
		}
	}
	
}

// se c'è un solo risultato, faccio un redirect su visualizza.php?document_id=<id documento>
// se ci sono più risultati, allora mostro l'elenco tramite la view risultatiView.php
// in tutti gli altri casi, mostro la view standard ricercaView.php

// carico la vista da mostrare all'utente
require ('view/ricercaView.php');


//FUNZIONI

// true: se nessun parametro della ricerca avanzata è stato impostato
// false: se almeno un parametro della ricerca avanzato è stato impostato
function noParameterIsSet() {
		
	//nessun campo dell'intestazione è stato impostato?
	
		//nessun campo dell'identificatore è stato impostato?
		if (trim($_POST['identificatore']) == "") return false;
		
		foreach($_POST['classe'] as $value) {
			if isset($value) return false;
		}
		
		if (trim($_POST['versione']) == "") return false;
		if (trim($_POST['anno']) == "") return false;
		if (trim($_POST['numero']) == "") return false;
		//a questo punto nessun campo dell'identificatore è stato impostato
		
	if (trim($_POST['data']) == "") return false;
	if (trim($_POST['revisione']) == "") return false;
	
	foreach($_POST['stato'] as $value) {
		if isset($value) return false;
	}
	
	if (trim($_POST['lingua']) == "") return false;


	//nessun campo del pié di pagina è stato impostato?
	
	if (trim($_POST['sede']) == "") return false;
	
	foreach($_POST['livello'] as $value) {
		if isset($value) return false;
	}
	
	if (trim($_POST['allegati']) == "") return false;
	if (trim($_POST['pagine']) == "") return false;
	if (trim($_POST['approvato']) == "") return false;
	if (trim($_POST['autore']) == "") return false;
	
	
	if (trim($_POST['abstract']) == "") return false;
	
	if (trim($_POST['doc']) == "") return false;
	
	
	
	/* CODICE ALTERNATIVO a tutti gli if
	 * NB: i tre cicli foreach precedenti sono riportati come sopra!
	 * TODO: verificare se potrebbe funzionare
	
	$fields = array('identificatore','versione','anno','numero','data','revisione','lingua','sede','allegati','pagine','approvato','autore','abstract','doc');
	
	foreach($fields as $field) {
		if ( trim($_POST[$field]) == "" ) return false;
	}
	
	// cicli foreach corrispondenti ai tre gruppi di checkbox
	foreach($_POST['classe'] as $value) {
			if isset($value) return false;
	}
	
	foreach($_POST['livello'] as $value) {
		if isset($value) return false;
	}
	
	foreach($_POST['livello'] as $value) {
		if isset($value) return false;
	}
	
	
	// FINE CODICE ALTERNATIVO
	 */
	
	
	//a questo punto nessuno dei precedenti parametri è stato impostato
	return true;
}
	
	
	
//costruisce la parte della query contenente le chiavi di una ricerca avanzata
function getAdvancedKeys() {
	
	$partialQuery = "";
	
	/* TODO: estrae chiavi di ricerca dai vari $_POST[''] e costruisce $partialQuery
	 */
	
	return $partialQuery;
}

?>