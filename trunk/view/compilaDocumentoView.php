<?php 
// aggiungo header
$page_title = "Compila documento (2 di 2)";
$page_subtitle = "Compila i campi";
include (dirname(__FILE__) . '/headerView.php');

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

// se la variabile $document è stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($document)) {
?>
<div id="fillDocument">
<fieldset>
	<legend>Documento da compilare</legend>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getFields() as $field => $content) {
		echo '<div id="field">'.$field.'</div>'."\n";
		echo '<textarea id="field_content">'.$content.'</textarea>'."\n";
	}
	?>
</fieldset>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>