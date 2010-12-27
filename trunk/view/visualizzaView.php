<?php 
// aggiungo header
$page_title = "Visualizza documento";
include (dirname(__FILE__) . '/headerView.php');

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

// se la variabile $document � stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($document)) {
?>
<fieldset>
	<legend>Documento Visualizzato</legend>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getFields() as $field => $content) {
		echo '<div id="field">'.$field.'</div>'."\n";
		echo '<textarea id="field_content">'.$content.'</textarea>'."\n";
	}
	?>
</fieldset>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>