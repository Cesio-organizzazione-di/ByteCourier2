<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Home - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>
	<body class = "cliente">
		<div class = "logout">
			<a href = "logout_cli.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo">
			<img src = "immagini/logo_large.png" alt = "ByteCourier2" />
			<h2> Il servizio di spedizioni e ritiri più efficiente in Italia! </h2>
		</div>
		
		<div class = "menu">
			<a href = "home_cli.php"> <img src = "immagini/logo.png" alt = "logo" title = "Home" /> </a>
			<a href = "richiesta.php"> Richiedi spedizione </a>
			<a href = "ricerca.php"> Gestisci spedizione </a>
			<a href = "area_clienti.php"> Area Clienti </a>
		</div>
		
		<div class = "titolo">
			<h1> Ciao <?php echo $_SESSION['username'] ?>! </h1>
		</div>
		<div class = "riga">
				<a href = "richiesta.php" class = "colonna1">Richiedi una spedizione</a>
			
				<a href = "ricerca.php" class = "colonna2">Gestisci una spedizione</a>
			
				<a href = "area_clienti.php" class = "colonna3">Area clienti</a>
		</div>
		<div class = "riga">
			<p class = "colonna"> Scegli la modalit&agrave; pi&ugrave; adatta alle tue esigenze </p>
			<p class = "colonna"> Rintraccia o modifica la tua spedizione </p>
			<p class = "colonna"> Accedi e gestisci in autonomia tutte le tue spedizioni </p>
		</div>
		
	</body>
</html>