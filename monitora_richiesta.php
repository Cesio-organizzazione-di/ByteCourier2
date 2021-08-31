<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

echo '<?xml version="1.0" encoding="UTF-8"?>';

$occorrenza = 0;

function stampa_commenti() {
	
	require("./connessione.php");
	//scandisco commenti.xml per stampare quelli associati a questa spedizione 
	$xmlCommento = "";
	foreach(file("XML/commenti.xml") as $nodo){
		$xmlCommento.= trim($nodo);
	}
	$doc = new DOMDocument();
	$doc->loadXML($xmlCommento);
	$root = $doc->documentElement;
	$commenti = $root->childNodes;
	
	echo "<h2> Commenti associati alla spedizione </h2>";
	
	$interazioni = "";
	$flag = 0;
	
	for($p=0; $p<$commenti->length; $p++){
		$commento = $commenti->item($p);
		
		$id_sped = $commento->getAttribute('id_spedizione');
		
		if($id_sped == $_POST['invio']) {
			$user = $commento->getAttribute('username'); 
			
			$stato_avanz = $commento->firstChild; 
			if(isset($stato_avanz)) {
				$stato_avanzX = $stato_avanz->textContent;
			}
			
			$contenuto = $stato_avanz->nextSibling;
			if(isset($contenuto)) {
				$contenutoX = $contenuto->textContent;	
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
					
					if($id_pacchettoX == $contenutoX) {			
						$nome = $id_pacchetto->nextSibling;
						$contenutoX = $nome->textContent;
						
						$descrizione = $nome->nextSibling;
						
						$tempo_cons = $descrizione->nextSibling;
						$tempo_consX = $tempo_cons->textContent;
						
						$prezzo = $pacchetto->lastChild;
						$prezzoX = $prezzo->textContent;
						
						$occorrenza = 1;
					}
					$i+=1;
				}
			}
			else $contenutoX = "";
			
			$timestamp = $commento->lastChild;
			$timestampX = $timestamp->textContent; 

			
			
			$sql = "SELECT * FROM $user_table_name WHERE username = \"".$user."\"";
			if(!$resultQ = mysqli_query($connection, $sql)) {
				echo "<p> Si &egrave; verificato un errore </p>";
				exit();
			}
			
			$row = mysqli_fetch_array($resultQ);
			
			$interazioni.="<div class=\"commento\">";
			if($row['tipo_utente'] == "bc") {
				$interazioni.= "<div class=\"prima\"><h3>ByteCourier</h3></div>\n<div class=\"seconda\"><h4>".$stato_avanzX."</h4></div>";
				$interazioni.= "<div class=\"contenuto\"><p>".$contenutoX."</p></div>";
				$interazioni.= "<div class=\"time\"><p>".$timestampX."</p></div>";
			}
			if($row['tipo_utente'] == "ges") {
				$interazioni.= "<div class=\"prima\"><h3>Gestore</h3></div>\n<div class=\"seconda\"><h4>".$stato_avanzX."</h4></div>";
				$interazioni.= "<div class=\"contenuto\"><p>".$contenutoX."</p></div>";	
				$interazioni.= "<div class=\"time\"><p>".$timestampX."</p></div>";
			}
			if(preg_match("/cli.*/", $row['tipo_utente'])){
				$interazioni.= "<div class=\"prima\"><h3>".$user."</h3></div>\n<div class=\"seconda\"><h4>".$stato_avanzX."</h4></div>";
				$interazioni.= "<div class=\"contenuto\"><p>".$contenutoX."</p></div>";	
				$interazioni.= "<div class=\"time\"><p>".$timestampX."</p></div>";
			}
			$interazioni.="</div>";

			$flag = 1;
		}
	}
	
	if($flag == 1){
		echo $interazioni;
	}else{
		$interazioni = "<p><em>Non ci sono commenti per questa spedizione</em></p>";
		echo $interazioni;
	}
}
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Monitora richiesta - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		<div class = "menu" id = "home">
			<a href = "home_ges.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "richieste.php"> Visualizza richieste di spedizione </a>
			<a href = "spedizioni.php"> Monitora spedizioni </a>
			<a href = "bytecouriers.php"> ByteCouriers </a>
			<a href = "prezzi.php"> Assegna prezzi </a>
		</div>
		
		<div class="richiesta">
			<?php 
				stampa();
			?>
		</div>
		
		<p class="back"><a href="#home"><img src="immagini/up-arrow.png" class="tornasu" title="Torna su" /></a></p>
	</body>
</html>

<?php
	function stampa(){
		require("./connessione.php");
		if(isset($_POST['invio'])){
			
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
				if($id_spedizioneY == $_POST['invio'] ){
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
		}
		//controllo se è stata individuata la spedizione
		if($occorrenza == 0 && isset($_POST['cerca']) && isset($_POST['id_spedizione'])){
			$msg="<p>Non &egrave; stato possibile trovare la richiesta da spedizione da lei indicata</p>";
			echo $msg;
		}
		
		//è stata trovata una spedizione con questo codice
		if($occorrenza == 1) {
			
			if($statoX == "In sospeso") {
				
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
				$sped.= "<div class = \"spedizione\"> <p><strong>CODICE SPEDIZIONE ".$id_spedizioneX."</strong></p>";
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
								
				
				//bottoni con cui il gestore può interagire 
				//prendo l'ultimo commento associato alla spedizione
				$xmlCommento = "";
				foreach(file("XML/commenti.xml") as $nodo){
					$xmlCommento.= trim($nodo);
				}
				$doc = new DOMDocument();
				$doc->loadXML($xmlCommento);
				$root = $doc->documentElement;
				$commenti = $root->childNodes;
				
				for($j=0; $j<$commenti->length; $j++){
					$commento = $commenti->item($j);
					$id_sped = $commento->getAttribute('id_spedizione');
					
					if($id_sped == $id_spedizioneX) {
						$user = $commento->getAttribute('username'); 
						
						$stato_avanz = $commento->firstChild; 
						
						
						$stato_avanzX = $stato_avanz->textContent;
			
						
						$contenuto = $stato_avanz->nextSibling;
						
						$timestamp = $commento->lastChild;
					}
				}
				
				if($auto->textContent != "si"){
					//CASO1: richiesta appena generata 
					if(!isset($stato_avanzX)){
						$form = "<div class=\"form\"><form action=\"assegna.php\" method=\"post\">";
						$form.= "<p>Accetta richiesta di spedizione e assegna corriere <input class = \"accetta\" type=\"submit\" name=\"accetta\" title=\"Accetta\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form>";
						$form.= "<form action=\"proposta.php\" method=\"post\">";
						$form.= "<p>Proponi un'altra tipologia di spedizione <input class = \"proposta\" type=\"submit\" name=\"proposta\" title=\"Proponi\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form>";
						$form.= "<form action=\"elimina.php\" method=\"post\">";
						$form.= "<p>Rifiuta richiesta di spedizione <input class = \"rifiuta\" type=\"submit\" name=\"rifiuta\" title=\"proponi\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form></div>";
						
						echo $form;
					}
					
					
					
					//CASO2: il gestore ha proposto una tipologia al cliente ed esso ha risposto positivamente
					else if($stato_avanzX == "Risposta"){
						$form = "<div class=\"form\"><form action=\"assegna.php\" method=\"post\">";
						$form.= "<p>Accetta richiesta di spedizione e assegna corriere <input class = \"accetta\" type=\"submit\" name=\"accetta\" title=\"Accetta\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form>";
						$form.= "<form action=\"elimina.php\" method=\"post\">";
						$form.= "<p>Rifiuta richiesta di spedizione <input class = \"rifiuta\" type=\"submit\" name=\"rifiuta\" title=\"proponi\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form></div>";
						
						echo $form;
						
					}
					
					//CASO3: assegnazione del bc2 dopo che la richiesta è stata servita da un bc1
					else if($byteCourier1X != "" && $stato_avanzX == "Pacco consegnato in centrale"){
						$form = "<div class=\"form\"><form action=\"assegna.php\" method=\"post\">";
						$form.= "<p>Accetta richiesta di spedizione e assegna corriere <input class = \"accetta\" type=\"submit\" name=\"accetta\" title=\"Accetta\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form></div>";
						
						echo $form;
						
					}
					
					//CASO4: si sono verificati problemi nella consegna
					else if($stato_avanzX == "Problemi nella consegna"){
						$form = "<div class=\"form\"><form action=\"assegna.php\" method=\"post\">";
						$form.= "<p>Assegna un nuovo corriere <input class = \"accetta\" type=\"submit\" name=\"accetta\" title=\"Accetta\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form>";
						$form.= "<form action=\"elimina.php\" method=\"post\">";
						$form.= "<p>Elimina richiesta <input class = \"rifiuta\" type=\"submit\" name=\"rifiuta\" title=\"proponi\" value = \"".$id_spedizioneX."\" /></p>";
						$form.= "</form></div>";
						
						echo $form;
				
					}
					
					//CASO5: il gestore ha proposto una nuova tipologia al cliente ma il cliente deve ancora rispondere
					else if($stato_avanzX == "Proposta"){
						echo "<p><em>Richiesta in sospeso: non ci sono ancora novit&agrave;</em></p>";
					}
				}
				
				else {
					echo "<p><em>Non &egrave; stato ancora assegnato nessun byteCourier a questa spedizione</em></p>";
				}
			
				if(isset($commento)) {
					echo "<div class=\"commenti\">";
						stampa_commenti();
					echo "</div>";
				}
				
			}
		}
	}


?>