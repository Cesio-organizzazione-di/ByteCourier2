<?php		
error_reporting(E_ALL &~E_NOTICE);	
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	
	<head> 
		<title> Creazione e popolamento DB </title> 
		<link rel = "stylesheet" type = "text/css" href = "stile_team.css" />
		</head>
	
	<body class="index">
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "titolo">
			<h1> ByteCourier2 </h1>
		
			<?php 
				$db_name = "lweb9";
				$user_table_name = "Utente";
							
			//CREAZIONE TABELLE	
				//APERTURA CONNESSIONE AL DB CREATO 
				$connection = new mysqli("localhost", "lweb9", "lweb9" , $db_name);
				
				//CONTROLLO CONNESSIONE
				if(mysqli_connect_errno()) {
					printf("<h3>Problemi di connessione al db: %s\n</h3>" ,  mysqli_connect_error());
					exit();
				}
				
				//CREAZIONE TABELLA USERS
				
				$sqlQuery = "CREATE TABLE if not exists $user_table_name (\n";
				$sqlQuery.= "username varchar (30), primary key (username), \n";
				$sqlQuery.= "password varchar (30) NOT NULL, \n";
				$sqlQuery.= "tipo_utente varchar(8), \n";
				$sqlQuery.= "nome varchar (30), \n";
				$sqlQuery.= "cognome varchar (30), \n";
				$sqlQuery.= "nome_attivita varchar (30), \n";
				$sqlQuery.= "data_nascita DATE, \n"; 
				$sqlQuery.= "cf varchar (17), \n";
				$sqlQuery.= "p_iva varchar (12), \n";
				$sqlQuery.= "settore varchar (30), \n";
				$sqlQuery.= "via varchar (30), \n";
				$sqlQuery.= "n_civico int, \n";
				$sqlQuery.= "citta varchar (30), \n";
				$sqlQuery.= "CAP varchar (6), \n";
				$sqlQuery.= "sesso varchar(2), \n";
				$sqlQuery.= "telefono bigint, \n";
				$sqlQuery.= "email varchar(30) NOT NULL, \n";
				$sqlQuery.= "avatar int, \n";
				$sqlQuery.= "abilitato boolean default true, \n";
				$sqlQuery.= "valutazione float, \n";
				$sqlQuery.= "stato varchar(10) default \"free\"\n";
				$sqlQuery.= ");\n";
				
				echo "<pre>$sqlQuery</pre>";
				
				//VERIFICA CREAZIONE TABELLA UTENTE
				if ($resultQ = mysqli_query($connection, $sqlQuery)) 
					printf("<h3>Tabella Utente creata con successo!\n</h3>");
				else {
					printf("<h3>Creazione tabella Utente fallita\n </h3>");
					exit();
				}
				
			
			//POPOLAMENTO 
				//POPOLAMENTO UTENTE
				//clienti
				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome_attivita, cf, p_iva, settore, via, n_civico, citta, CAP, telefono, email,  
				avatar)
				VALUES
				(\"orelli_srl\", \"1234\", \"cli_az\", \"Orelli S.R.L.\", 
				\"CCCNNN00A01A000A\", \"01234567890\", \"Metallurgico\", \"Via del Colosseo\", \"101\", \"Roma\", \"00100\", \"3332356789\", 
				\"orellisrl@mail.it\", \"2\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>Cliente inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento cliente</p>\n";
					exit();
				}
				
				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email,  
				avatar)
				VALUES
				(\"xrushofblood\", \"1234\", \"cli_p\", \"Angelica\", \"Della Vecchia\", 
				\"1997-10-05\", \"Via Giovanniello\", \"45\", \"Latina\", \"04100\", \"F\", \"3338921325\",\"angelica@mail.it\", \"6\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>Cliente inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento cliente</p>\n";
					exit();
				}
				
				//bytecouriers
				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email,valutazione)
				VALUES
				(\"bc01\", \"1234\", \"bc\", \"Antonio\", \"Cristallo\", 
				\"1994-12-30\", \"Via Nascosa\", \"34\", \"Latina\", \"04100\", \"M\", \"3476589765\",\"antonio@mail.it\", \"4.4\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>ByteCourier inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento byteCourier</p>\n";
					exit();
				}
				
				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email, valutazione)
				VALUES
				(\"bc02\", \"1234\", \"bc\", \"Laura\", \"Stella\", 
				\"1998-01-31\", \"Via Montagna\", \"2\", \"Sezze\", \"04018\", \"F\", \"3275649347\",\"laura@mail.it\", \"4.4\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>ByteCourier inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento byteCourier</p>\n";
					exit();
				}
				
				//gestori
				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email)
				VALUES
				(\"g01\", \"1234\", \"ges\", \"Elisa\", \"Orelli\", 
				\"1989-03-16\", \"Via Foresta\", \"6\", \"Sezze\", \"04018\", \"F\", \"345789234\",\"elisa@mail.it\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>Gestore inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento gestore</p>\n";
					exit();
				}

				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email)
				VALUES
				(\"g02\", \"1234\", \"ges\", \"Edoardo\", \"Strozzi\", 
				\"1994-05-13\", \"Via Forcella\", \"9\", \"Napoli\", \"80100\", \"M\", \"3249874563\",\"edoardo@mail.it\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>Gestore inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento gestore</p>\n";
					exit();
				}
				
				//admin
				$sql = "INSERT INTO $user_table_name
				(username, password, tipo_utente, nome, cognome, data_nascita, via, n_civico, citta, CAP, sesso, telefono, email)
				VALUES
				(\"admin\", \"1234\", \"adm\", \"Mario\", \"Rossi\", 
				\"1987-02-18\", \"Via Pascarella\", \"1\", \"Roma\", \"00031\", \"M\", \"3896754213\",\"mario@mail.it\")";
				
				if($resultQ = mysqli_query($connection, $sql)) 
					echo "<p>Admin inserito correttamente!</p>\n";
				else {
					echo "<p>Errore inserimento admin</p>\n";
					exit();
				}
			?>
		</div>
	</body>
</html>