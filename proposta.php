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
		<title> Proponi tipologia di spedizione - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		<div class = "menu">
			<a href = "home_ges.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "richieste.php"> Visualizza richieste di spedizione </a>
			<a href = "spedizioni.php"> Monitora spedizioni </a>
			<a href = "bytecouriers.php"> ByteCouriers </a>
			<a href = "prezzi.php"> Assegna prezzi </a>
		</div>
		
		<h1 class="titolo">Proponi tipologia di spedizione</h1>
		
		<?php
			if(isset($_POST['invio']) && empty($_POST['tipologia'])){
				echo "<p class=\"titolo\"><em>Seleziona un pacchetto alternativo da proporre al cliente</em></p><p class=\"titolo\"> Riprova</p>";
				$form = "<form action = \"{$_SERVER['PHP_SELF']}\" method = \"POST\">";
				$form.= "<input style=\"background-color: darkslategray; border-radius: 30px;\" title=\"Riprova\" class=\"seleziona\" type=\"submit\" name=\"proposta\" value=\"{$_POST['invio']}\" />";
				$form.= "</form>";
				
				echo $form;
			}
			else if(isset($_POST['proposta'])){
				stampa_richiesta();
				
				$form = "<form action = \"{$_SERVER['PHP_SELF']}\" method = \"POST\">";
				$form.= stampa_pacchetti();
				$form.= "<input style=\"background-color: darkslategray; border-radius: 30px;\" title=\"Seleziona\" class=\"seleziona\" type=\"submit\" name=\"invio\" value=\"{$_POST['proposta']}\" />";
				$form.= "</form>";
				
				//quando viene effettuata l'azione della form => generare il commento con questi campi e stato_avanzX = "Proposta"
				
				echo $form;
			}
		
		?>
		
	</body>
</html>

<?php
	function stampa_richiesta(){
		require_once("./connessione.php");
		//scandisco la lista spedizioni per individuare la mia spedizione
		$xmlSpedizione = "";
		foreach( file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		$spedizioni = $root->childNodes;
		
		$i = 0;
		$occorrenza = 0;
		
		while($i<$spedizioni->length && $occorrenza == 0){
			$sped = "";
			$spedizione = $spedizioni->item($i);
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			if($id_spedizioneY == $_POST['proposta'] ){
				$id_spedizioneX = $id_spedizione->textContent;
				
				$tipologiaX = $spedizione->getAttribute('tipologia');
				$statoX = $spedizione->getAttribute('stato');
				$usernameX = $spedizione->getAttribute('username');
				
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
					
				$auto = $spedizione->lastChild;
					
				//accedo al file tipologie.xml
				$xmlPacchetto = "";
				foreach(file("XML/tipologie.xml") as $nodo){
					$xmlPacchetto.= trim($nodo);
				}
				$doc = new DOMDocument();
				$doc->loadXML($xmlPacchetto);
				$root = $doc->documentElement;
				$pacchetti = $root->childNodes;
				
				$j = 0;
				$flag = 0;
				
				while($j<$pacchetti->length && $flag == 0){
					
					$pacchetto = $pacchetti->item($j);
					
					$id_pacchetto = $pacchetto->firstChild;
					$id_pacchettoX = $id_pacchetto->textContent;
					
					if($id_pacchettoX == $tipologiaX) {
						$nome= $id_pacchetto->nextSibling;
						$nomeY = $nome->textContent;
						
						$descrizione = $nome->nextSibling;
						$descrizioneX = $descrizione->textContent;
						
						$tempo_cons = $descrizione->nextSibling;
						$tempo_consX = $tempo_cons->textContent;
						
						$prezzo = $pacchetto->lastChild;;
						$prezzoX = $prezzo->textContent;
						
						$flag = 1;
					}	
				$j+=1;
				}
				$occorrenza = 1;
			}
			$i+=1;
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
		$sped.= "<div class = \"spedizione\"> <p style=\"text-align: center; color: maroon;\"><strong>CODICE SPEDIZIONE ".$id_spedizioneX."</strong></p>";
				$sped.= "<table>
							<tbody>
								<tr>
									<th> Emittente </th> <th> Tipologia di spedizione </th> <th> Ritiro presso </th>
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
										Nome: ".$nomeY."<br />Descrizione: ".$descrizioneX."<br />Tempo di consegna: ".$tempo_consX."
									</td>";
									if(isset($via)) {		
				$sped.= 				"<td>Domicilio del mittente<br />";
				$sped.= 					"Via: ".$viaY." ".$n_civicoY."<br />";
				$sped.= 					"Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
									}
									else $sped.= "<td>Centrale</td></tr>\n";
				
				$sped.= 		"<tr><th>Descrizione della merce</th><th>Destinatario</th><th>Pagamento a carico di</th></tr>";
				$sped.= 		"<tr><td>Numero colli: ".$n_colliX."<br />Altezza: ".$altezzaX." cm<br />Larghezza: ".$larghezzaX." cm<br />Lunghezza: ".$lunghezzaX." cm<br />Peso: ".$pesoX." kg<br />Fragile: ".$fragileX."</td>";
				$sped.= 			"<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."<br /><strong>Indirizzo di spedizione</strong><br />Via: ".$viaX." ".$n_civicoX."<br />";
				$sped.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td>";
				$sped.= 			"<td>".$onereX."</td></tr>";
				
				if($byteCourier1X != "") {
					$sped.= 		"<tr><th>ByteCourier 1</th><td colspan = \"2\">".$byteCourier1X."</td></tr>";
					$sped.= 		"<tr><th>ByteCourier 2</th><td colspan = \"2\"> Nessuno </td></tr>";
				}
				else {
					$sped.= 		"<tr><th>ByteCourier 1</th><td colspan = \"2\"> Nessuno </td></tr>";
					$sped.= 		"<tr><th>ByteCourier 2</th><td colspan = \"2\"> Nessuno </td></tr>";		
				}
				
				$sped.= 		"<tr><th>Importo totale:</th><td colspan = \"2\">".$prezzoX."&euro;</td></tr>";
			
				$sped.= "	</tbody>
						 </table></div>";
				
				
				echo $sped;
	}
	
	function stampa_pacchetti(){
		
		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo1){
			$xmlSpedizione.= trim($nodo1);
		}
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlSpedizione);
		$root1 = $doc1->documentElement;
		$spedizioni = $root1->childNodes;
		
		$k = 0;
		$occorrenza = 0;
		
		while($k<$spedizioni->length && $occorrenza == 0){
			$sped = "";
			$spedizione = $spedizioni->item($k);
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			if($id_spedizioneY == $_POST['proposta'] ){
				$id_spedizioneX = $id_spedizione->textContent;
				$tipologiaX = $spedizione->getAttribute('tipologia');
				$occorrenza = 1;
			}
			$k+=1;
		}
		
		
		$xmlPacchetto = "";
		foreach( file("XML/tipologie.xml") as $nodo){
			$xmlPacchetto .= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlPacchetto);
		$root = $doc->documentElement;
		$pacchetti = $root->childNodes;
		$offerte = "<div class = \"pacchetti\">";
		for($i=0; $i<$pacchetti->length; $i++){
			$pacchetto = $pacchetti->item($i);
			
			$stato = $pacchetto->getAttribute('stato');
			
			$id_pacchetto = $pacchetto->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			$nome = $id_pacchetto->nextSibling;
			$nomeX = $nome->textContent;
			
			$descrizione = $nome->nextSibling;
			$descrizioneX = $descrizione->textContent;
			
			$tempo_cons = $descrizione->nextSibling;
			$tempo_consX = $tempo_cons->textContent;
			
			$prezzo = $pacchetto->lastChild;
			$prezzoX = $prezzo->textContent;
			
			if($id_pacchettoX != $tipologiaX && $prezzoX && !$stato){
				$offerte.= "<span class=\"pacchetto\">
								<input type=\"radio\" name=\"tipologia\" value=\"{$id_pacchettoX}\" /> <strong>{$nomeX}</strong>
								<p class=\"wrap\">Descrizione: {$descrizioneX}</p>
								<p>Tempo di consegna: {$tempo_consX}</p>
								<p>Prezzo: {$prezzoX} &euro;</p>
							</span><hr />";
			}
		}
		$offerte.="</div>";
		return $offerte;
	}
	
	if(isset($_POST['invio']) && isset($_POST['tipologia'])){
		$xmlCommenti = "";
		foreach(file("XML/commenti.xml") as $nodo){
			$xmlCommenti.= trim($nodo);
		}
		
		$doc = new DOMDocument();
		$doc->loadXML($xmlCommenti);
		$root = $doc->documentElement;
		
		$commento = $doc->createElement("commento");
		
		$id_spedizione = $doc->createAttribute("id_spedizione");
		$id_spedizione->value = "{$_POST['invio']}";
		
		$username = $doc->createAttribute("username");
		$username->value = "{$_SESSION['username']}";
		
		$root->appendChild($commento);
		$commento->appendChild($id_spedizione);
		$commento->appendChild($username);
		
		$stato_avanz = $doc->createElement("stato_avanz", "Proposta");
		$commento->appendChild($stato_avanz);
		
		$descrizione = $doc->createElement("descrizone", "{$_POST['tipologia']}");
		$commento->appendChild($descrizione);
		
		$data = date("Y-m-d H:i:s");
		
		$timestamp= $doc->createElement("timestamp", $data);
		$commento->appendChild($timestamp);

		$doc->save('XML/commenti.xml');	
		
		$interazioni = "<h2 class=\"titolo\">Proposta inviata con successo</h2>";
		
		//scandisco i pacchetti per trovare il nome di quello selezionato
		$xmlPacchetto = "";
		foreach(file("XML/tipologie.xml") as $nodo){
			$xmlPacchetto .= trim($nodo);
		}
		
		$doc = new DOMDocument();
		$doc->loadXML($xmlPacchetto);
		$root = $doc->documentElement;
		$pacchetti = $root->childNodes;
		$offerte = "";
		
		$i=0;
		$occorrenza=0;
		
		while($i<$pacchetti->length && $occorrenza == 0) {
			$pacchetto = $pacchetti->item($i);
			
			$id_pacchetto = $pacchetto->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			if($id_pacchettoX == $_POST['tipologia']) {			
				$nome = $id_pacchetto->nextSibling;
				$occorrenza = 1;
			}
			$i+=1;
		}
		
		$interazioni.= "<div class=\"commento1\">";
		$interazioni.= "<div class=\"prima\"><h3>Gestore</h3></div>\n<div class=\"seconda\"><h4>".$stato_avanz->textContent."</h4></div>";
		$interazioni.= "<div class=\"contenuto\"><p>&Egrave; stata proposta la tipologia di spedizione: ".$nome->textContent."</p></div>";
		$interazioni.= "<div class=\"time\"><p>".$timestamp->textContent."</p></div>";
		$interazioni.= "</div>";
		$interazioni.= "<div class=\"link\"><a href=\"home_ges.php\">Torna alla home </a></div>";
		
		echo $interazioni;
	}
?>
