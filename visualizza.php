<?php

// Visualizza controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, è questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/visualizzaModel.php');
require_once (dirname(__FILE__) . '/classes/document.php');
// inizializzo il modello per la ricerca
$visualizza = new VisualizzaDocumento();

if (isset($_GET['type'])) {
	// l'utente vuole visualizzare qualche elenco di documenti
	// specifico cosa vuole vedere
	if ($_GET['type'] == "draft_documents") {
		// recupero i documenti di tipo draft da modello
		$documents = $visualizza->retrieveDraftDocuments();
		$page_title = "Bozze di documento";
	} else if ($_GET['type'] == "waiting_approval_documents") {
		// recupero i documenti che aspettano la mia approvazione
		$documents = $visualizza->retrieveWaitingApprovalDocuments();
		$page_title = "Documenti in attesa di approvazione";
	} else if ($_GET['type'] == "revisions" && isset($_GET['document_id'])) {
		// recupero tutte le revisioni dello stesso documento
		$documents = $visualizza->retrieveAllRevisionsOfDocuments($_GET['document_id']);
		$page_title = "Tutte le revisioni del documento precedente";
	} else {
		$error_message = "Nessun tipo di documento valido &eacute; stato selezionato.";
	}
	
	// carico la vista elenco da mostrare all'utente
	require ('view/visualizzaElencoView.php');
	
} else if (isset($_GET['document_id'])) {
	
	$document = new Document($_GET['document_id']);
	if ($document->isValidID()) {
		// il documento esiste sul serio, continuo i controlli
		if ($visualizza->getSessionUser()->getConfidentialLevel() <= $document->getConfidentialLevel()) {
			// l'utente pu˜ vederlo, allora glielo mostro
		} else {
			// l'utente non ha un livello di riservatezza abbastanza elevato per vedere il documento
			unset($document);
			$error_message = "Non sei autorizzato a vedere questo documento.";
		}
	} else {
		unset($document);
		$error_message = "il documento richiesto non esiste";
	}
	
	// carico la vista documento da mostrare all'utente
	require ('view/visualizzaView.php');

} else {
	$error_message = "Nessun documento o elenco selezionato.<br>Esegui una ricerca per vedere un documento.";
	
	// carico la vista da mostrare all'utente
	require ('view/visualizzaView.php');
}

?>