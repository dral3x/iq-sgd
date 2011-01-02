<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina,  questo che fa il lavoro sporco
require_once (dirname(__FILE__) . '/classes/compilaModel.php');

// inizializzo il modello per la ricerca
$compila = new Compilatore();

// controllo se l'utente ha giˆ selezionato il modello di documento da compilare
if (isset($_GET['model_id'])) {
	
	// interroga il db per recuperare tutti i campi previsti nel modello $_GET['model_id']
	$model_id = trim(filter_var($_GET['model_id'], FILTER_SANITIZE_STRING));
	$document = $compila->getDocumentModel($model_id);
	
	// carico la vista corrispondente
	require ('view/compilaDocumentoView.php');
} else {
	// nessun documento scelto dall'utente, mostro la vista con l'elenco di modelli disponibili
	$models = $compila->getModelsAvailable();
	
	// carico la vista corrispondente
	require ('view/mostraModelliView.php');
}

?>