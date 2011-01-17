<?php 
// aggiungo header
$page_title = "Visualizza documento";
include (dirname(__FILE__) . '/headerView.php');
?>
<h1><?php echo $page_title; ?></h1> 
<?php 
if (isset($error_message)) {
	echo '<div id="error">';
	echo '<fieldset style="margin: 0px"><legend>Messaggio:</legend>';
	echo '<div style="margin: 2em;">';
	echo $error_message;
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
}

if (isset($highlight_message)) {
	echo '<div id="highlight">';
	echo '<fieldset style="margin: 0px"><legend>Messaggio:</legend>';
	echo '<div style="margin: 2em;">';
	echo $highlight_message;
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
}


// se la variabile $document è stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($document)) {
?>
<div id="view">
<fieldset style="margin: 0px">
	<legend>Documento Visualizzato</legend>
	<?php
	// mostro informationi base sul documento
	?>
	<fieldset>
		<legend >Intestazione</legend>
		<div id="field"><b>Data: </b> <?php echo $document->getCreationDay()."/".$document->getCreationMonth()."/".$document->getCreationYear();?><br /></div>
		<div id="field"><b>ID.Doc: </b> <?php echo $document->getIdentifier(); ?><br /></div>
		<div id="field"><b>Revisione: </b> <?php echo $document->getRevision(); ?><br /></div>
		<div id="field"><b>Lingua: </b> <?php echo "Italiano"; ?><br /></div>
		<div id="field"><b>Stato: </b> <?php echo $document->getState();?><br /></div>
		<div id="field"><b>Sede archiviazione: </b> <?php echo htmlentities($document->getLocation()); ?><br /></div>
		<div id="field"><b>Liv. Confidenzialit&agrave;: </b> <?php echo $document->getConfidentialLevel();?><br /></div>
		<div id="field"><b>Autori: </b> <?php echo htmlentities($document->getAuthor()); ?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php echo htmlentities($document->getApprover()->getDisplayName()); ?><br /></div>
	</fieldset>
	<br/>
	<?php 
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field"><b>'.htmlentities($field->getName()).'</b><br/>'.nl2br($field->getContent()).'<br/><br/></div>'."\n";
	}
	?>
</fieldset>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>