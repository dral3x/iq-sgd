<?php
// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, è questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/pageModel.php');
require_once (dirname(__FILE__) . '/classes/document.php');


$modifica = new Page();

if (isset($_REQUEST['document_id'])) {
	
	$document = new Document($_GET['document_id']);
	if ($document->isValidID()) {
		// il documento esiste sul serio, continuo i controlli
		if ($document->canBeEditedBy($modifica->getSessionUser())) {
			// l'utente pu˜ modificarlo, allora glielo mostro
		} else {
			// l'utente non ha un livello di riservatezza abbastanza elevato per vedere il documento
			unset($document);
			$error_message = "Non sei autorizzato a modificare questo documento.";
		}
	} else {
		unset($document);
		$error_message = "il documento richiesto non esiste";
	}
	
	// carico la vista da mostrare all'utente
	require ('view/modificaView.php');

} else {
	$error_message = "Nessun documento selezionato.<br>Esegui una ricerca per modificare un documento.";
	
	// carico la vista da mostrare all'utente
	require ('view/modificaView.php');
}

?>