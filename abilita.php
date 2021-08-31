<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Gestisci utenti - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_adm.css" />
	</head>
	<body class = "admin">
		<div class = "logout">
			<a href = "logout_team.php"><img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu" id = "home">
			<a href = "home_adm.php"> <img src = "immagini/logo_blu.png" alt = "logo" title = "Home" /> </a>
			<a href = "tipologie.php"> Gestisci tipologie di spedizione</a>
			<a href = "utenti.php"> Gestisci utenti </a>
			<a href = "inserisci.php"> Inserisci personale </a>
		</div>
		<?php 
			if(isset($_POST['abilita'])) {
				abilita($_POST['abilita']);
			}
			else if(isset($_POST['disabilita'])) {
				disabilita($_POST['disabilita']);
			}
		?>
		<p><a class = "indietro" href = "utenti.php"> Torna indietro </a></p>
	</body>
</html>

<?php
	function abilita($username) {
		require("./connessione.php");
		
		$sql = "UPDATE $user_table_name
				SET abilitato = \"1\"
				WHERE username = '{$username}'
				";
				
		if(!$resultQ = mysqli_query($connection, $sql)){
			printf("<p>Si è verificato un errore!</p>");
			exit();
		}
		echo "<h1 class = \"titolo\">Utente riabilitato correttamente</h2>";
	}
	
	function disabilita($username) {
		require("./connessione.php");
		
		$sql = "UPDATE $user_table_name
				SET abilitato = \"0\"
				WHERE username = '{$username}'
				";
				
		if(!$resultQ = mysqli_query($connection, $sql)){
			printf("<p>Si è verificato un errore!</p>");
			exit();
		}
		echo "<h1 class = \"titolo\">Utente disabilitato correttamente</h2>";
	}
?>