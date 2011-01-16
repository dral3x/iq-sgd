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
<div id="modify">
<form name="ModificaDocumento" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
<fieldset>
	<legend>Documento da modificare</legend>
		<fieldset>
		<legend>Intestazione</legend>
		<div id="field"><b>Data: </b> <?php
		echo '<input type="text" name="creation_day" id="field_content" maxlength="2" size="3" value="'.$document->getCreationDay().'" />'."/\n";
		echo '<input type="text" name="creation_month" id="field_content" maxlength="2" size="3" value="'.$document->getCreationMonth().'" />'."/\n";
		echo '<input type="text" name="creation_year" id="field_content" maxlength="4" size="5" value="'.$document->getCreationYear().'" />'."\n";
		?><br /></div>
		<div id="field"><b>ID.Doc: </b> (generato automaticamente)<br /></div>
		<div id="field"><b>Revisione: </b> 
			<input type="text" name="revisione" id="field_content" size="3" value="<?php echo $document->getRevision(); ?>" /><br />
		</div>
		<div id="field"><b>Lingua: </b>
			<input type="radio" name="lingua" value="it" id="field_content" checked /> Italiano | 
			<input type="radio" name="lingua" value="en" id="field_content" disabled /> English |
			<input type="radio" name="lingua" value="de" id="field_content" disabled /> Deutsch<br />
		</div>
		<div id="field"><b>Stato: </b> <?php echo $document->getState(); ?><br /></div>
		<div id="field"><b>Sede archiviazione: </b>
			<input type="text" name="sede" id="field_content" maxlength="30" value="<?php echo $document->getLocation(); ?>" /><br />
		</div>
		<div id="field"><b>Liv. Confidenzialit&agrave;: </b>
			<input type="radio" name="liv_conf" value="0" id="field_content" <?php if ($document->getConfidentialLevel() == SecurityLevel::L0) echo "checked "; ?>/> L0 | 
			<input type="radio" name="liv_conf" value="1" id="field_content" <?php if ($document->getConfidentialLevel() == SecurityLevel::L1) echo "checked "; ?>/> L1 |
			<input type="radio" name="liv_conf" value="2" id="field_content" <?php if ($document->getConfidentialLevel() == SecurityLevel::L2) echo "checked "; ?>/> L2 |
			<input type="radio" name="liv_conf" value="3" id="field_content" <?php if ($document->getConfidentialLevel() == SecurityLevel::LPUBLIC) echo "checked "; ?>/> Pubblico<br />
		</div>
		<div id="field"><b>Autori: </b> <?php
		$tutti_gli_utenti = $modifica->getAllPossibleAuthors();
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			if ($autore->is_in($document->getAuthors())) {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" checked /> '.$autore->getDisplayName();
			} else {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" /> '.$autore->getDisplayName();
			}
		}
		?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			if ($autore->equals($document->getApprover())) {
				echo '<input type="radio" name="approvatore" value="'.$autore->user_id.'" id="field_content" checked /> '.$autore->getDisplayName() . ' ';
			} else {
				echo '<input type="radio" name="approvatore" value="'.$autore->user_id.'" id="field_content" /> '.$autore->getDisplayName() . ' ';
			}
			
		}
		?><br /></div>
	</fieldset>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field">'.$field->getName().'<br />'."\n";
		if ($field->getType() == DocumentField::SMALL) {
			echo '<input type="text" name="'.$field->getID().'" value="'.$field->getContent().'" id="field_content" maxlength="30" size="50" />'."\n";
		} else if ($field->getType() == DocumentField::MEDIUM) {
			echo '<input type="text" name="'.$field->getID().'" value="'.$field->getContent().'" id="field_content" maxlength="255" size="100" />'."\n";
		} else if ($field->getType() == DocumentField::LONG) {
			echo '<textarea name="'.$field->getID().'" id="field_content" rows="5" cols="72">'.$field->getContent().'</textarea>'."\n";
		}
		echo '</div><br />'."\n";
	}
	?>
	<input type="hidden" name="document_id" value="<?php echo $document->getID(); ?>" />
	<input type="submit" name="submit" value="Salva bozza"> <input type="submit" name="submit" value="Invia all'approvatore">
</fieldset>
</form>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>