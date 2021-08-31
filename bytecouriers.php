<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';

	$occorrenza = 0;
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Bytecouriers - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		<div id="home" class = "menu">
			<a href = "home_ges.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "richieste.php"> Visualizza richieste di spedizione </a>
			<a href = "spedizioni.php"> Monitora spedizioni </a>
			<p> ByteCouriers </p>
			<a href = "prezzi.php"> Assegna prezzi </a>
		</div>

		<h1 class = "titolo">ByteCouriers </h1>
		
		<div class = "corrieri">
			<?php 
				 corrieri();
			?>
		</div>
		
		
		<p class="back"><a href="#home"><img src="immagini/up-arrow.png" class="tornasu" title="Torna su" /></a></p>
	</body>
</html>

<?php 
	function corrieri() {
		require("./connessione.php");
		$sql = "SELECT * FROM $user_table_name WHERE tipo_utente = \"bc\" ORDER BY valutazione DESC";
		
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p><em>Errore durante l'accesso al database</p>";
			exit();
		}
		
		$bc = "";
		while($row = mysqli_fetch_array($resultQ)){
			if($row['abilitato'] == 0) {
				$bc.="<div class=\"corr\" style=\"background-color: silver; color: black;\">";
				$bc.="<div class=\"stato\">disabilitato";
			}
			else {
				if($row['stato'] == "busy") {
					$bc.="<div class=\"corr\" style=\"background-color: firebrick; color: white;\">";
					$bc.="<div class=\"stato\">".$row['stato'];
				}
				else {
					$bc.="<div class=\"corr\" style=\"background-color: lightgreen; color: black;\">";
					$bc.="<div class=\"stato\">".$row['stato'];
				}
			}
			
			$bc.="</div><p><strong>Username: </strong>".$row['username']."</p>";
			if($row['valutazione'])
				$bc.="<p><strong>Valutazione: </strong>".$row['valutazione']."</p>";
			else 
				$bc.="<p><strong>Valutazione: </strong> -- </p>";
			$bc.="<p><strong>Nome: </strong>".$row['nome']."</p>";
			$bc.="<p><strong>Cognome: </strong>".$row['cognome']."</p>";
			$bc.="<form action=\"corriere.php\" method=\"post\">";
			$bc.="<input class = \"analizza\" type=\"submit\" name = \"scelta\" value=\"".$row['username']."\" title = \"Analizza\"/>";
			$bc.="</form>";
			$bc.="</div>";
		}
		
		echo $bc;
	}
?>