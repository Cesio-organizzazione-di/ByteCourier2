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
		
		<div class = "menu">
			<a href = "home_adm.php"> <img src = "immagini/logo_blu.png" alt = "logo" title = "Home" /> </a>
			<a href = "tipologie.php"> Gestisci tipologie di spedizione</a>
			<p> Gestisci utenti </p>
			<a href = "inserisci.php"> Inserisci personale </a>
		</div>
		<div class = "titolo">
			<h1> Gestisci utenti </h1>
		</div>
		
		<div class="filtri">
			<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
				<input class="filtro" type = "submit" name = "invio" value = "Tutti gli utenti" />
				<input class="filtro" type = "submit" name = "invio" value = "Clienti" />
				<input class="filtro" type = "submit" name = "invio" value = "ByteCouriers" />
				<input class="filtro" type = "submit" name = "invio" value = "Gestori" />
			</form>
		</div>
		
		<div class = "utenti">
			<?php 
				stampa_utenti();
			?>
		</div>
	</body>
</html>

<?php
	function stampa_utenti() {
		require("./connessione.php");
		$sql = "SELECT * FROM $user_table_name WHERE tipo_utente <> \"adm\"";
			
			if(!$resultQ = mysqli_query($connection, $sql)) {
					echo "<p> Si &egrave; verificato un errore </p>";
					exit();
			}
		$profilo = "";
		if(empty($_POST['invio']) || $_POST['invio'] == "Tutti gli utenti"){
			echo "<h1> Tutti gli utenti </h1>";
			
			while($row = mysqli_fetch_array($resultQ)) {
				switch($row['tipo_utente']) {
					case "cli_p": 
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"utente\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else $profilo.= "<div class = \"utente\">";
						if($row['avatar']) $profilo.= "<img src = \"immagini/".$row['avatar'].".png\" alt = \"avatar\" />\n";
						else $profilo.= "<img src = \"immagini/avatar.png\" alt = \"avatar\" style = \"width: 65px;\"/>\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome']." ".$row['cognome']."</em></div>\n";
						$profilo.= "<div class = \"campo\"><strong>Tipologia utente: </strong>Cliente privato</div>\n";
						$profilo.= "<form action = \"cliente.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n\n";
					break;
					
					case "cli_az": 
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"utente\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else $profilo.= "<div class = \"utente\">";
						if($row['avatar']) $profilo.= "<img src = \"immagini/".$row['avatar'].".png\" alt = \"avatar\" />\n";
						else $profilo.= "<img src = \"immagini/factory.png\" alt = \"avatar\" style = \"width: 65px;\"/>\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome_attivita']."</em></div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>Cliente aziendale</div>\n";
						$profilo.= "<form action = \"cliente.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n\n";
					break;
					
					case "bc": 
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"courier\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else {
							if($row['stato'] == "free") $profilo.= "<div class = \"courier\" style = \"background-color: rgba(50, 205, 50,0.8)\">\n";
							else $profilo.= "<div class = \"courier\" style = \"background-color: rgba(178, 34, 34,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>".$row['stato']."\n</div>\n";
						}
						
						$profilo.= "<img src = \"immagini/courier.png\" alt = \"avatar\" />\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome']." ".$row['cognome']."</em></div>\n";
						if($row['valutazione']) 
							$profilo.= "<div class = \"campo\"><strong>Valutazione: </strong>".$row['valutazione']."</div>\n";
						else 
							$profilo.= "<div class = \"campo\"><strong>Valutazione: </strong> -- </div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>ByteCourier</div>\n";
						$profilo.= "<form action = \"personale.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n\n";
					break;
					
					case "ges":
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"gestore\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else $profilo.= "<div class = \"gestore\">";					
						$profilo.= "<img src = \"immagini/manager.png\" alt = \"avatar\" />\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome']." ".$row['cognome']."</em></div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>Gestore</div>\n";
						$profilo.= "<form action = \"personale.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n\n";
					break;
				}
			}
			echo $profilo;
		}
		
		else if($_POST['invio'] == "Clienti") {
			echo "<h1> Clienti </h1>";
			
			while($row = mysqli_fetch_array($resultQ)) {
				switch($row['tipo_utente']) {
					case "cli_p": 
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"utente\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else $profilo.= "<div class = \"utente\">";
						if($row['avatar']) {
							$profilo.= "<img src = \"immagini/".$row['avatar'].".png\" alt = \"avatar\" />\n";
						}
						else $profilo.= "<img src = \"immagini/avatar.png\" alt = \"avatar\" style = \"width: 65px;\"/>\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome']." ".$row['cognome']."</em></div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>Cliente privato</div>\n";
						$profilo.= "<form action = \"cliente.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n\n";
					break;
					
					case "cli_az": 
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"utente\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else $profilo.= "<div class = \"utente\">";
						if($row['avatar']) {
							$profilo.= "<img src = \"immagini/".$row['avatar'].".png\" alt = \"avatar\" />\n";
						}
						else $profilo.= "<img src = \"immagini/factory.png\" alt = \"avatar\" style = \"width: 65px;\"/>\n";
						$profilo.= "<div class = \"info\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome_attivita']."</em></div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>Cliente aziendale</div>\n";
						$profilo.= "<form action = \"cliente.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n\n";
					break;
				}
			}
			echo $profilo;
		}
		else if($_POST['invio'] == "ByteCouriers") {
			echo "<h1> ByteCouriers </h1>";
			
			while($row = mysqli_fetch_array($resultQ)) {
				if($row['tipo_utente'] == "bc") {
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"courier\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disalibitato</div>\n";
						}
						else {
							if($row['stato'] == "free") $profilo.= "<div class = \"courier\" style = \"background-color: rgba(50, 205, 50,0.8)\">\n";
							else $profilo.= "<div class = \"courier\" style = \"background-color: rgba(178, 34, 34,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>".$row['stato']."\n</div>\n";
						}
						$profilo.= "<img src = \"immagini/courier.png\" alt = \"avatar\" />\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome']." ".$row['cognome']."</em></div>\n";
						if($row['valutazione'])
							$profilo.= "<div class = \"campo\"><strong>Valutazione: </strong>".$row['valutazione']."</div>\n";
						else 
							$profilo.= "<div class = \"campo\"><strong>Valutazione: </strong> -- </div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>ByteCourier</div>\n";
						$profilo.= "<form action = \"personale.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n";
				}
			}
			echo $profilo;
		}
		
		else if($_POST['invio'] == "Gestori") {
			echo "<h1> Gestori </h1>";
			
			while($row = mysqli_fetch_array($resultQ)) {
				if($row['tipo_utente'] == "ges") {
						if($row['abilitato'] == 0) {
							$profilo.= "<div class = \"gestore\" style = \"background-color: rgba(192,192,192,0.8)\">\n";
							$profilo.= "<div class = \"stato\"><strong>Stato: </strong>disabilitato</div>\n";
						}
						else $profilo.= "<div class = \"gestore\">";
						$profilo.= "<img src = \"immagini/manager.png\" alt = \"avatar\" />\n";
						$profilo.= "<div class = \"user\">\n";
						$profilo.= "<strong>Username: </strong>".$row['username'];
						$profilo.= "\n</div>\n";
						$profilo.= "<div class = \"campo\"><em>".$row['nome']." ".$row['cognome']."</em></div>\n";
						$profilo.= "<div class = \"tipo\"><strong>Tipologia utente: </strong>Gestore</div>\n";
						$profilo.= "<form action = \"personale.php\" method = \"post\">\n";
						$profilo.= "<input class = \"analizza\" type = \"submit\" name = \"invio\" value = \"".$row['username']."\" title = \"Analizza\" />\n";
						$profilo.= "</form>\n";
						$profilo.= "</div>\n";
				}
			}
			echo $profilo;
		}
	}
?>