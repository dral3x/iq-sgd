<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="view/stile.css">
</head>
<body>
<!-- Menu -->
<?php
if (isset($hide_menu) && $hide_menu) {
	// non si vuole visualizzare il menu
} else {
	include (dirname(__FILE__) . '/menuView.php');
}
?>
<!-- Fine menu -->