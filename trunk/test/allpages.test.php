<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

$pages_test = new TestSuite('Test sulle pagine');
$pages_test->addFile('pages/index.page.test.php');
$pages_test->addFile('pages/login.page.test.php');
$pages_test->addFile('pages/ricerca.page.test.php');
$pages_test->run(new HtmlReporter());

?>