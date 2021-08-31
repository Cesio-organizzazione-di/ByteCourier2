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
		<title> <?php echo $_POST['scelta']?> - ByteCourier2 </title>
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
			<a href = "bytecouriers.php"> ByteCouriers </a>
			<a href = "prezzi.php"> Assegna prezzi </a>
		</div>

		<h1 class = "titolo">ByteCourier <?php echo $_POST['scelta']?> </h1>
		
		<div class = "corriere">
			<?php 
				 corriere();
			?>
		</div>
		
		<div class = "tutte_le_spedizioni">
			<h2 class = "titolo"> Spedizioni associate a <?php echo $_POST['scelta']?> </h2>
			<?php 
				spedizioni();
			?>
		</div>
	</body>
</html>

<?php 
	function corriere() {
		require("./connessione.php");
		
		//seleziono quel corriere dal database
		$sql = "SELECT * FROM $user_table_name WHERE username = \"".$_POST['scelta']."\"";
		
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p><em>Errore durante l'accesso al database</p>";
			exit();
		}
		
		$row = mysqli_fetch_array($resultQ); 
		
		if($row['abilitato'] == 0) {
			$profilo ="<div class=\"corpo\" style=\"background-color: silver;\">";
			$profilo.= "<div class = \"info\"> Corriere disattivato </div>";
		}
		else {
			if($row['stato'] == "busy")
				$profilo = "<div class=\"corpo\" style=\"background-color: firebrick; color: white;\">";
				
			else 
				$profilo = "<div class = \"corpo\" style=\"background-color: lightgreen; color: black;\">";
			$profilo.= "<div class = \"stato\"> Stato: ".$row['stato']."</div>";
		}
		$profilo.= "<div class = \"info\"><strong>Nome: </strong>".$row['nome']." ".$row['cognome']."</div>";
		$profilo.= "<div class = \"info\"><strong>Data di nascita: </strong>".$row['data_nascita']."</div>";
		$profilo.= "<div class = \"indirizzo\"><u>Indirizzo:</u> <br />";
		$profilo.= "<strong>Via: </strong>".$row['via']." ".$row['n_civico']."<br />";
		$profilo.= "<strong>Citt&agrave;: </strong>".$row['citta'].", ";
		$profilo.= $row['CAP']."<br />";
		$profilo.= "</div>";
		$profilo.= "<div class = \"info\"><strong>Telefono: </strong>".$row['telefono']."</div>";
		$profilo.= "<div class = \"info\"><strong>E-mail: </strong>".$row['email']."</div>";
		if($row['valutazione'])
			$profilo.= "<div class = \"media\"><strong>Valutazione </strong>".$row['valutazione']."</div>";
		else 
			$profilo.= "<div class = \"media\"><strong>Valutazione </strong> -- </div>";
		$profilo.= "</div>";
		
		echo $profilo;
	}
	
	function spedizioni(){
		require("./connessione.php");
		$xmlSpedizione = "";
		foreach( file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		$spedizioni = $root->childNodes;
		
		$occorrenza = 0;
		
		for($i=0; $i<$spedizioni->length; $i++){
			$sped ="";
			$spedizione = $spedizioni->item($i);
			
			$statoX = $spedizione->getAttribute('stato');
			
			$id_spedizione = $spedizione->firstChild;
			$bc1 = $id_spedizione->nextSibling;
			$bc1X = $bc1->textContent;
			$bc2 = $bc1->nextSibling;
			$bc2X = $bc2->textContent;
		
			$auto = $spedizione->lastChild;
			$autoX = $auto->textContent;
			
			$sql = "SELECT * FROM $user_table_name WHERE username = \"".$_POST['scelta']."\"";
		
			if(!$resultQ = mysqli_query($connection, $sql)) {
				echo "<p><em>Errore durante l'accesso al database</p>";
				exit();
			}
		
			$row = mysqli_fetch_array($resultQ); 
			
			if($statoX && ($bc1X == $row['username'] || $bc2X == $row['username'])){
				
				$occorrenza = 1;
				
				$usernameX = $spedizione->getAttribute('username');
				
				$tipologiaX = $spedizione->getAttribute('tipologia');
		
				$id_spedizione = $spedizione->firstChild;
				$id_spedizioneX = $id_spedizione->textContent;
				
				$byteCourier1 = $id_spedizione->nextSibling;
				$byteCourier1X = $byteCourier1->textContent;
				
				$byteCourier2 = $byteCourier1->nextSibling;
				$byteCourier2X = $byteCourier2->textContent;
				
				$destinatario = $byteCourier2->nextSibling;
					$nome = $destinatario->firstChild;
					$nomeX = $nome->textContent;
					
					$indirizzo = $nome->nextSibling;
						$via = $indirizzo->firstChild;
						$viaX = $via->textContent;
						
						$n_civico = $via->nextSibling;
						$n_civicoX = $n_civico->textContent;
						
						$citta = $n_civico->nextSibling;
						$cittaX = $citta->textContent;
						
						$CAP = $indirizzo->lastChild;
						$CAPX = $CAP->textContent;
					
					$telefono = $destinatario->lastChild;
					$telefonoX = $telefono->textContent;
					
				$n_colli = $destinatario->nextSibling;
				$n_colliX = $n_colli->textContent;
				
				$tipo_collo = $n_colli->nextSibling;
					$dimensione = $tipo_collo->firstChild;
						$altezza = $dimensione->firstChild;
						$altezzaX = $altezza->textContent;
						
						$larghezza = $altezza->nextSibling;
						$larghezzaX = $larghezza->textContent;
						
						$lunghezza = $dimensione->lastChild;
						$lunghezzaX = $lunghezza->textContent;
					
					$peso = $dimensione->nextSibling;
					$pesoX = $peso->textContent;
					
					$fragile = $tipo_collo->lastChild;
					$fragileX = $fragile->textContent;
					
				$onere = $tipo_collo->nextSibling;
				$onereX = $onere->textContent;
				
				$ritiro= $onere->nextSibling;
				
				$via = $ritiro->firstChild;
				if(isset($via)) {
					$viaY = $via->textContent;
			
					$n_civico = $via->nextSibling;
					$n_civicoY = $n_civico->textContent;
						
					$citta = $n_civico->nextSibling;
					$cittaY = $citta->textContent;
						
					$CAP = $ritiro->lastChild;
					$CAPY = $CAP->textContent;
				}
					
					
					
				//accedo al file tipologie.xml
				$xmlPacchetto = "";
				foreach( file("XML/tipologie.xml") as $nodo){
					$xmlPacchetto.= trim($nodo);
				}
				$doc = new DOMDocument();
				$doc->loadXML($xmlPacchetto);
				$root = $doc->documentElement;
				$pacchetti = $root->childNodes;
				
				for($k=0; $k<$pacchetti->length; $k++){
					$pacchetto = $pacchetti->item($k);
					
					$id_pacchetto = $pacchetto->firstChild;
					$id_pacchettoX = $id_pacchetto->textContent;
					
					if($id_pacchettoX == $tipologiaX) {
						$nome= $id_pacchetto->nextSibling;
						$nomeY = $nome->textContent;
						
						$descrizione = $nome->nextSibling;
						$descrizioneX = $descrizione->textContent;
						
						$tempo_cons = $descrizione->nextSibling;
						$tempo_consX = $tempo_cons->textContent;
						
						$prezzo = $pacchetto->lastChild;
						$prezzoX = $prezzo->textContent;
						break;
					}
				}
				
				//stampa info ordine
				$sql = "SELECT * 
						FROM $user_table_name U
						WHERE U.username = \"$usernameX\"
						";
						
				if(!$resultQ = mysqli_query($connection, $sql)) {
					echo "<p> Si &egrave; verificato un errore </p>";
					exit();
				}
				
				$row1 = mysqli_fetch_array($resultQ);
				
				if($statoX == "In carico") {
					$sped.= "<form action=\"storico.php\" method=\"post\">";
					$sped.="<div class=\"sopra\" style=\"background-color: rgba(255, 215, 0,0.5);\">";
					$sped.= "<p><strong> CODICE SPEDIZIONE </strong>
							<input class=\"bottone\" style = \"background-color: rgb(255, 215, 0);\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" />
						 </p></form></div>\n";
				}
				else if($statoX == "Rifiutato") {
					$sped.= "<form action=\"storico.php\" method=\"post\">";
					$sped.="<div class=\"sopra\" style=\"background-color: rgba(178, 34, 34,0.5);\">";
					$sped.= "<p><strong> CODICE SPEDIZIONE </strong>
							<input class=\"bottone\" style = \"background-color: rgb(178, 34, 34);\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" />
						 </p></form></div>\n";
				}
				else if($statoX == "Consegnato") {
					$sped.= "<form action=\"storico.php\" method=\"post\">";					
					$sped.="<div class=\"sopra\" style=\"background-color: rgba(50, 205, 50,0.5);\">";
					$sped.= "<p><strong> CODICE SPEDIZIONE </strong>
							<input class=\"bottone\" style = \"background-color: rgb(5'0, 205, 50);\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" />
						 </p></form></div>\n";
				}
				else {
					$sped.= "<form action=\"monitora_richiesta.php\" method=\"post\">";
					$sped.="<div class=\"sopra\" style=\"background-color: rgba(192, 192, 192,0.5);\">";
					$sped.= "<p><strong> CODICE SPEDIZIONE </strong>
							<input class=\"bottone\" style = \"background-color: rgb(192, 192, 192);\" type=\"submit\" name=\"invio\" value=\"".$id_spedizioneX."\" />
						 </p></form></div>\n";
				}
				
				$sped.= "<table>\n
							<tbody>\n
								<tr>
									<th> Emittente </th> <th> Tipologia di spedizione </th>
								</tr>
								<tr> 
									<td>";
										if($row1['tipo_utente'] == "cli_p") {
											$sped.="Nominativo: {$row1['nome']} {$row1['cognome']}"; 
										}
										else if($row1['tipo_utente'] == "cli_az") {
											$sped.="Nominativo: {$row1['nome_attivita']}"; 
										}
					$sped.= 			"<br />Telefono: {$row1['telefono']}<br />
											   Email: {$row1['email']}
									</td>
									<td>
										Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."
									</td></tr>";
				
				$sped.= 		"<tr><th>Destinatario</th><th> Ritiro presso </th></tr>";
				$sped.= 		"<tr><td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."<br /><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
				$sped.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td>";
				
				if(isset($via)) {
				$sped.= 				"<td><u>Domicilio del mittente</u><br />";
				$sped.= 					"Via: ".$viaY." ".$n_civicoY."<br />";
				$sped.= 					"Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
									}
									else $sped.= "<td>Centrale</td></tr>\n";
				
				$sped.= "	</tbody></table>";
				echo $sped;		
				
			}
			
		}
		
		if($occorrenza == 0)
			echo "<p style = \"text-align: center;\">Nessuna spedizione</p>";
		else 
			echo "<p class=\"back\"><a href=\"#home\"><img src=\"immagini/up-arrow.png\" class=\"tornasu\" title=\"Torna su\" /></a></p>";
		
	}