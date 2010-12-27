<?php
// aggiungo header
$page_title = "Login";
include (dirname(__FILE__) . '/headerView.php');

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}
?>
<h1>Accedi</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
Nome Utente: <input type="text" name="username" size="15" <?php if (isset($username)) echo 'value="'.$username.'"' ?>/><br />
Password: <input type="password" name="password" size="15" /><br />
<div align="left">
<p><input type="submit" name="submit" value="Login" /></p>
</div>
</form>
<?php 
include (dirname(__FILE__) . '/footerView.php');
?>