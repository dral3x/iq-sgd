<?php 
// aggiungo header
$page_title = "Modifica documento";
include (dirname(__FILE__) . '/headerView.php');

?>
<h1><?php echo $page_title; ?></h1> 
<?php 
if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

// se la variabile $document è stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($document)) {
?>
<div id="view">
<fieldset>
	<legend>Documento da modificare</legend>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field">'.$field->getName().'<br />'."\n";
		if ($field->getType() == DocumentField::SMALL) {
			echo '<input type="text" name="'.$field->getName().'" value="'.$field->getContent().'" id="field_content" maxlength="30" size="50" />'."\n";
		} else if ($field->getType() == DocumentField::MEDIUM) {
			echo '<input type="text" name="'.$field->getName().'" value="'.$field->getContent().'" id="field_content" maxlength="255" size="100" />'."\n";
		} else if ($field->getType() == DocumentField::LONG) {
			echo '<textarea name="'.$field->getName().'" id="field_content" rows="5" cols="72">'.$field->getContent().'</textarea>'."\n";
		}
		echo '</div><br />'."\n";
	}
	?>
</fieldset>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>