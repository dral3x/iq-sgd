<?php 
require_once (dirname(__FILE__) . '/..//classes/document.php');
$_ls = new LoginSession();
?>
<div id="menu">
<ul>
  <li><h2>Ricerca</h2>
    <ul>
      <li><a href="ricerca.php?type=simple" title="Simple search">Ricerca semplice</a></li>
      <li><a href="ricerca.php?type=advanced" title="Advanced search">Ricerca avanzata</a></li>
    </ul>
  </li>
</ul>

<ul>
	<li><h2>Azioni</h2>
		<ul>
			<li><a href="compila.php" title="Create new document">Crea un nuovo documento</a>
			<?php 
			if  (strpos($_SERVER['PHP_SELF'], "visualizza.php") && isset($_REQUEST['document_id']) && !isset($_GET['type'])) {
			?>
				<ul>
					<li><a href="compila.php" title="Create new document">Crea da zero</a></li>
					<li><a href="compila.php?new_revision_for_document=<?php echo $_REQUEST['document_id']; ?>" title="New revision of this document">Crea nuova revisione</a></li>
				</ul>
			</li>
			<?php 
				// posso modificare il documento?
				$_d = new Document($_REQUEST['document_id']);
				if ($_d->canBeEditedBy($_ls->getUser())) {
			?>
			<li><a href="modifica.php?document_id=<?php echo $_REQUEST['document_id']; ?>" title="Edit document">Modifica documento</a></li>
			<?php 
				}
			?>
			<li><a href="#" title="Print this document">Stampa documento</a></li>
			<?php
				// azioni da approvatore
				if ($_d->getState() == DocumentState::DA_APPROVARE && $_d->getApprover()->equals($_ls->getUser())) {
					?>
					<li><a href="#" title="Approve this document">Approva/Rigetta documento</a>
						<ul>
							<li><a href="#" title="Approve this document">Approva il documento</a></li>
							<li><a href="#" title="Reject this document">Rigetta il documento</a></li>
						</ul>
					</li>
					<?php
				}
				unset($_d);
			} else {
				echo "</li>\n";
			}
			
			if  (strpos($_SERVER['PHP_SELF'], "compila.php") && isset($document_id) && !isset($_GET['model_id'])) {
				// posso modificare il documento almeno?
				$_d = new Document($document_id);
				if ($_d->canBeEditedBy($_ls->getUser())) {
					?>
					<li><a href="modifica.php?document_id=<?php echo $document_id; ?>" title="Edit document">Modifica documento</a></li>
					<?php	
				}
				unset($_d);
			}
			
			if (strpos($_SERVER['PHP_SELF'], "modifica.php") && isset($_REQUEST['document_id'])) {
				// posso modificare il documento almeno?
				$_d = new Document($_REQUEST['document_id']);
				if ($_d->canBeEditedBy($_ls->getUser())) {
					?>
					<li><a href="visualizza.php?document_id=<?php echo $_REQUEST['document_id']; ?>" title="Back to view document">Annulla modifica</a></li>
					<?php
				}
				unset($_d);
			}
			?>

		</ul>
	</li>
</ul>

<ul>
  <li><h2>Visualizza</h2>
    <ul>
    	<?php
		if (strpos($_SERVER['PHP_SELF'], "visualizza.php") && isset($_REQUEST['document_id'])) {
		?>
		<li><a href="visualizza.php?type=revisions&document_id=<?php echo $_GET['document_id']; ?>" title="View all revisions">Mostra tutte le revisioni</a></li>
		<?php
		}
		
		if ($_ls->getUser()->user_id == 1) { // sono l'amministratore di sistema
		?>
		<li><a href="visualizza.php?type=all" title="View all documents">Mostra tutti i documenti</a></li>
		<?php
		}
		?>
      <li><a href="visualizza.php?type=draft_documents" title="View document">Bozze di documento</a></li>
      <li><a href="visualizza.php?type=waiting_approval_documents" title="Waiting approval documents">Documenti in attesa di approvazione</a></li>
    </ul>
  </li>
</ul>

<ul>
  <li><h2>Help</h2>
  	<ul>
  		<?php  
  		$_anchor = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/")+1, strpos($_SERVER['PHP_SELF'], ".php")-4);
  		?>
      <li><a href="help.html#<?php echo $_anchor ?>" target="_blank" title="Help">Help online</a></li>
      <?php 
      	unset($_anchor);
      ?>
      <li><a href="sgd_user_manual.php" target="_blank" title="User manual">Manuale d'uso</a></li>
  	</ul>
  </li>
</ul>

<ul>
  <li><h2>Profilo utente</h2>
  	<ul>
  	  <li><a href="#" title="Profile page">Profilo di <?php echo htmlentities($_ls->getUser()->getDisplayName()); ?></a></li>
      <li><a href="login.php?action=logout" title="Logout user">Logout</a></li>
  	</ul>
  </li>
</ul>		
</div>
<?php 

unset($_ls);

?>