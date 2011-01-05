<?php

// Ricerca controller ...  l'utente chiede questa pagina

// scarico il modello per questa pagina, è questo che il lavoro sporco
require_once (dirname(__FILE__) . '/classes/ricercaModel.php');

// inizializzo il modello per la ricerca
$ricerca = new Ricerca();

// controllo che tipo di ricerca vuole fare l'utente
if (isset($_GET['type'])) {
	if ($_GET['type'] == "simple")	{
		$ricerca->setSimpleSearch();
	} elseif ($_GET['type'] == "advanced") {
		$ricerca->setAdvancedSearch();
	}
} elseif (isset($_POST['search_type'])) {
	if ($_POST['search_type'] == "simple")	{
		$ricerca->setSimpleSearch();
	} elseif ($_POST['search_type'] == "advanced") {
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
			if (isset($value)) return false;
		}
		
		if (trim($_POST['versione']) == "") return false;
		if (trim($_POST['anno']) == "") return false;
		if (trim($_POST['numero']) == "") return false;
		//a questo punto nessun campo dell'identificatore è stato impostato
		
	if (trim($_POST['data']) == "") return false;
	if (trim($_POST['revisione']) == "") return false;
	
	foreach($_POST['stato'] as $value) {
		if (isset($value)) return false;
	}
	
	if (trim($_POST['lingua']) == "") return false;


	//nessun campo del pié di pagina è stato impostato?
	
	if (trim($_POST['sede']) == "") return false;
	
	foreach($_POST['livello'] as $value) {
		if (isset($value)) return false;
	}
	
	if (trim($_POST['allegati']) == "") return false;
	if (trim($_POST['pagine']) == "") return false;
	if (trim($_POST['approvato']) == "") return false;
	if (trim($_POST['autore']) == "") return false;
	
	
	if (trim($_POST['abstract']) == "") return false;
	
	if (trim($_POST['doc']) == "") return false;
	
	
	
	/* CODICE ALTERNATIVO a tutti gli if
	 * NB: i tre cicli foreach precedenti sono riportati come sopra!
	 * TODO: verificare se potrebbe funzionare CODICE ALTERNATIVO a tutti gli if
	
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
	
	// query parziale: "document WHERE "
	$partialQuery = "";
	
	//TODO:parte della query riservata ad eventuali join con tabelle diverse da documento
	$from = ""
	
	//contatore che segna quante condizioni che vanno separate da AND sono state inserite
	$k = 0;
	/* quando inserisco una condizione devo sapere se è la prima,
	 * altrimenti devo mettere un AND prima di riportarla
	 * 
	 * (un controllo analogo verrà fatto nei cicli foreach dove le condizioni andranno separate da OR)
	 */
	
	//identificatore completo è impostato?
	if ( isset($_POST['identificatore']) ) {
		$id = $_POST['identificatore'];
		// separa la stringa dell'identificatore in 4 sottostringhe
		// (i separatori devono essere caratteri '-')
		list($className, $ver, $year, $num) = explode("-",$id);
			
		//controlli per trovare il numero corrispondente alla classe
		switch($className) {
			case "A1":
				$class = 1;
				break;
			case "DQ":
				$class = 2;
				break;
			case "OA":
				$class = 3;
				break;
			case "PO":
				$class = 4;
				break;
			case "DT":
				$class = 5;
				break;
			case "RS":
				$class = 6;
				break;
			case "VR":
				$class = 7;
				break;
			case "EX":
				$class = 8;
				break;
			case "LN":
				$class = 9;
				break;
		}
		
		//condizione sulla classe
		$partialQuery .= "documento.classe = $class" ;
		
		//condizione sulla versione
		$partialQuery .= " AND documento.versione = $ver" ;
		
		//condizione sull'anno
		$partialQuery .= " AND documento.anno = $year" ;
		
		//condizione sul numero
		$partialQuery .= " AND documento.id = $num" ;
		
		//4 condizioni 'AND' inserite
		$k += 4;
	}
	
	
	//identificatore in parti separate
	
	//classe è stata impostata?
	if ( isset($_POST['classe']) ) {
		//contatore che segna quante condizioni che vanno separate da OR sono state inserite
		$i = 0;
		
		foreach($_POST['classe'] as $value) {
			//controlla se esiste almeno una condizione 'OR' preimpostata
			if ($i > 0) { $partialQuery.= " OR "; }
			
			$partialQuery .= "documento.classe = $value" ;
			
			//condizione 'OR' inserita
			$i++;
		}
		
		//condizione 'AND' inserita
		$k++;
	}
	
	if ( isset($_POST['versione']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "documento.versione = ".$_POST['versione'];
		
		$k++;
	}
	
	if ( isset($_POST['anno']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "documento.anno = ".$_POST['anno'];
		
		$k++;
	}
	

	if ( isset($_POST['numero']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "documento.id = ".$_POST['numero'];
		
		$k++;
	}
	
	
	//data del tipo gg/mm
	if ( isset($_POST['data']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$d = $_POST['data'];
		
		list($giorno, $mese) = explode("/",$d);
		
		$partialQuery .= "documento.giorno = $giorno AND documento.mese = $mese";
		
		$k += 2;
	}
	
	
	
	/* TODO:revisione attualmente non individuata
	if ( isset($_POST['revisione']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "documento.giorno = ".$_POST['revisione'];
		
		$k++;
	}
	*/
	
	
	
	
	//stato è impostato?
	if ( isset($_POST['stato']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		//contatore che segna quante condizioni che vanno separate da OR sono state inserite
		$i = 0;
		
		foreach($_POST['stato'] as $value) {
			//controlla se esiste almeno una condizione 'OR' preimpostata
			if ($i > 0) { $partialQuery.= " OR "; }
			
			$partialQuery .= "documento.stato = $value" ;
			
			//condizione 'OR' inserita
			$i++;
		}
		
		//condizione 'AND' inserita
		$k++;
	}
	
	
	if ( isset($_POST['lingua']) ) {
		$lingua = $_POST['lingua'];
		$lng = "";
		
		switch() {
			case "IT":
			case "italiano":
			case "Italiano":
				$lng = "it";
				break;
			case "ENG":
			case "inglese":
			case "Inglese":
				$lng = "eng";
				break;
			case "DE":
			case "tedesco":
			case "Tedesco":
				$lng = "de";
				break;
		}
		
		if ($lng != "") {
			//controlla se esiste almeno una condizione 'AND' preimpostata
			if ($k > 0) { $partialQuery.= " AND "; }
		
			$partialQuery .= "documento.supp_$lng = 1" ;
			
			$k++;
		}
	}
	
	
	if ( isset($_POST['sede']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "documento.sede = ".$_POST['sede'];
		
		$k++;
	}
	
	
	/* 
	 * TODO: fare controllo sui permessi dell'utente
	 * 
	 */
	//livello di confidenzialità è impostata?
	if ( isset($_POST['livello']) ) {
		// TODO: livello di autorizzazione dell'utente:funziona così? 
		$level = $_SESSION[user_logged]->getSecurityLevel();
		
		//NB: WORKAROUND che dipende da come sono stati messi i livelli nel DB
		//nel db i livelli hanno numeri invertiti rispetto al modello security_levels? se si
		//$level = 3 - $level;
		
		//contatore che segna quante condizioni che vanno separate da OR sono state inserite
		$i = 0;
		
		foreach($_POST['livello'] as $value) {
			//TODO:controllare! confronto dipende da come sono stati messi i livelli nel DB
			if ( $value < $level ) {
				//controlla se esiste almeno una condizione 'AND' preimpostata
				if ( ($k > 0) && ($i == 0) ) { $partialQuery.= " AND "; }
		
				//controlla se esiste almeno una condizione 'OR' preimpostata
				if ($i > 0) { $partialQuery.= " OR "; }
				
				$partialQuery .= "documento.liv_conf = $value" ;
				
				//condizione 'OR' inserita
				$i++;
			}
		}
		
		//condizione 'AND' inserita
		if ($i>0) $k++;
	}
	
	
	if ( isset($_POST['allegati']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "documento.allegati = ".$_POST['allegati'];
		
		$k++;
	}
	
	
	//TODO:query pagine
	if ( isset($_POST['pagine']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST['pagine'];
		
		$k++;
	}
	
	//TODO:query approvato
	if ( isset($_POST['approvato']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST['approvato'];
		
		$k++;
	}
	
	//TODO:query autore
	if ( isset($_POST['autore']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST['autore'];
		
		$k++;
	}
	
	
	
	//TODO:query ricerca all'interno dell'abstract
	if ( isset($_POST['abstract']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST[''];
		
		$k++;
	}

	//TODO:query ricerca all'interno del documento
	if ( isset($_POST['doc']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST[''];
		
		$k++;
	}
	
	/* template
	if ( isset($_POST['']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST[''];
		
		$k++;
	}
	*/
	

	return $partialQuery;
}

?>