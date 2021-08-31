<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
	require_once("./connessione.php");
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Area clienti - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>
	<body class = "cliente">
		<div class = "logout">
			<a href = "logout_cli.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "richiesta.php"> Richiedi spedizione </a>
			<a href = "ricerca.php"> Gestisci spedizione </a>
			<a href = "area_clienti.php"> Area Clienti </a>
			<a href = "home_cli.php"> <img src = "immagini/logo.png" alt = "logo" title = "Home" /> </a>
		</div>
		
		<div class = "corpo">
			<?php
				if(isset($_POST['indirizzo'])){
			?>
				<div class = "corpo_richiesta">
					<form action = "<?php $_SERVER['PHP_SELF'] ?>" method ="post">
						<h3> Inserisci il nuovo indirizzo </h3>
						<p><div class = "info"> Via: </div><div class = "input"><input type = "text" name = "via" /> </div></p>
						<p><div class = "info"> Numero civico: </div><div class = "input"><input type = "text" name = "n_civico" /> </div></p>
						<p><div class = "info"> Citt&agrave;: </div><div class = "input"><input type = "text" name = "citta" /> </div></p>
						<p><div class = "info"> CAP: </div><div class = "input"><input type = "text" name = "CAP" /> </div></p>
						<div class = "bottoni">
							<input type="submit" name = "new_indirizzo" value = "Conferma" />
							<input type="reset" value="Ripristina" />
						</div>
					</form>
				</div>
			<?php
				}
				if(isset($_POST['telefono'])){
			?>
				<div class = "corpo_richiesta">
					<form action = "<?php $_SERVER['PHP_SELF'] ?>" method ="post">
						<h3> Inserisci il nuovo numero di telefono </h3>
						<p><div class = "info"> Numero: </div><div class = "input"><input type = "text" name = "telefono1" /></div> </p>
						<div class = "bottoni">
							<input type="submit" name = "new_telefono" value = "Conferma" />
							<input type="reset" value="Ripristina" />
						</div>
					</form>
				</div>
			<?php
				}
				if(isset($_POST['new_indirizzo'])){
					if($_POST['via'] && $_POST['n_civico'] && $_POST['citta'] && $_POST['CAP']){
						$sql = "UPDATE $user_table_name
								SET via = '{$_POST['via']}',
									n_civico = '{$_POST['n_civico']}',
									citta = '{$_POST['citta']}',
									CAP = '{$_POST['CAP']}'
								WHERE username = '{$_SESSION['username']}'";
								
						if(!$resultQ = mysqli_query($connection, $sql)){
							printf("<p>Si è verificato un errore!</p>");
							exit();
						}
						echo "<h2>Indirizzo aggiornato correttamente</h2>";
					}
					else
						header('Location: area_clienti.php');
				}
				
				if(isset($_POST['new_telefono'])){
					if($_POST['telefono1']){
						$sql = "UPDATE $user_table_name
								SET telefono = '{$_POST['telefono1']}'
								WHERE username = '{$_SESSION['username']}'";
								
						if(!$resultQ = mysqli_query($connection, $sql)){
							printf("<p>Si è verificato un errore!</p>");
							exit();
						}
						echo "<h2>Numero di telefono aggiornato correttamente</h2>";
					}
					else
						header('Location: area_clienti.php');
				}
			?>
		</div>	
		<p><a class = "link" href = "area_clienti.php"> Torna indietro </a></p>
	</body>
</html>