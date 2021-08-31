<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$msg = "";
require_once("./connessione.php");

	//verichiamo se siano stati inseriti correttamente i campi username e password
	if(isset($_POST['invio'])) {
		if(empty($_POST['username']) || empty($_POST['password'])) 
			$msg = "<p class =\"p\">Dati mancanti. Riprova.</p>";
		
		else {
			//verifichiamo se i dati inseriti corrispondono a un account esistente
			$sql = "SELECT *
					FROM $user_table_name
					WHERE username = \"{$_POST['username']}\" 
					AND password = \"{$_POST['password']}\"
					AND tipo_utente = \"cli_p\"
					UNION 
					SELECT *
					FROM $user_table_name
					WHERE username = \"{$_POST['username']}\" 
					AND password = \"{$_POST['password']}\"
					AND tipo_utente = \"cli_az\"
					";
			if(!$resultQ = mysqli_query($connection, $sql)) {
				echo "<p class = \"p\">Questi dati non corrispondono a nessun account.<a href = \"login_cli.php\">Riprova</a> o <a href = \"signup.php\">registrati</a>.</p>";
				exit();
			}
		
			//se l'account esiste
			$row = mysqli_fetch_array($resultQ);
			
			if($row){ 
				if($row['abilitato'] == true) {
					if($row['tipo_utente'] == "cli_p") {
						session_start();
						$_SESSION['username'] = $_POST['username'];
						$_SESSION['nome'] = $row['nome'];
						$_SESSION['cognome'] = $row['cognome'];
						$_SESSION['data_nascita'] = $row['data_nascita'];
						$_SESSION['sesso'] = $row['sesso'];
						$_SESSION['email'] = $row['email'];
						$_SESSION['via'] = $row['via'];
						$_SESSION['n_civico'] = $row['n_civico'];
						$_SESSION['citta'] = $row['citta'];
						$_SESSION['CAP'] = $row['CAP'];
						$_SESSION['telefono'] = $row['telefono'];
						$_SESSION['avatar'] = $row['avatar'];
						$_SESSION['accessoPermesso'] = 1000;
						$_SESSION['dataLogin'] = time();
					}
					if($row['tipo_utente'] == "cli_az") {
						session_start();
						$_SESSION['username'] = $_POST['username'];
						$_SESSION['nome_attivita'] = $row['nome_attivita'];
						$_SESSION['cf'] = $row['cf'];
						$_SESSION['p_iva'] = $row['p_iva'];
						$_SESSION['settore'] = $row['settore'];
						$_SESSION['email'] = $row['email'];
						$_SESSION['via'] = $row['via'];
						$_SESSION['n_civico'] = $row['n_civico'];
						$_SESSION['citta'] = $row['citta'];
						$_SESSION['CAP'] = $row['CAP'];
						$_SESSION['telefono'] = $row['telefono'];
						$_SESSION['avatar'] = $row['avatar'];
						$_SESSION['accessoPermesso'] = 1000;
						$_SESSION['dataLogin'] = time();
					}
					header('Location: home_cli.php');
					exit();
				}else{ 
					header('Location: disabilitato.php');
					exit();
				}
			}
			else $msg = "<p class = \"p\">Questi dati non corrispondono a nessun account.<a href = \"login_cli.php\">Riprova</a> o <a href = \"signup.php\">registrati</a>.</p>";
		}
	}
?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">	
	
	<head> 
		<title> Login ByteCourier2 </title> 
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>
	
	<body class = "cliente">
		<div class = "logo">
			<img src = "immagini/logo_large.png" alt = "logo" />
		</div>
		
		<div class = "titolo">
			<p> Accedi e usufruisci di tutti i servizi offerti da ByteCourier2 per le tue spedizioni! </p>
			<h2> Login cliente </h2>
			<?php echo "<em>".$msg."</em>" ?>
		</div>
	
		<form action = "<?php $_SERVER['PHP_SELF']?>" method = "post">
			<table align="center" class = "table"> 
          <tr>
            <td>
              <img src="immagini/user.png" width="30" height="30" />
            </td>
            <td>
              <input class="text" type="text" name="username" value = "username" /> 
            </td>
          </tr>
          <tr>
            <td>
              <img src="immagini/lock.png" width="30" height="30"/> 
            </td>
            <td>
              <input class="text" type="password" name="password" value = "password" /> 
            </td>
          </tr>
        </table>
			<div class = "bottoni">
				<input type = "submit" name = "invio" value = "Accedi" />
				<input type = "reset" name = "reset" value = "Reset" />
			</div>
		</form>
		
		<p class = "p">
			Non hai ancora un account? <a href = "signup.php"> Registrati </a>
		</p>
		
		<p class="below">
			Lavori per BC2? Vai al <a href="login_team.php">login</a>
		</p>
			<a class = "link" href = "index.php">Vai alla pagina iniziale</a>
	</body>
</html>