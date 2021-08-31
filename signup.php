<?php 
error_reporting(E_ALL &~E_NOTICE);

require_once("./connessione.php");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Sign-Up to ByteCourier2 </title> 
		<link rel = "stylesheet" type = "text/css" href = "stile_sign.css" />
	</head>
	
	<body class = "signup">
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		<div class = "titolo">
			<h1> Crea il tuo account ByteCourier2 </h1>
			<p> Usufruisci di tutte le nostre offerte per le tue spedizioni registrandoti alla piattaforma ByteCourier2!</p>
		
			<h2> Signup </h2>
			<p> Scegli il tipo di account da creare </p>
		</div>
		
		<div class = "riga">
			<a href = "signup_cli.php" title = "Account personale" class = "colonna1"> Privato </a>
			<a href = "signup_az.php" title = "Account aziendale" class = "colonna2"> Azienda </a>
		</div>
		<p class = "p"> Hai gi&agrave; un account? <a href = "login_cli.php"> Accedi </a>
	</body>
</html>