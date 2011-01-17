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

//azione che resetta tutti i campi
if (isset($_POST['reset'])) {
	
	$fields = array('identificatore','classe','versione','anno','cont','giorno','mese','revisione','stato','lingua','sede','livello','allegati','approvatore','autore','abstract','doc');
	foreach($fields as $field) {
		unset($_POST[$field]);
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
			$ricerca->setError("Nessuna chiave di ricerca inserita");
		} else {
			$results = $ricerca->doSimpleSearch($_POST['parametroRicerca']);
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
			//potrebbe essere stato già impostato un Messaggio (vedi livello di confidenzialità)
			if ( ! $ricerca->isSetMessage() ) { $ricerca->setError("Nessuna chiave di ricerca inserita"); }
		} else {
			$results = $ricerca->doAdvancedSearch( getAdvancedKeys($ricerca) );
		}
	}
	
}

// carico la vista da mostrare all'utente
require ('view/ricercaView.php');




//FUNZIONI


// NB:  per accedere al livello di confidenzialità
//		devo passare $ricerca alla funzione, poiché non è un metodo!
// true: se nessun parametro della ricerca avanzata è stato impostato
// false: se almeno un parametro della ricerca avanzato è stato impostato
function noParameterIsSet($ricerca) {
		
	$fields = array('identificatore','versione','anno','cont','giorno','mese','revisione','sede','allegati',/*'pagine',*/'approvatore','autore','abstract','doc');
	
	foreach($fields as $field) {
		//il campo in esame è stato impostato? se è così ritorna falso
		if ( trim($_POST[$field]) != "" ) return false;
	}
	
	$checkboxes = array('classe','stato','lingua');
	
	// cicli foreach corrispondenti ai tre gruppi di checkbox
	foreach($checkboxes as $checkbox) {
		if ( isset($_POST[$checkbox]) ) {
			foreach($_POST[$checkbox] as $value) {
				if ( isset($value) ) return false;
			}
		}
	}
	
	if ( isset($_POST['livello']) ) {
		// preleva il livello di confidenzialità dell'utente
		$level = $ricerca->getSessionUser()->getConfidentialLevel();
		
		foreach($_POST['livello'] as $value) {
			if ( isset($value) ) {
				if ( $value >= $level ) { return false; }
				else { $ricerca->addMessage("Non si disponde del livello di confidenzialit&agrave; necessario per ricercare documenti di livello L$value<br/>");
				}
			}
		}
	}
		
	//a questo punto nessuno dei precedenti parametri è stato impostato
	return true;
}
	
	
// NB:  per accedere al livello di confidenzialità
//		devo passare $ricerca alla funzione, poiché non è un metodo!
// costruisce la parte della query contenente le chiavi di una ricerca avanzata
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
		list($className, $ver, $year, $cont) = explode("-",$id);
		
		//controlli per trovare il numero corrispondente alla classe
		switch(strtoupper($className)) {
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
		$partialQuery .= "d.classe = $class" ;
		
		//condizione sulla versione
		$partialQuery .= " AND d.versione = $ver" ;
		
		//condizione sull'anno
		$partialQuery .= " AND d.anno = $year" ;
		
		//condizione sul numero/contatore
		$partialQuery .= " AND d.cont = $cont" ;
		
		//4 condizioni 'AND' inserite
		$k += 4;
	}
	
	
	$checkboxes = array('classe','stato','lingua');
	
	// parte della query riguardante classe, stato e lingua
	foreach($checkboxes as $checkbox) {
		//$checkbox è impostato?
		if ( isset($_POST[$checkbox]) ) {
			//controlla se esiste almeno una condizione 'AND' preimpostata
			if ($k > 0) { $partialQuery.= " AND "; }
			
			//contatore che segna quante condizioni che vanno separate da OR sono state inserite
			$i = 0;
			
			$partialQuery .= "( ";
			foreach($_POST[$checkbox] as $value) {
				//controlla se esiste almeno una condizione 'OR' preimpostata
				if ($i > 0) { $partialQuery.= " OR "; }
				
				//controllo necessario per lingua perchè query è diversa (lingua è distribuita su tre attributi)
				if ($checkbox == 'lingua') {
					$partialQuery .= "d.supp_$value = 1" ;
				} else {
					$partialQuery .= "d.$checkbox = '$value' " ;
				}
				
				//condizione 'OR' inserita
				$i++;
			}
			$partialQuery .= ") ";
			
			//condizione 'AND' inserita
			$k++;
		}
	
	}	
	
	
	$fields = array('versione','anno','cont','giorno','mese','revisione','allegati');
	
	foreach($fields as $field) {
		if ( isset($_POST[$field])  && $_POST[$field]!="" ) {
			//controlla se esiste almeno una condizione 'AND' preimpostata
			if ($k > 0) { $partialQuery.= " AND "; }
			
			$partialQuery .= "lower(d.$field) = ".strtolower(trim($_POST[$field]));
			
			$k++;
		}
	}
	
	
	// sede, " campo LIKE %chiave% "
	if ( isset($_POST['sede']) && $_POST['sede']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "lower(d.sede) LIKE '%".strtolower(trim($_POST['sede']))."%' ";
		
		$k++;
	}
	
	
	// preleva il livello di confidenzialità dell'utente
		$level = $ricerca->getSessionUser()->getConfidentialLevel();
	
	// livello di confidenzialità è impostato?
	if ( isset($_POST['livello']) ) {
		
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
	// se non è stata impostata dall'utente alcuna condizione sui livelli di confidenzialità
	// per default imposta la query in modo che possa accedere solo ai livelli concessi all'utente
	else {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$partialQuery .= "d.liv_conf >= '$level' ";
		
		$k++;
	}	
	
	
	if ( isset($_POST['approvatore']) && $_POST['approvatore']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$from .= "INNER JOIN utente AS ua ON ua.matricola = d.approvatore ";
		
		$appr = strtolower(removeAccent(trim( $_POST['approvatore'] )));
		list($name, $surname) = explode(" ",$appr);
		
		//potrei avere nome in $surname e cognome in $name
		$partialQuery .= "(lower(ua.nome) LIKE '$name' OR lower(ua.nome) LIKE '$surname' OR lower(ua.cognome) LIKE '$name' OR lower(ua.cognome) LIKE '$surname') ";
		
		$k++;
	}
	
	if ( isset($_POST['autore']) && $_POST['autore']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		//presenza di join
		$from .= "INNER JOIN autore AS a ON d.id = a.id_doc INNER JOIN utente AS ub ON ub.matricola = a.mat_utente ";
		
		$auth = strtolower(removeAccent(trim( $_POST['autore'] )));
    	list($name, $surname) = explode(" ",$auth);
		
		//potrei avere nome in $surname e cognome in $name
		$partialQuery .= "(lower(ub.nome) LIKE '$name' OR lower(ub.nome) LIKE '$surname' OR lower(ub.cognome) LIKE '$name' OR lower(ub.cognome) LIKE '$surname') ";
		
		$k++;
	}
	
	
	if ( isset($_POST['abstract']) && $_POST['abstract']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$strings = strtolower($_POST['abstract']);
		
		//splittare stringa
		$keywords = explode(" ",$strings);
		
		$from .= "LEFT OUTER JOIN valori_campo_medium AS avcm ON d.id = avcm.id_doc ";
		
		$partialQuery .= "avcm.id_campo = 5 AND ";
		
		//numero di parole inserite (per controllare se è già stata inserita una parola e serve AND)
		$j = 0;
		
		foreach ( $keywords  as $key ) {
			if ( $j > 0 ) {$partialQuery .= " AND "; }
			
			$key = "'%$key%'";
			
			$partialQuery .= "( lower(avcm.valore_it) LIKE  $key OR lower(avcm.valore_eng) LIKE  $key OR lower(avcm.valore_de) LIKE  $key ) ";
			
	   		$j++;
		}
		
		$k++;
	}
	
	
	if ( isset($_POST['doc']) && $_POST['doc']!="" ) {
		//controlla se esiste almeno una condizione 'AND' preimpostata
		if ($k > 0) { $partialQuery.= " AND "; }
		
		$strings = strtolower($_POST['doc']);
		
		//splittare stringa
		$keywords = explode(" ",$strings);
		
		$from .= "LEFT OUTER JOIN valori_campo_small AS vcs ON d.id = vcs.id_doc ".
					"LEFT OUTER JOIN valori_campo_medium AS vcm ON d.id = vcm.id_doc ".
					"LEFT OUTER JOIN valori_campo_long AS vcl ON d.id = vcl.id_doc ";
		
		$partialQuery .= "vcm.id_campo != 5 AND ";
		
		//numero di parole inserite (per controllare se è già stata inserita una parola e serve AND)
		$j = 0;
		
		foreach ( $keywords  as $key ) {
			if ( $j > 0 ) {$partialQuery .= " AND "; }
			
			$key = "'%$key%'";
			
			$partialQuery .= "( lower(vcs.valore_it) LIKE  $key OR lower(vcs.valore_eng) LIKE  $key ".
				"OR lower(vcs.valore_de) LIKE  $key OR lower(vcm.valore_it) LIKE  $key ".
				"OR lower(vcm.valore_eng) LIKE  $key OR lower(vcm.valore_de) LIKE  $key ".
				"OR lower(vcl.valore_it) LIKE  $key OR lower(vcl.valore_eng) LIKE  $key ".
				"OR lower(vcl.valore_de) LIKE  $key ) ";
			
	   		$j++;
		}
		
		$k++;
	}
		
	//inserisce eventuali condizioni di join
	$partialQuery = $from . $partialQuery ;
	
	return $partialQuery;
}

/* rimuove gli accenti */
function removeAccent($string) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

	return $string;
}
	

?>