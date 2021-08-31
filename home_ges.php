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
		<title> Home Gestore - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		<div class = "logo">
			<img src = "immagini/logo_large.png" alt = "ByteCourier2" />
			<h2> Il servizio di spedizioni e ritiri più efficiente in Italia! </h2>
		</div>
		<div class = "menu">
			<a href = "home_ges.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "richieste.php"> Richieste di spedizione </a>
			<a href = "spedizioni.php"> Monitora spedizioni </a>
			<a href = "bytecouriers.php"> ByteCouriers </a>
			<a href = "prezzi.php"> Assegna prezzi </a>
		</div>
		<div class = "titolo">
			<h1> Ciao <?php echo $_SESSION['nome'] ?>! </h1>
		</div>
		<div class = "riga">
				<a href = "richieste.php" class = "colonna1">Richieste di spedizione</a>
			
				<a href = "spedizioni.php" class = "colonna2">Monitora spedizioni</a>
		</div>
		<div class = "riga1">
			<p class = "colonna"> Visualizza l'elenco delle richieste di spedizione in sospeso</p>
			<p class = "colonna"> Monitora le richieste di spedizione attive sulla piattaforma BC2</p>
		</div>
		<div class = "riga">
				<a href = "bytecouriers.php" class = "colonna3">ByteCouriers</a>
				
				<a href = "prezzi.php" class = "colonna4">Assegna prezzi</a>
		</div>
		
		<div class = "riga1">
			<p class = "colonna"> Visualizza l'elenco dei ByteCouriers che lavorano per BC2</p>
			<p class = "colonna"> Visualizza l'elenco delle tipologie di spedizione ed assegna loro il prezzo</p>
		</div>
	</body>
</html>