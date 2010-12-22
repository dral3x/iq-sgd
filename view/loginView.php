<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login</title>
</head>
<body>
<?php
echo $login->getHeader();

if (isset($error_message)) {
	echo '<p>' . $error_message . '</p>';
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
<?php echo $login->getFooter(); ?>
</body>
</html>