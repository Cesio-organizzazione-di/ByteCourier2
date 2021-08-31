<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	$msg = "";

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Home Admin - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_adm.css" />
	</head>
	<body class = "admin">
		<div class = "logout">
			<a href = "logout_team.php"><img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo">
			<img src = "immagini/logo_large.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "home_adm.php"> <img src = "immagini/logo_blu.png" alt = "logo" title = "Home" /> </a>
			<a href = "tipologie.php"> Gestisci tipologie di spedizione</a>
			<a href = "utenti.php"> Gestisci utenti </a>
			<p> Inserisci personale </p>
		</div>
		<div class = "titolo">
			<h1>Inserisci nuovo personale!</h1>
		</div>
		<div class = "riga">
				<a href = "g.php" class = "g">Gestore</a>
			
				<a href = "b.php" class = "b">ByteCourier</a>
		</div>
		<div class = "riga">
			<p class = "column"> Aggiungi un nuovo gestore </p>
			<p class = "column"> Aggiungi un nuovo ByteCourier</p>
		</div>
	</body>
</html>