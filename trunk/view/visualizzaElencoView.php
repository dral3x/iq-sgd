<?php
// aggiungo header
$page_title = "Visualizza elenco di documenti";
include (dirname(__FILE__) . '/headerView.php');

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
} else {

?>
<fieldset>
	<legend>Documenti richiesti</legend>
	<?php
	// se ci sono documenti, li mostro in forma di elenco
	if (isset($documents) && count($documents)>0) {
		echo '<div id="container">'."\n";
		foreach ($documents as $document) {
			echo '<div id="row">';
			echo '<div id="left"><a href="visualizza.php?document_id='.$document->getID().'"><b>'.$document->getTitle().'</b></a> di '.$document->getAuthor().'</div>';
			echo '</div>'."\n";
		}
		echo '</div>'."\n";
	} else {
		// non ci sono documenti
		echo '<div id="empty">Nessun documento in questa categoria</div>'."\n";
	}
	?>
</fieldset>
<?php 
} // fine else isset error_message

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>