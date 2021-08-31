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
			<h1> Crea il tuo account ByteCourier2</h1>
			<p> Spedisci i tuoi pacchi in modo efficiente e veloce creando il tuo account ByteCourier2! </p>
		<?php
			
			if(isset($_POST['invio'])) {
				if(($_POST['nome']) && ($_POST['cognome']) && ($_POST['username'])
				  && ($_POST['password']) && ($_POST['email']) && ($_POST['data_nascita']) && $_POST['via'] && $_POST['n_civico']
					&& $_POST['citta'] && $_POST['CAP'] && $_POST['telefono']) {
						if(isset($_POST['sesso']) && isset($_POST['avatar'])) {
						  $sql = "INSERT INTO $user_table_name 
								 (username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email,  
								  avatar)
								 VALUES
								 ('{$_POST['username']}', '{$_POST['password']}', \"cli_p\", '{$_POST['nome']}','{$_POST['cognome']}',
								 '{$_POST['data_nascita']}', '{$_POST['via']}', '{$_POST['n_civico']}',
								 '{$_POST['citta']}', '{$_POST['CAP']}','{$_POST['sesso']}',
								 '{$_POST['telefono']}','{$_POST['email']}','{$_POST['avatar']}')
								 ";
								 
								//CONTROLLO QUERY
								if(!($resultQ = mysqli_query($connection, $sql))) {
									printf("Si è verificato un problema. Impossibile registrarsi.\n");
									exit();
								}
						}
						else if(!isset($_POST['sesso']) && isset($_POST['avatar'])) {
							 $sql = "INSERT INTO $user_table_name 
								 (username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, telefono, email,  
								  avatar)
								 VALUES
								 ('{$_POST['username']}', '{$_POST['password']}', \"cli_p\", '{$_POST['nome']}','{$_POST['cognome']}',
								 '{$_POST['data_nascita']}', '{$_POST['via']}', '{$_POST['n_civico']}',
								 '{$_POST['citta']}', '{$_POST['CAP']}','{$_POST['telefono']}','{$_POST['email']}','{$_POST['avatar']}')
								 ";
								 
								//CONTROLLO QUERY
								if(!($resultQ = mysqli_query($connection, $sql))) {
									printf("Si è verificato un problema. Impossibile registrarsi.\n");
									exit();
								}
						}
						else if(!isset($_POST['avatar']) && isset($_POST['sesso'])) {
							 $sql = "INSERT INTO $user_table_name 
								 (username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email)
								 VALUES
								 ('{$_POST['username']}', '{$_POST['password']}', \"cli_p\", '{$_POST['nome']}','{$_POST['cognome']}',
								 '{$_POST['data_nascita']}', '{$_POST['via']}', '{$_POST['n_civico']}',
								 '{$_POST['citta']}', '{$_POST['CAP']}','{$_POST['sesso']}','{$_POST['telefono']}','{$_POST['email']}')
								 ";
								 
								//CONTROLLO QUERY
								if(!($resultQ = mysqli_query($connection, $sql))) {
									printf("Si è verificato un problema. Impossibile registrarsi.\n");
									exit();
								}
						}
						else if(!isset($_POST['sesso']) && !isset($_POST['avatar'])) {
							 $sql = "INSERT INTO $user_table_name 
								 (username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, telefono, email)
								 VALUES
								 ('{$_POST['username']}', '{$_POST['password']}', \"cli_p\", '{$_POST['nome']}','{$_POST['cognome']}',
								 '{$_POST['data_nascita']}', '{$_POST['via']}', '{$_POST['n_civico']}',
								 '{$_POST['citta']}', '{$_POST['CAP']}','{$_POST['telefono']}','{$_POST['email']}')
								 ";
								 
								//CONTROLLO QUERY
								if(!($resultQ = mysqli_query($connection, $sql))) {
									printf("Si è verificato un problema. Impossibile registrarsi.\n");
									exit();
								}
						}
						echo "<em> Registrazione avvenuta con successo!</em><br />";
						echo "<p class = \"p\">Vai al <a href = \"login_cli.php\"> login </a></p>";
						
				}
				
				else echo "<em>Completa tutti i campi per continuare </em>";	
			}
			
			//CHIUSURA CONNESSIONE
			$connection->close();		
		?>
			<h2> Signup Account personale</h2>
		</div>
		<div class = "corpo">
			<p>
				<em>(*) Campi obbligatori</em>
			</p>
			<hr  />
			<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "POST">
				<table class = "table" align = "center">
			  
					<tr><td class = "info">Nome*:</td>
						<td><input type = "text" name = "nome" size = "30" <?php if(isset($_POST['nome'])) echo "value = \"{$_POST['nome']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info">Cognome*: </td>
						<td><input type = "text" name = "cognome" size = "30" <?php if(isset($_POST['cognome'])) echo "value = \"{$_POST['cognome']}\""; ?>></td>
					</tr>
			   
					<tr> <td class = "info"> Data di nascita*: </td>
					<td><input type = "date" name = "data_nascita"></td>
					</tr>
				
					<tr>
						<td class = "info">Sesso:</td>
						<td>
							<input class = "selezione" type = "radio" name = "sesso" value = "M"> M 
							<input class = "selezione" type = "radio" name = "sesso" value = "F"> F 
						</td>
					</tr>
				
					<tr> <td class = "info"> Via* </td>
						<td><input type = "text" name = "via" size = "30" <?php if(isset($_POST['via'])) echo "value = \"{$_POST['via']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info"> Numero civico* </td>
						<td><input type = "text" name = "n_civico" size = "30" <?php if(isset($_POST['n_civico'])) echo "value = \"{$_POST['n_civico']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info"> Citt&agrave;* </td>
						<td><input type = "text" name = "citta" size = "30" <?php if(isset($_POST['citta'])) echo "value = \"{$_POST['citta']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info"> CAP* </td>
						<td><input type = "text" name = "CAP" size = "30" <?php if(isset($_POST['CAP'])) echo "value = \"{$_POST['CAP']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info"> Telefono* </td>
						<td><input type = "text" name = "telefono" size = "30" <?php if(isset($_POST['telefono'])) echo "value = \"{$_POST['telefono']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info">Username*:</td>
						<td><input type = "text" name = "username" size = "30" <?php if(isset($_POST['username'])) echo "value = \"{$_POST['username']}\""; ?>></td>
					</tr>
				
					<tr><td class = "info">Password*:</td>
						<td><input type = "password" name = "password" size = "30"></td>
					</tr>
				
					<tr><td class = "info"> E-mail*:</td>
						<td><input type = "text" name = "email" size = "30" <?php if(isset($_POST['email'])) echo "value = \"{$_POST['email']}\""; ?>></td>
					</tr>
					<tr><td class = "info"> Avatar: </td>
						<td>
							<ul>
								<li> <input type = "radio" name = "avatar" value = "4" /><img src = "immagini/4.png" alt = "user1" /></li>
								<li> <input type = "radio" name = "avatar" value = "5" /><img src = "immagini/5.png" alt = "user2" /></li>
								<br />
								<li> <input type = "radio" name = "avatar" value = "6" /><img src = "immagini/6.png" alt = "user3" /></li>
								<li> <input type = "radio" name = "avatar" value = "7" /><img src = "immagini/7.png" alt = "user4" /></li>
							</ul>
						</td>
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
		<p class = "p"> Hai gi&agrave; un account? <a href = "login_cli.php"> Accedi </a>
		<p class = "p" style = "margin-top: 0; margin-bottom: 5%;"> <a href = "signup.php"> Torna indietro </a> </p>
	</body>
</html>