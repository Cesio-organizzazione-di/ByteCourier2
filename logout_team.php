<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	unset($_SESSION);
	session_destroy();

?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title>Logout - ByteCourier2</title>
		<link rel = "stylesheet" type = "text/css" href = "stile_team.css" />
	</head>

	<body class="team">
		<div class = "logo">
			<img src = "immagini/logo_white.png" alt = "logo" />
		</div>
		
		<div class="titolo">
			<h2> Hai effettuato il Logout </h2>
		</div>
		
		
		<div class="back">
				<a href="login_team.php">Torna al login</a>
				<a href = "index.php">Torna alla pagina iniziale</a>
		</div>
	</body>
</html>