<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina,  questo che fa il lavoro sporco
require_once (dirname(__FILE__) . '/classes/compilaModel.php');

// inizializzo il modello per la ricerca
$compila = new Compilatore();

// controllo se l'utente ha giˆ selezionato il modello di documento da compilare
if (isset($_GET['model_id'])) {
	// visualizza un documento partendo dal modello specificato con tutti i campi vuoti cos“ che
	// l'utente possa compilarlo
	
	// interroga il db per recuperare tutti i campi previsti nel modello $_GET['model_id']
	$model_id = trim(filter_var($_GET['model_id'], FILTER_SANITIZE_STRING));
	$model = new Model($model_id);
	
	// carico la vista corrispondente
	require ('view/compilaDocumentoView.php');

} else if (isset($_POST['model_id'])) { 
	
	// salvo il documento
	$model = new Model($_POST['model_id']);
	$document = $compila->generateDocumentFromModelWithData($model, $_POST);
	unset($model);
	
	// resta una bozza o lo metto come "da approvare" ?
	if ($_POST['submit'] == "Invia all'approvatore") {
		// cerco di far approvare il documento
		$document->setState(DocumentState::DA_APPROVARE);
	} else {
		// salvo come bozza
		$document->setState(DocumentState::BOZZA);
	}
	
	$saved = $document->saveDocumentIntoDB();
	
	if (!$saved) {
		$error_message = "Errore durante il salvataggio del documento!";
		require ('view/compilaDocumentoView.php');
	} else if ($document->getState() == DocumentState::BOZZA) {
		$highlight_message = "Documento salvato con successo.";
		$document_id = $document->getID();
		require ('view/compilaDocumentoView.php');
	} else {
		$highlight_message = "Salvataggio completato con successo. Il documento ora &egrave in attesa di approvazione da parte dell'approvatore.";
		require ('view/visualizzaView.php');
	}
	
} else if (isset($_GET['new_revision_for_document'])) {
	
	$document_id = trim(filter_var($_GET['new_revision_for_document'], FILTER_SANITIZE_NUMBER_INT));
	$document = new Document($document_id);
	//  imposto la nuova data
	$document->setCreationDate(date("j"), date("n"), date("Y"));
	// imposto il nuovo numero di revisione (modificabile comunque)
	$rev_num = filter_var($document->getRevision(), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$rev_num += 1.0;
	$document->setRevision("$rev_num.0");
	// aggiungo agli autori, me stesso
	$auts = $document->getAuthors();
	if (!in_array($compila->getSessionUser(), $auts)) {
		$document->addAuthor($compila->getSessionUser());
	}
	
	// estraggo il numero di progressione per la view
	$old_progressive = $document->getProgressive();
	
	require ('view/compilaDocumentoView.php');
	
} else {
	// nessun documento scelto dall'utente, mostro la vista con l'elenco di modelli disponibili
	$models = $compila->getModelsAvailable();
	
	// carico la vista corrispondente
	require ('view/mostraModelliView.php');
}

?>