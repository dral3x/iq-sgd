<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, Ë questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/visualizzaModel.php');
require_once (dirname(__FILE__) . '/classes/document.php');
// inizializzo il modello per la ricerca
$visualizza = new VisualizzaDocumento();


/*controlla se l'utente ha inviato qualche parametro di ricerca e la esegue*/

//l'utente ha inviato qualcosa?
if (isset($_GET['document_id'])) {
	
	$document = new Document($_GET['document_id']);
	if ($document->isValid()) {
		// il documento esiste sul serio, continuo i controlli
		if ($visualizza->getSessionUser()->getSecurityLevel() > $document->getSecurityLevel()) {
			// l'utente può vederlo, allora glielo mostro
		} else {
			// l'utente non ha un livello di riservatezza abbastanza elevato per vedere il documento
			unset($document);
			$error_message = "Non sei autorizzato a vedere questo documento.";
		}
	} else {
		unset($document);
		$error_message = "il documento richiesto non esiste";
	}
	
	// carico la vista da mostrare all'utente
	require ('view/visualizzaView.php');
	
} else if (isset($_GET['type'])) {
	// l'utente vuole visualizzare qualche elenco di documenti
	// specifico cosa vuole vedere
	if ($_GET['type'] == "draft_documents") {
		// recupero i documenti di tipo draft da modello
		$documents = $visualizza->retrieveDraftDocuments();
	} else if ($_GET['type'] == "waiting_approval_documents") {
		// recupero i documenti che aspettano la mia approvazione
		$documents = $visualizza->retrieveWaitingApprovalDocuments();
	} else {
		$error_message = "Nessun tipo di documento valido è stato selezionato.";
	}
	
	// carico la vista da mostrare all'utente
	require ('view/visualizzaElencoView.php');
} else {
	$error_message = "Nessun documento selezionato.<br>Esegui una ricerca per vedere un documento.";
	
	// carico la vista da mostrare all'utente
	require ('view/visualizzaView.php');
}

?>