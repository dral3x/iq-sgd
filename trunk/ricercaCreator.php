<?php

require_once (dirname(__FILE__) . '/classes/ricercaClass.php');

$ricerca = new Ricerca();

//controlli da inserire

// chiamata del metodo $ricerca->dbSearch();

// form di ricerca
require ('view/ricercaView.php');

?>
