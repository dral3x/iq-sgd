<?php
// aggiungo header
$page_title = "Ricerca";
include (dirname(__FILE__) . '/headerView.php');

//eventuali messaggi ( per ora riguardanti solo livello di confidenzialità non accessibile dall'utente )
if ($ricerca->isSetMessage()) {
	echo '<div id="error">';
	echo '<fieldset><legend>Messaggio:</legend>';
	echo '<div style="margin: 2em;">';
	echo $ricerca->getMessage();
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
}

//SPOSTATO da sotto
if ($ricerca->isSetError()) {
	
	echo '<div id="error">';
	echo '<fieldset><legend>Messaggio:</legend>';
	echo '<div style="margin: 2em;">';
	echo $ricerca->getError();
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
	
} else if ( isset($results) ) {
	
	
	
	// mostro l'elenco dei risultati
	echo '<div id="result">'."\n";
	echo '<fieldset><legend>Ecco i risultati:</legend>';
	echo '<div id="container">'."\n";
	foreach ($results as $document) {
		echo '<div id="row">';
		echo '<div id="left"><a href="visualizza.php?document_id='.$document->getID().'"><b>'.$document->getIdentifier().'</b></a> di '.$document->getAuthor().'</div>';
		echo '</div>'."\n";
	}
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
}
//SPOSTATO da sotto - END


?>
<h1><?php echo $page_title; ?></h1>
<?php

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

if ($ricerca->getTypeOfSearch() == "simple") { ?> 
<div id="search">
<form name="RicercaSemplice" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
	<fieldset>
	<legend>Ricerca Semplice</legend>
		<input type="text" name="parametroRicerca" size="40" <?php /*if (isset($_POST['parametroRicerca']))*/ echo 'value="'.$_POST['parametroRicerca'].'" '; ?> />
		
		<!-- workaround: campo aggiuntivo necessario per aggirare un bug di explorer (pressione del tasto invio non fa submit) -->
		<input style="display:none;" type="text" name="inutile" size="0" />
		
		<input type="hidden" name="search_type" value="simple" />
		<input type="submit" name="submit" value="Ricerca Semplice" />
	</fieldset>
</form>
</div>

<br />


<?php
} else if ($ricerca->getTypeOfSearch() == "advanced") {
?>
<div id="search">
<form name="RicercaAvanzata"  action="ricerca.php" method="POST">
	<fieldset>
	<legend>Ricerca Avanzata</legend>	
 	<br />
 	
		<fieldset>
		<legend>Campi dell'Intestazione</legend>
			
			<p>
			<label>Identificatore Completo (NB: inserire il carattere "-" per separare le parti dell'id) : <input type="text" name="identificatore" <?php echo 'value="'.$_POST['identificatore'].'" '; ?> /></label><br />
			</p>
			
			<fieldset>
			<legend>(in alternativa all'ID completo) Campi dell'Identificatore</legend>
				
				<fieldset>
				<legend>Classe di appartenenza:</legend>
					<label><input type="checkbox" name="classe[]" value="1" <?php $i=0; if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "1") ) { $i++; echo 'checked'; }?> />A1 - Allegato</label><br />
					<label><input type="checkbox" name="classe[]" value="2" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "2") ) { $i++; echo 'checked'; }?> />DQ - Documento per la Qualit&agrave;</label><br />
					<label><input type="checkbox" name="classe[]" value="3" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "3") ) { $i++; echo 'checked'; }?> />OA - Organizzazione Aziendale</label><br />
					<label><input type="checkbox" name="classe[]" value="4" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "4") ) { $i++; echo 'checked'; }?> />PO - Procedura Operativa</label><br />
					<label><input type="checkbox" name="classe[]" value="5" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "5") ) { $i++; echo 'checked'; }?> />DT - Documento Tecnico</label><br />
					<label><input type="checkbox" name="classe[]" value="6" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "6") ) { $i++; echo 'checked'; }?> />RS - Rapporto Statistico/Gestionale</label><br />
					<label><input type="checkbox" name="classe[]" value="7" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "7") ) { $i++; echo 'checked'; }?> />VR - Verbale di Riunione</label><br />
					<label><input type="checkbox" name="classe[]" value="8" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "8") ) { $i++; echo 'checked'; }?> />EX - Documento Esterno</label><br />
					<label><input type="checkbox" name="classe[]" value="9" <?php if ( isset($_POST['classe']) && ($_POST['classe'][$i] == "9") ) { echo 'checked'; }?> />LN - Legge/Norma</label><br />
				</fieldset>
				
				<p>
				<label>Versione: <input style="margin-left: 0.5em;" class="campo" type="text" name="versione" <?php echo 'value="'.$_POST['versione'].'" '; ?>/></label><br />
				<label>Anno: 	 <input style="margin-left: 2.2em;" class="campo" type="text" name="anno" <?php echo 'value="'.$_POST['anno'].'" '; ?>/></label><br />
				<label>Numero: 	 <input style="margin-left: 0.9em;" class="campo" type="text" name="cont" <?php echo 'value="'.$_POST['cont'].'" '; ?>/></label><br />
				</p>
				
			</fieldset>
		 	
			<p>
			Data di Compilazione - <label> giorno: <input class="campo" type="text" name="giorno" <?php echo 'value="'.$_POST['giorno'].'" '; ?>/></label> <label> mese: <input type="text" name="mese" <?php echo 'value="'.$_POST['mese'].'" '; ?>/></label><br />
			
			<label>Revisione del documento: <input class="campo" type="text" name="revisione" <?php echo 'value="'.$_POST['revisione'].'" '; ?>/></label><br />
			</p>
			
			<fieldset>
			<legend>Stato</legend>
	 			<label><input type="checkbox" name="stato[]" value="bozza" <?php $i=0; if ( isset($_POST['stato']) && ($_POST['stato'][$i] == "bozza") ) { $i++; echo 'checked'; }?> />Bozza</label><br />
				<label><input type="checkbox" name="stato[]" value="approvato" <?php if ( isset($_POST['stato']) && ($_POST['stato'][$i] == "approvato") ) { $i++; echo 'checked'; }?> />Approvato</label><br />
				<label><input type="checkbox" name="stato[]" value="obsoleto" <?php if ( isset($_POST['stato']) && ($_POST['stato'][$i] == "obsoleto") ) { echo 'checked'; }?> />Obsoleto</label><br />
			</fieldset>
			<br/>
			<fieldset>
			<legend>Lingua</legend>
	 			<label><input type="checkbox" name="lingua[]" value="it" <?php $i=0; if ( isset($_POST['lingua']) && ($_POST['lingua'][$i] == "it") ) { $i++; echo 'checked'; }?> />Italiano</label><br />
				<label><input type="checkbox" name="lingua[]" value="eng" <?php if ( isset($_POST['lingua']) && ($_POST['lingua'][$i] == "eng") ) { $i++; echo 'checked'; }?> />English</label><br />
				<label><input type="checkbox" name="lingua[]" value="de" <?php if ( isset($_POST['lingua']) && ($_POST['lingua'][$i] == "de") ) { echo 'checked'; }?> />Deutsch</label><br />
			</fieldset>
			
		</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Campi del Pi&eacute; di Pagina</legend>
			<p>
			<label>Sede di Archiviazione: <input class="campo" type="text" name="sede" <?php echo 'value="'.$_POST['sede'].'" '; ?>/></label><br />
			</p>
			
			<fieldset>
			<legend>Livello di Confidenzialit&agrave;</legend>
				<label><input type="checkbox" name="livello[]" value="0" <?php $i=0; if ( isset($_POST['livello']) && ($_POST['livello'][$i] == "0") ) { $i++; echo 'checked'; }?> />L0</label><br />
				<label><input type="checkbox" name="livello[]" value="1" <?php if ( isset($_POST['livello']) && ($_POST['livello'][$i] == "1") ) { $i++; echo 'checked'; }?> />L1</label><br />
				<label><input type="checkbox" name="livello[]" value="2" <?php if ( isset($_POST['livello']) && ($_POST['livello'][$i] == "2") ) { $i++; echo 'checked'; }?> />L2</label><br />
				<label><input type="checkbox" name="livello[]" value="3" <?php if ( isset($_POST['livello']) && ($_POST['livello'][$i] == "3") ) { echo 'checked'; }?> />Pubblico</label><br />
			</fieldset>
		  	
		  	<p>
			<label>Numero di Allegati: <input class="campo" type="text" name="allegati" <?php echo 'value="'.$_POST['allegati'].'" '; ?>/></label><br />
		
			<label>Numero di Pagine del Documento: <input class="campo" style="background-color:lightgrey; text-align:center;" disabled value="(campo disabilitato)" type="text" name="pagine" /></label><br />
		  
			<label>Approvato da: <input class="campo" type="text" name="approvatore" <?php echo 'value="'.$_POST['approvatore'].'" '; ?>/></label><br />
		
			<label>Autore: <input class="campo" type="text" name="autore" <?php echo 'value="'.$_POST['autore'].'" '; ?>/></label><br />
			</p>
			
		</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Parole da Ricercare nell'abstract del documento</legend>	
			<textarea name="abstract" rows="2" cols="40" ><?php echo $_POST['abstract']; ?></textarea><br />
	 	</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Parole da Ricercare all'interno del documento</legend>	
			<textarea name="doc" rows="3" cols="40" ><?php echo $_POST['doc']; ?></textarea><br />
		</fieldset>
	
		<br />
 
	<input type="hidden" name="search_type" value="advanced" />
	<input type="submit" name="submit" value="Ricerca Avanzata" />
	
	<input style="float: right; margin-right: 1.5em;" type="submit" name="reset" value="Resetta tutti i campi" />
	
	</fieldset>
</form>
</div>

<br />



<?php
}

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>