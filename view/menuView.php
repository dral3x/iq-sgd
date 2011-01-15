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
			<?php
			if  (strpos($_SERVER['PHP_SELF'], "visualizza.php") && isset($_REQUEST['document_id']) && !isset($_GET['type'])) {
				
			?>
			<li><a href="modifica.php?document_id=<?php echo $_REQUEST['document_id']; ?>" title="Edit document">Modifica documento</a></li>
			<li><a href="#" title="New revision of this document">Crea nuova revisione</a></li>
			<li><a href="#" title="Print this document">Stampa documento</a></li>
			<?php
			}
			if  (strpos($_SERVER['PHP_SELF'], "compila.php") && isset($document_id) && !isset($_GET['model_id'])) {
			?>
			<li><a href="modifica.php?document_id=<?php echo $document_id; ?>" title="Edit document">Modifica documento</a></li>
			<?php	
			}
			if (strpos($_SERVER['PHP_SELF'], "modifica.php") && isset($_REQUEST['document_id'])) {
			?>
			<li><a href="visualizza.php?document_id=<?php echo $_REQUEST['document_id']; ?>" title="Back to view document">Annulla modifica</a></li>
			<?php
			}
			?>
			<li><a href="compila.php" title="Create new document">Crea un nuovo documento</a></li>
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
		?>
      <li><a href="visualizza.php?type=draft_documents" title="View document">Bozze di documento</a></li>
      <li><a href="visualizza.php?type=waiting_approval_documents" title="Waiting approval documents">Documenti in attesa di approvazione</a></li>
    </ul>
  </li>
</ul>

<ul>
  <li><h2>Help</h2>
  	<ul>
      <li><a href="help.php" target="_blank" title="Help">Help online</a></li>
      <li><a href="help.php?type=user_manual" title="Help">Manuale d'uso</a></li>
  	</ul>
  </li>
</ul>

<ul>
  <li><h2>Profilo utente</h2>
  	<ul>
  	  <li><a href="#" title="Profile page">Profilo di <?php $_ls = new LoginSession(); echo $_ls->getUser()->getDisplayName(); ?></a></li>
      <li><a href="login.php?action=logout" title="Logout user">Logout</a></li>
  	</ul>
  </li>
</ul>		
</div>