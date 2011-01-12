<?php
// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, è questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/modificaModel.php');
require_once (dirname(__FILE__) . '/classes/document.php');


$modifica = new Edit();

if (isset($_GET['document_id'])) { // prima modifica
	
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
	
} else if (isset($_POST['document_id'])) { // salvataggio
	
	$document = $modifica->generateUpdatedDocument($_POST['document_id'], $_POST);
	//$document = new Document($_POST['document_id']);
	if ($document->isValidID()) {
		// il documento esiste sul serio, continuo i controlli
		if ($document->canBeEditedBy($modifica->getSessionUser())) {
			// l'utente pu˜ modificarlo, allora lo salvo ... ma prima
			
			// resta una bozza o lo metto come "da approvare" ?
			if ($_POST['submit'] == "Invia all'approvatore") {
				// cerco di far approvare il documento
				$document->setState(DocumentState::DA_APPROVARE);
			} else {
				// salvo come bozza
				$document->setState(DocumentState::BOZZA);
			}
	
			$document->saveDocumentIntoDB();
			
			if ($document->getState() == DocumentState::BOZZA) {
				require ('view/modificaView.php');
			} else {
				require ('view/visualizzaView.php');
			}
			
		} else {
			// l'utente non ha un livello di riservatezza abbastanza elevato per vedere il documento
			unset($document);
			$error_message = "Non sei autorizzato a modificare questo documento.";
			
			// carico la vista da mostrare all'utente
			require ('view/modificaView.php');
	
		}
	} else {
		unset($document);
		$error_message = "il documento richiesto non esiste";
		
		// carico la vista da mostrare all'utente
		require ('view/modificaView.php');
	}


} else {
	$error_message = "Nessun documento selezionato.<br>Esegui una ricerca per modificare un documento.";
	
	// carico la vista da mostrare all'utente
	require ('view/modificaView.php');
}

?>