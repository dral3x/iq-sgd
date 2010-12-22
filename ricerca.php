<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina,  questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/ricercaModel.php');

// inizializzo il modello per la ricerca
$ricerca = new Ricerca();

// controllo se l'utente mi sta inviando chiavi su cui eseguire la ricerca
if (isset($_POST['submit']) && (trim($_POST['submit']) == "Cerca")) {

	if (trim($_POST['parametriRicerca']) == "") {
		$search_error = "Nessuna chiave di ricerca inserita";
	} else {
		$search_result = $ricerca->doBasicSearch($_POST['parametriRicerca']);
	}
}

// carico la vista da mostrare all'utente
require ('view/ricercaView.php');

?>
