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
		<title> Home Corriere - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_bc.css" />
	</head>
	<body class="bc">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo">
			<img src = "immagini/logo_large.png" alt = "ByteCourier2" />
			<h2> Il servizio di spedizioni e ritiri più efficiente in Italia! </h2>
		</div>
		
		<div class = "menu">
			<a href = "home_bc.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "corr_in_sospeso.php"> Spedizioni in sospeso </a>
			<a href = "corr_in_carico.php"> Spedizioni in carico </a>
			<a href = "corr_completate.php"> Spedizioni completate </a>
		</div>
		
		<div class = "titolo" style = "margin-bottom: 100px;">
			<h1> Ciao <?php echo $_SESSION['nome'] ?>! </h1>
			<h3> Il tuo profilo: </h3>
			<p> <strong>Username:</strong> <?php echo $_SESSION['username'] ?> </p>
			<p> <strong>Nome:</strong> <?php echo $_SESSION['nome'] ?> </p>
			<p> <strong>Cognome:</strong> <?php echo $_SESSION['cognome'] ?> </p>
			<p> <strong>Data di nascita:</strong> <?php echo $_SESSION['data_nascita'] ?> </p>
			<p> <strong>Email:</strong> <?php echo $_SESSION['email'] ?> </p>
			<p> <strong>Numero di telefono:</strong> <?php echo $_SESSION['telefono']; ?> </p>
		</div>
	</body>
</html>