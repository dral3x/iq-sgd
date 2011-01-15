<?php
// aggiungo header
$page_title = "Login";
include (dirname(__FILE__) . '/headerView.php');

if (isset($error_message)) {
	echo '<div id="error">' . $error_message . '</div>';
}
?>

<div class="containerBig">
	<div class="contained">
	<?php
	$arr = array();
	for( $i=0; $i<18; $i++){
		$arr[]= pow( 1.2,(1+($i/2)) );
	}
	$arrInv = array_reverse ( $arr );
	$arr2 = array();
	
	$j=0;
	$m=0;
	foreach ( $arrInv as $k ) {
		$arr2[$m]=( 80 + ( $j * (0.4) ) );
		echo '<div class="oAlign" style="width: '.$arr2[$m].'%; height: 1px; margin-bottom: '.$k.'px; background-color: gray;">&nbsp;</div>';
		$j=$j+2;
		$m++;
	}
	?>
	<!--
	<div style="width: 100%; height: 2em; margin-bottom: 4em; background-color: gray;">&nbsp;</div>
	-->
	<div id="login" style="width: 36em; margin-bottom: 4em;">
	
		<h1 class="oAlign" style="width: 3.5em;">Accedi</h1>
		<div style="margin-top: 2em; border: 4px ridge black; height: 13em; width: 35em;">
			<div class="container">
				<div class="contained" >
				<form style="margin: 1em; padding: 1em; border: 3px double gray; width: 16em; height: 8.5em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<div style="margin: 20px 0px;">Nome Utente: <input style="float: right; margin-right: 2em;" type="text" name="username" size="15" <?php if (isset($username)) echo 'value="'.$username.'"' ?>/></div>
					<div style="margin: 20px 0px;">Password: <input style="float: right; margin-right: 2em;" type="password" name="password" size="15" /></div>
					
					<span style="width: 7em; float: right; margin-right: 1.5em; text-align: center;">
					<input type="submit" value="Login" name="submit">
					</span>
				</form>
				</div>
				
				<p class="contained" style="margin-right: 2.5em;">
					<img src="logo-iqsolutions-small.png" style="width: 8.5em; height: 8.5em;" />
				</p>
			</div>
		</div>
		
	</div>
	
	<?php
	$arr2 = array_reverse ( $arr2 );
	$m = 0;
	foreach ( $arr as $k ) {
		echo '<div class="oAlign" style="width: '.$arr2[$m].'%; height: 1px; margin-top: '.$k.'px; background-color: gray;">&nbsp;</div>';
		$m++;
	}
	/*
	foreach ( $arr as $k ) {
		echo '<div class="oAlign" style="width: '.(60/$k).'%; height: 1px; margin-top: '.$k.'px; background-color: gray;">&nbsp;</div>';
	}*/
	?>
	<!-- 
	<div class="oAlign" style="width: 100%; height: 2em; margin-top: 4em; background-color: gray;">&nbsp;</div>
	 -->
	 
	</div>
</div>

<?php 
include (dirname(__FILE__) . '/footerView.php');
?>