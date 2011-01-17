<?php
// aggiungo header
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
} else {

?>
<div id="viewList">
<fieldset style="margin: 0px">
	<legend>Documenti richiesti</legend>
	<?php
	// se ci sono documenti, li mostro in forma di elenco
	if (isset($documents) && count($documents)>0) {
		echo '<div id="container">'."\n";
		foreach ($documents as $document) {
			echo '<div id="row">';
			echo '<div id="left"><a href="visualizza.php?document_id='.$document->getID().'"><b>'.$document->getIdentifier().' (rev. '.$document->getRevision().')</b></a> di '.htmlentities($document->getAuthor()).'</div>';
			echo '</div>'."\n";
		}
		echo '</div>'."\n";
	} else {
		// non ci sono documenti
		echo '<div id="empty">Nessun documento in questa categoria</div>'."\n";
	}
	?>
</fieldset>
</div>
<?php 
} // fine else isset error_message

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>