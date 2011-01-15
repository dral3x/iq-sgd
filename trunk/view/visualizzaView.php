<?php 
// aggiungo header
$page_title = "Visualizza documento";
include (dirname(__FILE__) . '/headerView.php');

?>
<h1><?php echo $page_title; ?></h1> 
<?php 
if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

if (isset($highlight_message)) {
	echo '<div id="hightlight">' . $highlight_message . '</div>';
}


// se la variabile $document è stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($document)) {
?>
<div >
<fieldset>
	<legend>Documento Visualizzato</legend>
	<?php
	// mostro informationi base sul documento
	?>
	<fieldset>
		<legend >Intestazione</legend>
		<div id="field"><b>Data: </b> <?php echo $document->getCreationDay()."/".$document->getCreationMonth()."/".$document->getCreationYear();?><br /></div>
		<div id="field"><b>ID.Doc: </b> <?php echo $document->getIdentifier(); ?><br /></div>
		<div id="field"><b>Revisione: </b> <?php echo $document->getRevision(); ?><br /></div>
		<div id="field"><b>Lingua: </b> <?php ?><br /></div>
		<div id="field"><b>Stato: </b> <?php echo $document->getState();?><br /></div>
		<div id="field"><b>Sede archiviazione: </b> <?php echo $document->getLocation(); ?><br /></div>
		<div id="field"><b>Liv. Confidenzialit&agrave;: </b> <?php echo $document->getConfidentialLevel();?><br /></div>
		<div id="field"><b>Autori: </b> <?php echo $document->getAuthor(); ?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php echo $document->getApprover()->getDisplayName(); ?><br /></div>
	</fieldset>
	<br/>
	<?php 
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field"><b>'.htmlentities($field->getName()).'</b><br/>'.htmlentities($field->getContent()).'<br/><br/></div>'."\n";
	}
	?>
</fieldset>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>