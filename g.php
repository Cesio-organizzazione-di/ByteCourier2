<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	$msg = "";

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');
	
	require("./connessione.php");

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title>Aggiungi gestore - ByteCourier2 </title>
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
			<a href = "inserisci.php"> Inserisci personale </a>
		</div>
		<div class = "titolo">
			<h1>Inserisci un nuovo gestore</h1>
		</div>
		<?php
			
			if(isset($_POST['invio'])) {
				if(isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && ($_POST['data_nascita']) && isset($_POST['via']) && isset($_POST['n_civico']) && isset($_POST['citta']) && isset($_POST['CAP']) && ($_POST['telefono']) && isset($_POST['sesso'])) {
					$sql = "INSERT INTO $user_table_name 
							(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email)
							VALUES
							('{$_POST['username']}', '{$_POST['password']}', \"ges\", '{$_POST['nome']}','{$_POST['cognome']}',
							'{$_POST['data_nascita']}', '{$_POST['via']}', '{$_POST['n_civico']}',
							'{$_POST['citta']}', '{$_POST['CAP']}','{$_POST['sesso']}','{$_POST['telefono']}','{$_POST['email']}')
							";
								 
					//CONTROLLO QUERY
					if(!($resultQ = mysqli_query($connection, $sql))) {
						printf("Si è verificato un problema. Impossibile registrarsi.\n");
						exit();
					}	
					echo "<div class=\"titolo\"><em> Registrazione avvenuta con successo!</em></div>";
				}
				
				else echo "<div class=\"titolo\"><em>Completa tutti i campi per continuare </em></div>";	
			}
			
			//CHIUSURA CONNESSIONE
			$connection->close();		
		?>
		
		<div class = "corpo">
			<p>
				<em>Tutti i campi sono obbligatori</em>
			</p>
			<hr  />
			<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "POST">
				<table class = "table" align = "center">
      
					<tr><td class = "info">Nome:</td>
						<td><input type = "text" name = "nome" size = "30" <?php if(isset($_POST['nome'])) echo "value = \"{$_POST['nome']}\""; ?>></td>
					</tr>
     
					<tr> <td class = "info">Cognome: </td>
						<td><input type = "text" name = "cognome" size = "30" <?php if(isset($_POST['cognome'])) echo "value = \"{$_POST['cognome']}\""; ?>></td>
					</tr>
    
					<tr> <td class = "info"> Data di nascita: </td>
						<td><input type = "date" name = "data_nascita"></td>
					</tr>
     
					<tr>
						<td class = "info">Sesso:</td>
						<td>
						<input class = "selezione" type = "radio" name = "sesso" value = "M"> M 
						<input class = "selezione" type = "radio" name = "sesso" value = "F"> F 
						</td>
					</tr>
     
					<tr> <td class = "info"> Via: </td>
						<td><input type = "text" name = "via" size = "30" <?php if(isset($_POST['via'])) echo "value = \"{$_POST['via']}\""; ?>></td>
					</tr>
     
					<tr> <td class = "info"> Numero civico: </td>
						<td><input type = "text" name = "n_civico" size = "30" <?php if(isset($_POST['n_civico'])) echo "value = \"{$_POST['n_civico']}\""; ?>></td>
					</tr>
     
					<tr> <td class = "info"> Citt&agrave;: </td>
						<td><input type = "text" name = "citta" size = "30" <?php if(isset($_POST['citta'])) echo "value = \"{$_POST['citta']}\""; ?>></td>
					</tr>
     
					<tr> <td class = "info"> CAP: </td>
						<td><input type = "text" name = "CAP" size = "30" <?php if(isset($_POST['CAP'])) echo "value = \"{$_POST['CAP']}\""; ?>></td>
					</tr>
     
					<tr> <td class = "info"> Telefono: </td>
						<td><input type = "text" name = "telefono" size = "30" <?php if(isset($_POST['telefono'])) echo "value = \"{$_POST['telefono']}\""; ?>></td>
					</tr>
     
					<tr> <td class = "info">Username:</td>
						<?php $user = username(); ?>
					<td><input type = "hidden" name = "username" value = "<?php echo $user ?>"><?php echo $user ?></td>
      
					</tr>
     
					<tr> <td class = "info">Password:</td>
						<td><input type = "password" name = "password" size = "30"></td>
					</tr>
     
					<tr><td class = "info"> E-mail:</td>
						<td><input type = "text" name = "email" size = "30" <?php if(isset($_POST['email'])) echo "value = \"{$_POST['email']}\""; ?>></td>
					</tr>
				</table>
				<hr />
				<div class = "bottoni">
					<p>
						<input class = "bottone" type = "submit" name = "invio" value = "Registrati">
					</p>
				</div>
			</form>
		</div>
		
		<p><a class = "indietro" href = "inserisci.php"> Torna indietro </a></p>
	</body>
</html>

<?php
	function username(){
		require("./connessione.php");
		$sql = "SELECT COUNT(*)
				FROM $user_table_name 
				WHERE tipo_utente = \"ges\"
				";
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p> Si &egrave; verificato un errore </p>";
			exit();
		}
		
		$count = mysqli_fetch_array($resultQ);
		$count[0]++;
		
		if($count[0] < 9) {
			$username = "g0".$count[0];
		}
		else $username = "g".$count[0];
	
	return $username;
	}
?>