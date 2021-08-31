<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	unset($_SESSION);
	session_destroy();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title>Logout - ByteCourier2</title>
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>

	<body class="cliente">
		<div class = "logo">
			<img class = "logo" src = "immagini/logo_large.png" alt = "ByteCourier2" /> 
		</div>
		
		<div class="titolo">
			<h2> Hai effettuato il Logout </h2>
			<h3> Grazie per aver scelto ByteCourier2, a presto!</h3>
		</div>

			<p class="back">
				<a href="login_cli.php">Torna al login</a>
				<a href = "index.php">Torna alla pagina iniziale</a>
			</p>
	</body>
</html>