<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Ricerca</title>
</head>
<body>
<?php
echo Page::getHeader();

if ($login->getErrorMessage()) {
	echo '<p>' . $login->getErrorMessage() . '</p>';
}
?>
<h1>Ricerca</h1>
<div>

<!-- ###NB: forse bisogna cambiare 'method' da GET a POST e sistemare il valore di 'action' -->

<form name="Ricerca" action="ricercaCreator.php" method="GET">
	<fieldset>
	<legend>Ricerca Semplice</legend>
		<input type="text" name="parametriRicerca" size="40" />
		<input type="submit" value="Cerca" />
	</fieldset>
</form>
</div>

<div>

<!-- ###NB: stessa cosa qui, 'method' da GET a POST e sistemare il valore di 'action' -->

<form name="Ricerca Avanzata"  action="ricercaCreator.php" method="GET">
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
					<input type="checkbox" name="A1" value="A1" />A1 - Allegato  <br />
					<input type="checkbox" name="DQ" value="DQ" />DQ - Documento per la Qualit&agrave;  <br />
					<input type="checkbox" name="OA" value="OA" />OA - Organizzazione Aziendale <br />
					<input type="checkbox" name="PO" value="PO" />PO - Procedura Operativa  <br />
					<input type="checkbox" name="DT" value="DT" />DT - Documento Tecnico <br />
					<input type="checkbox" name="RS" value="RS" />RS - Rapporto Statistico/Gestionale  <br />
					<input type="checkbox" name="VR" value="VR" />VR - Verbale di Riunione <br />
					<input type="checkbox" name="EX" value="EX" />EX - Documento Esterno <br />
					<input type="checkbox" name="LN" value="LN" />LN - Legge/Norma  <br />
				</fieldset>
				
				<p>
				<label>Versione: <input type="text" name="versione" /></label><br />
				<label>Anno: <input type="text" name="anno" /></label><br />
				<label>Numero: <input type="text" name="numero" /></label><br />
				</p>
				
			</fieldset>
		 	
			
			<p>
			<label>Data di Compilazione: <input type="text" name="data" /></label><br />
			
			<label>Revisione del documento: <input type="text" name="revisione" /></label><br />
			</p>
			
			<fieldset>
			<legend>Stato</legend>
	 			<input type="checkbox" name="bozza" value="bozza"/>Bozza<br />
				<input type="checkbox" name="approvato" value="approvato"/>Approvato<br />
				<input type="checkbox" name="obsoleto" value="obsoleto"/>Obsoleto<br />
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
				<input type="checkbox" name="l0" value="l0"/>L0<br />
				<input type="checkbox" name="l1" value="l1"/>L1<br />
				<input type="checkbox" name="l2" value="l2"/>L2<br />
				<input type="checkbox" name="pubblico" value="pubblico"/>Pubblico<br />
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
			<input type="text" name="abstract" size="40" /><br />
	 	</fieldset>
	
		<br />
	
		<fieldset>
		<legend>Parole da Ricercare all'interno del documento</legend>	
			<input type="text" name="doc" size="40" /><br />
		</fieldset>
	
		<br />
  
	<input type="submit" value="Cerca" />

	</fieldset>
</form>
</div>
<?php echo Page::getFooter(); ?>
</body>
</html>