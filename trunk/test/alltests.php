<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

$tests = new TestSuite('All tests of SGD');
$tests->addFile('allmodels.test.php');
$tests->addFile('allpages.test.php');
//$tests->run(new HtmlReporter());

?>