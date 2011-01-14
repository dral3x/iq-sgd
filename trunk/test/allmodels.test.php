<?php
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

$models_test = new TestSuite('Test sul modello');
$models_test->addFile('models/user.test.php');
$models_test->addFile('models/model.test.php');
$models_test->run(new HtmlReporter());

?>