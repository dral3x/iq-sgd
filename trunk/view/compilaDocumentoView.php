<?php 
// aggiungo header
$page_title = "Compila documento (2 di 2)";
$page_subtitle = "Compila i campi";
include (dirname(__FILE__) . '/headerView.php');
?>
<h1><?php echo $page_title; ?></h1> 
<?php 
if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}

// se la variabile $document  stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($model)) {
?>
<div id="fillDocument">
<fieldset>
	<legend>Documento da compilare</legend>
	<form name="CompilazioneDocumento" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($model->getFields() as $field) {
		echo '<div id="field">'.$field->getName();
		if (!$field->isOptional()) echo "*";
		echo '<br />'."\n";
		if ($field->getType() == DocumentField::SMALL) {
			echo '<input type="text" name="'.$field->getID().'" id="field_content" maxlength="30" size="50" />'."\n";
		} else if ($field->getType() == DocumentField::MEDIUM) {
			echo '<input type="text" name="'.$field->getID().'" id="field_content" maxlength="255" size="100" />'."\n";
		} else if ($field->getType() == DocumentField::LONG) {
			echo '<textarea name="'.$field->getID().'" id="field_content" rows="5" cols="72"></textarea>'."\n";
		}
		echo '</div>'."\n";
	}
	?>
	<input type="hidden" name="model_id" value="<?php echo $model->getID(); ?>" />
	<input type="submit" name="submit" value="Salva come bozza"> <input type="submit" name="submit" value="Invia all'approvatore">
	</form>
</fieldset>
</div>
<?php
// isset model 
} else if (isset($document)) {
?>
<div id="fillDocument">
<fieldset>
	<legend>Documento da compilare</legend>
	<form name="CompilazioneDocumento" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field">'.$field->getName();
		if (!$field->isOptional()) echo "*";
		echo '<br />'."\n";
		if ($field->getType() == DocumentField::SMALL) {
			echo '<input type="text" name="'.$field->getID().'" id="field_content" maxlength="30" size="50" value="'.$field->getContent().'" />'."\n";
		} else if ($field->getType() == DocumentField::MEDIUM) {
			echo '<input type="text" name="'.$field->getID().'" id="field_content" maxlength="255" size="100" value="'.$field->getContent().'"/>'."\n";
		} else if ($field->getType() == DocumentField::LONG) {
			echo '<textarea name="'.$field->getID().'" id="field_content" rows="5" cols="72">'.$field->getContent().'</textarea>'."\n";
		}
		echo '</div>'."\n";
	}
	?>
	<input type="hidden" name="model_id" value="<?php echo $document->getModelID(); ?>" />
	<input type="submit" name="submit" value="Salva come bozza"> <input type="submit" name="submit" value="Invia all'approvatore">
	</form>
</fieldset>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>