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
			$search_result = $ricerca->doSimpleSearch($_POST['parametroRicerca']);
		}
	}
	//l'utente ha richiesto una ricerca avanzata?
	// NB:quando chiama le due funzioni, deve passare l'oggetto $ricerca
	elseif ($ricerca->getTypeOfSearch() == "advanced") {
		
		/* chiama la funzione che controlla se nessun parametro è stato impostato
		 *  fa il corrispettivo di
		 *  if (trim($_POST['parametriRicerca']) == "") {
		 *  per la ricerca avanzata che ha più parametri
		 */
		if (noParameterIsSet($ricerca)) {
			//potrebbe essere stato già impostato un errore (vedi livello di confidenzialità)
			$search_error .= "Nessuna chiave di ricerca inserita";
		} else {
			$search_result = $ricerca->doAdvancedSearch( getAdvancedKeys($ricerca) );
		}
	}
	
}

// se c'è un solo risultato, faccio un redirect su visualizza.php?document_id=<id documento>
// se ci sono più risultati, allora mostro l'elenco tramite la view risultatiView.php
// in tutti gli altri casi, mostro la view standard ricercaView.php

// carico la vista da mostrare all'utente
require ('view/ricercaView.php');


//FUNZIONI


// NB:  per accedere al livello di confidenzialità
//		devo passare $ricerca alla funzione, poiché non è un metodo!
// true: se nessun parametro della ricerca avanzata è stato impostato
// false: se almeno un parametro della ricerca avanzato è stato impostato
function noParameterIsSet($ricerca) {
		
	//nessun campo dell'intestazione è stato impostato?
	
		//nessun campo dell'identificatore è stato impostato?
		if (trim($_POST['identificatore']) != "") return false;
		
		if ( isset($_POST['classe']) ) {
			foreach($_POST['classe'] as $value) {
				if (isset($value)) return false;
			}
		}
		
		if (trim($_POST['versione']) != "") return false;
		if (trim($_POST['anno']) != "") return false;
		if (trim($_POST['numero']) != "") return false;
		//a questo punto nessun campo dell'identificatore è stato impostato
		
	if (trim($_POST['data']) != "") return false;
	if (trim($_POST['revisione']) != "") return false;
	
	if ( isset($_POST['stato']) ) {
		foreach($_POST['stato'] as $value) {
			if (isset($value)) return false;
		}
	}
	
	if (trim($_POST['lingua']) != "") return false;


	//nessun campo del pié di pagina è stato impostato?
	
	if (trim($_POST['sede']) != "") return false;
	
	
	// TODO: livello di confidenzialità, messaggio di errore
	if ( isset($_POST['livello']) ) {
		// preleva il livello di confidenzialità dell'utente
		$level = $ricerca->getSessionUser()->getConfidentialLevel();
		
		foreach($_POST['livello'] as $value) {
			if ( isset($value) ) {
				if ( $value >= $level ) { return false; }
				else { $ricerca->search_error .= "<br/>Non si disponde del livello di confidenzialit&agrave; necessario per ricercare documenti di livello L$value<br/>";
				}
			}
		}
	}
	
	if (trim($_POST['allegati']) != "") return false;
	if (trim($_POST['pagine']) != "") return false;
	if (trim($_POST['approvatore']) != "") return false;
	if (trim($_POST['autore']) != "") return false;
	
	
	if (trim($_POST['abstract']) != "") return false;
	
	if (trim($_POST['doc']) != "") return false;
	
	
	
	/* CODICE ALTERNATIVO a tutti gli if
	 * NB: i tre cicli foreach precedenti sono riportati come sopra!
	 * TODO: verificare se potrebbe funzionare CODICE ALTERNATIVO a tutti gli if
	
	$fields = array('identificatore','versione','anno','numero','data','revisione','lingua','sede','allegati','pagine','approvatore','autore','abstract','doc');
	
	foreach($fields as $field) {
		if ( trim($_POST[$field]) != "" ) return false;
	}
	
	// cicli foreach corrispondenti ai tre gruppi di checkbox
	if ( isset($_POST['classe']) ) {
		foreach($_POST['classe'] as $value) {
				if isset($value) return false;
		}
	}
	
	if ( isset($_POST['stato']) ) {
		foreach($_POST['stato'] as $value) {
			if isset($value) return false;
		}
	}
	
	if ( isset($_POST['livello']) ) {
		// preleva il livello di confidenzialità dell'utente
		$level = $ricerca->getSessionUser()->getConfidentialLevel();
		
		foreach($_POST['livello'] as $value) {
			if ( isset($value) && ( $value >= $level ) ) return false;
		}
	}
	
	// FINE CODICE ALTERNATIVO
	 */
	
	
	//a questo punto nessuno dei precedenti parametri è stato impostato
	return true;
}
	
	
// NB:  per accedere al livello di confidenzialità
//		devo passare $ricerca alla funzione, poiché non è un metodo!
//costruisce la parte della query contenente le chiavi di una ricerca avanzata
function getAdvancedKeys($ricerca) {
	
	// query parziale: "WHERE "
	$partialQuery = "WHERE ";
	
	//parte della query riservata ad eventuali join con tabelle diverse da documento
	$from = "";
	
	//contatore che segna quante condizioni che vanno separate da AND sono state inserite
	$k = 0;
	/* quando inserisco una condizione devo sapere se è la prima,
	 * altrimenti devo mettere un AND prima di riportarla
	 * 
	 * (un controllo analogo verrà fatto nei cicli foreach dove le condizioni andranno separate da OR)
	 */
	
	//identificatore completo è impostato?
	if ( isset($_POST['identificatore']) && $_POST['identificatore']!="" ) {
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
		$partialQuery .= "d.classe = $class " ;
		
		//condizione sulla versione
		$partialQuery .= " AND d.versione = $ver " ;
		
		//condizione sull'anno
		$partialQuery .= " AND d.anno = $year " ;
		
		//condizione sul numero
		$partialQuery .= " AND d.id = $num " ;
		
		//4 condizioni 'AND' inserite
		$k += 4;
	}
	
	
	//identificatore in parti separate
	
	//classe è stata impostata?
	if ( isset($_POST['classe']) ) {
		//contatore che segna quante condizioni che vanno separate da OR sono state inserite
		$i = 0;
		
		$partialQuery .= " ( ";
		foreach($_POST['classe'] as $value) {
			//controlla se esiste almeno una condizione 'OR' preimpostata
			if ($i > 0) { $partialQuery.= " OR "; }
			
			$partialQuery .= "d.classe = '$value' " ;
			
			//condizione 'OR' inserita
			$i++;
		}
		$partialQuery .= " ) ";
		
		//condizione 'AND' inserita
		$k++;
	}
	
	if ( isset($_POST['versione'])  && $_POST['versione']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.versione = ".trim($_POST['versione']);
		
		$k++;
	}
	
	if ( isset($_POST['anno']) && $_POST['anno']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.anno = ".trim($_POST['anno']);
		
		$k++;
	}
	

	if ( isset($_POST['numero']) && $_POST['numero']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.id = ".trim($_POST['numero']);
		
		$k++;
	}
	
	
	//data del tipo gg/mm
	if ( isset($_POST['data']) && $_POST['data']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$d = trim($_POST['data']);
		
		list($giorno, $mese) = explode("/",$d);
		
		$partialQuery .= "d.giorno = $giorno AND d.mese = $mese";
		
		$k += 2;
	}
	
	
	
	if ( isset($_POST['revisione']) && $_POST['revisione']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.revisione = ".trim($_POST['revisione']);
		
		$k++;
	}
	
	
	
	
	//stato è impostato?
	if ( isset($_POST['stato']) ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		//contatore che segna quante condizioni che vanno separate da OR sono state inserite
		$i = 0;
		
		$partialQuery .= "( ";
		foreach($_POST['stato'] as $value) {
			//controlla se esiste almeno una condizione 'OR' preimpostata
			if ($i > 0) { $partialQuery.= " OR "; }
			
			$partialQuery .= "d.stato = '$value' " ;
			
			//condizione 'OR' inserita
			$i++;
		}
		$partialQuery .= ") ";
		
		//condizione 'AND' inserita
		$k++;
	}
	
	
	if ( isset($_POST['lingua']) && $_POST['lingua']!="" ) {
		$lingua = trim($_POST['lingua']);
		$lng = "";
		
		switch($lingua) {
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
		
			$partialQuery .= "d.supp_$lng = 1" ;
			
			$k++;
		}
	}
	
	// TODO: sede, eventuale WHERE campo LIKE %chiave%
	if ( isset($_POST['sede']) && $_POST['sede']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.sede = ".trim($_POST['sede']);
		
		$k++;
	}
	
	
	// livello di confidenzialità è impostato?
	if ( isset($_POST['livello']) ) {
		// preleva il livello di confidenzialità dell'utente
		$level = $ricerca->getSessionUser()->getConfidentialLevel();
		
		//contatore che segna quante condizioni che vanno separate da OR sono state inserite
		$i = 0;
		
		foreach($_POST['livello'] as $value) {
			if ( $value >= $level ) {
				//controlla se esiste almeno una condizione 'AND' preimpostata
				if ( ($k > 0) && ($i == 0) ) { $partialQuery.= " AND ( "; }
				elseif ($i == 0) { $partialQuery .= "( "; }
						
				//controlla se esiste almeno una condizione 'OR' preimpostata
				if ($i > 0) { $partialQuery.= " OR "; }
				
				$partialQuery .= "d.liv_conf = '$value' " ;
				
				//condizione 'OR' inserita
				$i++;
			}
		}
		if ( ($value >= $level) ||  ($i > 0) ) { $partialQuery .= ") "; }
		
		//condizione 'AND' inserita
		if ($i>0) $k++;
	}
	
	
	if ( isset($_POST['allegati']) && $_POST['allegati']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.allegati = ".trim($_POST['allegati']);
		
		$k++;
	}
	
	
	//TODO:query pagine non fattibile al momento
	/*
	if ( isset($_POST['pagine']) && $_POST['pagine']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$_POST['pagine'];
		
		$k++;
	}
	*/
	
	if ( isset($_POST['approvatore']) && $_POST['approvatore']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$from .= "INNER JOIN utente AS ua ON ua.matricola = d.approvatore ";
		
		$appr = trim(strtolower( $_POST['approvatore'] ));
		list($name, $surname) = explode(" ",$appr);
		
		//potrei avere nome in $surname e cognome in $name
		$partialQuery .= "(ua.nome LIKE '$name' OR ua.nome LIKE '$surname' OR ua.cognome LIKE '$name' OR ua.cognome LIKE '$surname') ";
		
		$k++;
	}
	
	if ( isset($_POST['autore']) && $_POST['autore']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		//presenza di join
		$from .= "INNER JOIN autore AS a ON d.id = a.id_doc INNER JOIN utente AS ub ON ub.matricola = a.mat_utente ";
		
		$auth = trim(strtolower( $_POST['autore'] ));
		list($name, $surname) = explode(" ",$auth);
		
		//potrei avere nome in $surname e cognome in $name
		$partialQuery .= "(ub.nome LIKE '$name' OR ub.nome LIKE '$surname' OR ub.cognome LIKE '$name' OR ub.cognome LIKE '$surname') ";
		
		$k++;
	}
	
	if ( isset($_POST['abstract']) && $_POST['abstract']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$strings = ($_POST['abstract']);
		
		//splittare stringa
		$keywords = explode(" ",$strings);
		
		// TODO: condizione usata: abstract è un campo di tipo medium
		$from .= "INNER JOIN valori_campo_medium AS avcm ON d.id = avcm.id_doc ";
		
		$partialQuery .= "avcm.id_campo = 5 AND ";
		
		//numero di parole inserite (per controllare se è già stata inserita una parola e serve AND)
		$j = 0;
		
		foreach ( $keywords  as $key ) {
			if ( $j > 0 ) {$partialQuery .= " AND "; }
			
			$key = "'%$key%'";
			
			$partialQuery .= "( avcm.valore_it LIKE  $key OR avcm.valore_eng LIKE  $key OR avcm.valore_de LIKE  $key ) ";
			
	   		$j++;
		}
		
		$k++;
	}
	
	if ( isset($_POST['doc']) && $_POST['doc']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$strings = ($_POST['doc']);
		
		//splittare stringa
		$keywords = explode(" ",$strings);
		
		$from .= "INNER JOIN valori_campo_small AS vcs ON d.id = vcs.id_doc ".
				"INNER JOIN valori_campo_medium AS vcm ON d.id = vcm.id_doc ".
				"INNER JOIN valori_campo_long AS vcl ON d.id = vcl.id_doc ";
		
		$partialQuery .= "vcm.id_campo != 5 AND ";
		
		//numero di parole inserite (per controllare se è già stata inserita una parola e serve AND)
		$j = 0;
		
		foreach ( $keywords  as $key ) {
			if ( $j > 0 ) {$partialQuery .= " AND "; }
			
			$key = "'%$key%'";
			
			$partialQuery .= "( vcs.valore_it LIKE  $key OR vcs.valore_eng LIKE  $key ".
				"OR vcs.valore_de LIKE  $key OR vcm.valore_it LIKE  $key ".
				"OR vcm.valore_eng LIKE  $key OR vcm.valore_de LIKE  $key ".
				"OR vcl.valore_it LIKE  $key OR vcl.valore_eng LIKE  $key ".
				"OR vcl.valore_de LIKE  $key ) ";
			
	   		$j++;
		}
		
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
	
	//inserisce eventuali condizioni di join
	$partialQuery = $from . $partialQuery ;
	
	return $partialQuery;
}

?>