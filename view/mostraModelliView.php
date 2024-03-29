<?php
// aggiungo header
$page_title = "Compila documento (1 di 2)";
$page_subtitle = "Scegli il modello da compilare";

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
<div id="showModel">
<fieldset style="margin: 0px">
	<legend><?php echo $page_subtitle; ?></legend>
	<?php
	// se ci sono documenti, li mostro in forma di elenco
	if (isset($models) && count($models)>0) {
		echo '<div id="container">'."\n";
		foreach ($models as $model) {
			echo '<div id="row">';
			echo '<div id="left"><a href="compila.php?model_id='.$model->getID().'"><b>'.htmlentities($model->getName()).'</b></a></div>';
			echo '</div>'."\n";
		}
		echo '</div>'."\n";
	} else {
		// non ci sono documenti
		echo '<div id="empty">Nessun modello disponibile</div>'."\n";
	}
	?>
</fieldset>
</div>
<?php 
} // fine else isset error_message

// aggiungo footer
include (dirname(__FILE__) . '/footerView.php');
?>