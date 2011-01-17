<?php 
// aggiungo header
$page_title = "Compila documento (2 di 2)";
$page_subtitle = "Compila i campi";
include (dirname(__FILE__) . '/headerView.php');
?>
<h1><?php echo $page_title; ?></h1> 
<?php 
if (isset($error_message)) {
	echo '<div id="error">';
	echo '<fieldset><legend>Messaggio:</legend>';
	echo '<div style="margin: 2em;">';
	echo $error_message;
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
}

if (isset($highlight_message)) {
	echo '<div id="highlight">';
	echo '<fieldset><legend>Messaggio:</legend>';
	echo '<div style="margin: 2em;">';
	echo $highlight_message;
	echo '</div>';
	echo '</fieldset>';
	echo '</div>';
	echo '<br/>';
}


// se la variabile $document  stata impostata dal modello... allora posso mostrarne il contenuto
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
		<div id="field"><b>Revisione: </b> 
			<input type="text" name="revisione" id="field_content" maxlength="5" size="3" value="1.0" /><br />
		</div>
		<div id="field"><b>Lingua: </b>
			<input type="radio" name="lingua" value="it" id="field_content" checked /> Italiano | 
			<input type="radio" name="lingua" value="en" id="field_content" disabled /> English |
			<input type="radio" name="lingua" value="de" id="field_content" disabled /> Deutsch<br />
		</div>
		<div id="field"><b>Stato: </b> Bozza<br /></div>
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
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" checked /> '.htmlentities($autore->getDisplayName());
			} else {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" /> '.htmlentities($autore->getDisplayName());
			}
		}
		?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			echo '<input type="radio" name="approvatore" id="field_content" value="'.$autore->user_id.'" /> '.htmlentities($autore->getDisplayName()) . ' ';
		}
		?><br /></div>
	</fieldset>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($model->getFields() as $field) {
		echo '<div id="field"><b>'.htmlentities($field->getName());
		
		// con scrittina a fianco del nome
		//if ($field->isOptional()) 
		//	echo " (opzionale)";
		//else 
		//	echo " (obbligatorio)";
			
		// con * sui campi obbligatori
		if (!$field->isOptional()) echo "&#42;";
		
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
	oppure <a href="compila.php">annulla compilazione</a>.
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
		echo '<input type="text" name="creation_day" id="field_content" maxlength="2" size="3" value="'.$document->getCreationDay().'" />'."/\n";
		echo '<input type="text" name="creation_month" id="field_content" maxlength="2" size="3" value="'.$document->getCreationMonth().'" />'."/\n";
		echo '<input type="text" name="creation_year" id="field_content" maxlength="4" size="5" value="'.$document->getCreationYear().'" />'."\n";
		?><br /></div>
		<div id="field"><b>ID.Doc: </b> (sar&agrave; generato durante il salvataggio)<br /></div>
		<div id="field"><b>Revisione: </b> 
			<input type="text" name="revisione" id="field_content" maxlength="5" size="3" value="<?php echo $document->getRevision(); ?>" /><br />
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
			<input type="radio" name="liv_conf" value="0" id="field_content" /> L0 | 
			<input type="radio" name="liv_conf" value="1" id="field_content" /> L1 |
			<input type="radio" name="liv_conf" value="2" id="field_content" checked /> L2 |
			<input type="radio" name="liv_conf" value="3" id="field_content" /> Pubblico<br />
		</div>
		<div id="field"><b>Autori: </b> <?php
		$tutti_gli_utenti = $compila->getAllPossibleAuthors();
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			if ($autore->is_in($document->getAuthors())) {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" checked /> '.htmlentities($autore->getDisplayName());
			} else {
				echo '<input type="checkbox" name="autore_'.$autore->user_id.'" id="field_content" /> '.htmlentities($autore->getDisplayName());
			}
		}
		?><br /></div>
		<div id="field"><b>Approvatore: </b> <?php
		foreach ($tutti_gli_utenti as $autore) {
			echo "<br />\n";
			if ($autore->equals($document->getApprover())) {
				echo '<input type="radio" name="approvatore" value="'.$autore->user_id.'" id="field_content" checked /> '.htmlentities($autore->getDisplayName()) . ' ';
			} else {
				echo '<input type="radio" name="approvatore" value="'.$autore->user_id.'" id="field_content" /> '.htmlentities($autore->getDisplayName()) . ' ';
			}
		}
		?><br /></div>
	</fieldset>
	<?php
	// mostro l'elenco di tutti i campi
	foreach ($document->getContent() as $field) {
		echo '<div id="field"><b>'.htmlentities($field->getName());
		
		// con scrittina a fianco del nome
		//if ($field->isOptional()) 
		//	echo " (opzionale)";
		//else 
		//	echo " (obbligatorio)";
			
		// con * sui campi obbligatori
		if (!$field->isOptional()) echo "&#42;";
		
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
	<input type="hidden" name="document_id" value="<?php echo $document->getID(); ?>" />
	<input type="hidden" name="model_id" value="<?php echo $document->getModelID(); ?>" />
	<input type="hidden" name="progressive" value="<?php if (isset($old_progressive)) { echo $old_progressive; } ?>" />
	<input type="submit" name="submit" value="Salva come bozza"> <input type="submit" name="submit" value="Invia all'approvatore">
	oppure <a href="visualizza.php?document_id=<?php echo $document->getID(); ?>">visualizza il documento salvato</a>.
	</form>
</fieldset>
</div>
<?php 
} // isset document

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>