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

// se la variabile $document è stata impostata dal modello... allora posso mostrarne il contenuto
if (isset($model)) {
?>
<div id="fillDocument">
<fieldset>
	<legend>Documento da compilare</legend>
	<form name="CompilazioneDocumento" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="POST">
	<?php
	// mostro informationi base sul documento
	?>
	<fieldset>
		<legend>Intestazione</legend>
		<div id="field"><b>Data: </b> <?php
		echo '<input type="text" name="creation_day" id="field_content" maxlength="2" size="3" value="'.date("j").'" />'."/\n";
		echo '<input type="text" name="creation_month" id="field_content" maxlength="2" size="3" value="'.date("n").'" />'."/\n";
		echo '<input type="text" name="creation_year" id="field_content" maxlength="4" size="5" value="'.date("Y").'" />'."\n";
		?><br /></div>
		<div id="field"><b>ID.Doc: </b> (sar&agrave; generato durante il salvataggio)<br /></div>
		<div id="field"><b>Versione: </b> 
			<input type="text" name="versione" id="field_content" maxlength="2" size="3" value="1.0" /><br />
		</div>
		<div id="field"><b>Lingua: </b>
			<input type="radio" name="lingua" value="it" id="field_content" checked /> Italiano | 
			<input type="radio" name="lingua" value="en" id="field_content" /> English |
			<input type="radio" name="lingua" value="de" id="field_content" /> Deutsch<br />
		</div>
		<div id="field"><b>Stato: </b> <?php ?><br /></div>
		<div id="field"><b>Sede archiviazione: </b>
			<input type="text" name="sede" id="field_content" maxlength="30" /><br />
		</div>
		<div id="field"><b>Liv. Confidenzialit&agrave;: </b>
			<input type="radio" name="liv_conf" value="0" id="field_content" /> L0 | 
			<input type="radio" name="liv_conf" value="1" id="field_content" /> L1 |
			<input type="radio" name="liv_conf" value="2" id="field_content" checked /> L2 |
			<input type="radio" name="liv_conf" value="3" id="field_content" /> Pubblico<br />
		</div>
		<div id="field"><b>Autori: </b> <?php
		$tutti_gli_utenti = $compila->getAllPossibleAuthors();
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			if ($compila->getSessionUser()->equals($autore)) {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" checked /> '.$autore->getDisplayName();
			} else {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" /> '.$autore->getDisplayName();
			}
		}
		?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			echo '<input type="radio" name="approvatore" id="field_content" /> '.$autore->getDisplayName() . ' ';
		}
		?><br /></div>
	</fieldset>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($model->getFields() as $field) {
		echo '<div id="field"><b>'.htmlentities($field->getName());
		if (!$field->isOptional()) echo "*";
		echo '</b><br />'."\n";
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
		<fieldset>
		<legend>Intestazione</legend>
		<div id="field"><b>Data: </b> <?php
		echo '<input type="text" name="date_day" id="field_content" maxlength="2" size="3" value="'.$document->getCreationDay().'" />'."/\n";
		echo '<input type="text" name="date_month" id="field_content" maxlength="2" size="3" value="'.$document->getCreationMonth().'" />'."/\n";
		echo '<input type="text" name="date_year" id="field_content" maxlength="4" size="5" value="'.$document->getCreationYear().'" />'."\n";
		?><br /></div>
		<div id="field"><b>ID.Doc: </b> (sar&agrave; generato durante il salvataggio)<br /></div>
		<div id="field"><b>Versione: </b> 
			<input type="text" name="versione" id="field_content" maxlength="2" size="3" value="1.0" /><br />
		</div>
		<div id="field"><b>Lingua: </b>
			<input type="radio" name="lingua" value="it" id="field_content" checked /> Italiano | 
			<input type="radio" name="lingua" value="en" id="field_content" /> English |
			<input type="radio" name="lingua" value="de" id="field_content" /> Deutsch<br />
		</div>
		<div id="field"><b>Stato: </b> <?php ?><br /></div>
		<div id="field"><b>Sede archiviazione: </b>
			<input type="text" name="sede" id="field_content" maxlength="30" /><br />
		</div>
		<div id="field"><b>Liv. Confidenzialit&agrave;: </b>
			<input type="radio" name="liv_conf" value="0" id="field_content" /> L0 | 
			<input type="radio" name="liv_conf" value="1" id="field_content" /> L1 |
			<input type="radio" name="liv_conf" value="2" id="field_content" checked /> L2 |
			<input type="radio" name="liv_conf" value="3" id="field_content" /> Pubblico<br />
		</div>
		<div id="field"><b>Autori: </b> <?php
		$tutti_gli_utenti = $compila->getAllPossibleAuthors();
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			if ($compila->getSessionUser()->equals($autore)) {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" checked /> '.$autore->getDisplayName();
			} else {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" /> '.$autore->getDisplayName();
			}
		}
		?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			echo '<input type="radio" name="approvatore" id="field_content" /> '.$autore->getDisplayName() . ' ';
		}
		?><br /></div>
	</fieldset>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field"><b>'.$field->getName();
		if (!$field->isOptional()) echo "*";
		echo '</b><br />'."\n";
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