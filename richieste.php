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
		<title> Richieste in sospeso - ByteCourier2 </title>
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
			<p> Visualizza richieste di spedizione </p>
			<a href = "spedizioni.php"> Monitora spedizioni </a>
			<a href = "bytecouriers.php"> ByteCouriers </a>
			<a href = "prezzi.php"> Assegna prezzi </a>
		</div>
		
		<div class="spedizioni">
			<h1>Richieste di spedizione in sospeso</h1>
			<?php 
				stampa_in_sospeso();
			?>
		</div>
		
		
	</body>
</html>

<?php
	function stampa_in_sospeso() {
		require_once("./connessione.php");
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
			
			if($statoX == "In sospeso"){
				
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
				foreach(file("XML/tipologie.xml") as $nodo1){
					$xmlPacchetto.= trim($nodo1);
				}
				$doc1 = new DOMDocument();
				$doc1->loadXML($xmlPacchetto);
				$root1 = $doc1->documentElement;
				$pacchetti = $root1->childNodes;
				$k=0;
				$trovato = 0;
				while( $k<$pacchetti->length && $trovato == 0){
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
						$trovato = 1;
					}
					$k++;
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
				
				$row = mysqli_fetch_array($resultQ);
				
				$sped.= "<form action=\"monitora_richiesta.php\" method=\"post\">";
				$sped.= "<div class = \"sopra\" style = \"background-color: rgba(192, 192, 192,0.7);\"><p><strong> CODICE SPEDIZIONE </strong>
							<input class=\"bottone\" type=\"submit\" name=\"invio\" value=\"".$id_spedizioneX."\" />
						 </p></form></div>";
				$sped.= "<table>\n
							<tbody>\n
								<tr>
									<th> Emittente </th> <th> Tipologia di spedizione </th>
								</tr>
								<tr> 
									<td>";
										if($row['tipo_utente'] == "cli_p") {
											$sped.="Nominativo: {$row['nome']} {$row['cognome']}"; 
										}
										else if($row['tipo_utente'] == "cli_az") {
											$sped.="Nominativo: {$row['nome_attivita']}"; 
										}
					$sped.= 			"<br />Telefono: {$row['telefono']}<br />
											   Email: {$row['email']}
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
		
		if($occorrenza == 0){
			$sped = "<p>Non ci sono richieste di spedizione in sospeso</p>";
			echo $sped;
		}
		else 
			echo "<p class=\"back\"><a href=\"#home\"><img src=\"immagini/up-arrow.png\" class=\"tornasu\" title=\"Torna su\" /></a></p>";
	}
?>