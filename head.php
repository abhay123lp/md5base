<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>MD5 Database</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/script.js"></script>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
	<div id="navdiv">
		<div id="menu"><a href="submit.php" class="menulink">Submit</a> | 
		<?php if(isset($_SESSION['user_name']) && strlen($_SESSION['user_name']) > 0 && isset($_SESSION['user_id']) && strlen($_SESSION['user_id']) > 0) { echo '<a href="user.php?id='.$_SESSION['user_id'].'" class="menulink">Settings</a>';} else {echo	'<a href="login.php" class="menulink">Login</a>';} ?>

		| <a href="browse.php" class="menulink">Browse</a> | <a href="help.php" class="menulink">Help</a> | About | <a href="index.php" class="menulink">Home</a></div>
	</div>
	<div id="srchdiv">
			<form action="">
				<div id="srchform"><input id="searchfield" type="text" /><input id="searchbutton" type="submit" value="search" /></div>
				<div style="clear:both;"></div>
			</form>
	</div>