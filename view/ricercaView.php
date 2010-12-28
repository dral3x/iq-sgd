<?php
// aggiungo header
$page_title = "Ricerca";
include (dirname(__FILE__) . '/headerView.php');
?>
<h1>Ricerca</h1>
<?php

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

if ($ricerca->getTypeOfSearch() == "simple") { ?> 
<div>
<form name="RicercaSemplice" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
	<fieldset>
	<legend>Ricerca Semplice</legend>
		<input type="text" name="parametroRicerca" size="40" <?php if (isset($_POST['parametroRicerca'])) echo 'value="'.$_POST['parametroRicerca'].'" '; ?> />
		<input type="hidden" name="search_type" value="simple" />
		<input type="submit" name="submit" value="Ricerca Semplice" />
	</fieldset>
</form>
</div>

<br />


<?php
} else if ($ricerca->getTypeOfSearch() == "advanced") {
?>
<div>
<form name="RicercaAvanzata"  action="ricerca.php" method="POST">
	<fieldset>
	<legend>Ricerca Avanzata</legend>	
 	<br />
 	
		<fieldset>
		<legend>Campi dell'Intestazione</legend>
			
			<p>
			<label>Identificatore Completo : <input type="text" name="identificatore" /></label><br />
			</p>
			
			<fieldset>
			<legend>(in alternativa all'ID completo) Campi dell'Identificatore</legend>
				
				<fieldset>
				<legend>Classe di appartenenza:</legend>
					<label><input type="checkbox" name="classe[]" value="A1" />A1 - Allegato</label><br />
					<label><input type="checkbox" name="classe[]" value="DQ" />DQ - Documento per la Qualit&agrave;</label><br />
					<label><input type="checkbox" name="classe[]" value="OA" />OA - Organizzazione Aziendale</label><br />
					<label><input type="checkbox" name="classe[]" value="PO" />PO - Procedura Operativa</label><br />
					<label><input type="checkbox" name="classe[]" value="DT" />DT - Documento Tecnico</label><br />
					<label><input type="checkbox" name="classe[]" value="RS" />RS - Rapporto Statistico/Gestionale</label><br />
					<label><input type="checkbox" name="classe[]" value="VR" />VR - Verbale di Riunione</label><br />
					<label><input type="checkbox" name="classe[]" value="EX" />EX - Documento Esterno</label><br />
					<label><input type="checkbox" name="classe[]" value="LN" />LN - Legge/Norma</label><br />
				</fieldset>
				
				<p>
				<label>Versione: <input type="text" name="versione" /></label><br />
				<label>Anno: 	 <input type="text" name="anno" /></label><br />
				<label>Numero: 	 <input type="text" name="numero" /></label><br />
				</p>
				
			</fieldset>
		 	
			<p>
			<label>Data di Compilazione: <input type="text" name="data" /></label><br />
			
			<label>Revisione del documento: <input type="text" name="revisione" /></label><br />
			</p>
			
			<fieldset>
			<legend>Stato</legend>
	 			<label><input type="checkbox" name="stato[]" value="bozza" />Bozza</label><br />
				<label><input type="checkbox" name="stato[]" value="approvato" />Approvato</label><br />
				<label><input type="checkbox" name="stato[]" value="obsoleto" />Obsoleto</label><br />
			</fieldset>
			
			<p>
			<label>Lingua: <input type="text" name="lingua" /></label><br />
			</p>
			
		</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Campi del Pi&eacute; di Pagina</legend>
			<p>
			<label>Sede di Archiviazione: <input type="text" name="sede" /></label><br />
			</p>
			
			<fieldset>
			<legend>Livello di Confidenzialit&agrave;</legend>
				<label><input type="checkbox" name="livello[]" value="l0" />L0</label><br />
				<label><input type="checkbox" name="livello[]" value="l1" />L1</label><br />
				<label><input type="checkbox" name="livello[]" value="l2" />L2</label><br />
				<label><input type="checkbox" name="livello[]" value="pubblico" />Pubblico</label><br />
			</fieldset>
		  	
		  	<p>
			<label>Numero di Allegati: <input type="text" name="allegati" /></label><br />
		
			<label>Numero di Pagine del Documento: <input type="text" name="pagine" /></label><br />
		  
			<label>Approvato da: <input type="text" name="approvato" /></label><br />
		
			<label>Autore: <input type="text" name="autore" /></label><br />
			</p>
			
		</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Parole da Ricercare nell'abstract del documento</legend>	
			<textarea name="abstract" rows="2" cols="40"></textarea><br />
	 	</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Parole da Ricercare all'interno del documento</legend>	
			<textarea name="doc" rows="3" cols="40"></textarea><br />
		</fieldset>
	
		<br />
 
	<input type="hidden" name="search_type" value="advanced" />
	<input type="submit" name="submit" value="Ricerca Avanzata" />

	</fieldset>
</form>
</div>

<br />



<?php
}


if (isset($search_error)) {
	
	echo '<div ="error">'.$search_error.'</div>';
	
} else if (isset($search_result)) {
	// mostro l'elenco dei risultati
	
	echo '<div id="results">Ecco i risultati: <br />';
//	foreach ($risultato in $search_result) {
//		echo '<div id="single_result">'.$risultato.'</div>';
//	}
	echo '</div>';
}

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>