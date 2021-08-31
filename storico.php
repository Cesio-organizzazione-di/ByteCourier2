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
		<title> Monitora spedizioni - ByteCourier2 </title>
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

		<h1 class = "titolo">Richiesa di spedizione n° <?php echo $_POST['id_spedizione'] ?> </h1>
		
		<div class = "spedizione">
			<?php 
				spedizione(); 
			?>
		</div>
		
		
		<p class="back"><a href="#home"><img src="immagini/up-arrow.png" class="tornasu" title="Torna su" /></a></p>
	</body>
</html>

<?php
	function spedizione() {
		require("./connessione.php");
		//scandisco la lista spedizioni per individuare la mia spedizione
		$xmlSpedizione = "";
		foreach( file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		$spedizioni = $root->childNodes;
		$i=0;
		$occorrenza = 0;
		while($i<$spedizioni->length && $occorrenza == 0){
			$spedizione = $spedizioni->item($i);
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			if($id_spedizioneY == $_POST['id_spedizione']){
				$id_spedizioneX = $id_spedizione->textContent;
				
				$tipologiaX = $spedizione->getAttribute('tipologia');
				$statoX = $spedizione->getAttribute('stato');
				
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
				$autoX = $auto->textContent;
				
				//accedo al file tipologie.xml
				$xmlPacchetto = "";
				foreach(file("XML/tipologie.xml") as $nodo1){
					$xmlPacchetto.= trim($nodo1);
				}
				$doc1 = new DOMDocument();
				$doc1->loadXML($xmlPacchetto);
				$root1 = $doc1->documentElement;
				$pacchetti = $root1->childNodes;
				$j=0;
				$trovato = 0;
				while($j<$pacchetti->length && $trovato == 0){
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
						
						$trovato = 1;
					}
					$j+=1;
				}
				$occorrenza = 1;
			}
			$i+=1;
		}
		//CASO1: sped in carico o rifiutata
		if($statoX == "In carico" || $statoX == "Rifiutato"){
			//stampa info ordine
			if($statoX == "In carico")
				$riepilogo ="<div class=\"richiesta\" style=\"background-color: rgba(255, 215, 0,.5);\">";
			else
				$riepilogo ="<div class=\"richiesta\" style=\"background-color: rgba(178, 34, 34,.5);\">";
			$riepilogo.= "<p style=\"color: firebrick; text-align: center;\"><strong> CODICE SPEDIZIONE: ".$id_spedizioneX." </strong></p>";
			$riepilogo.= "<p>Stato dell'ordine: ".$statoX."</p>";
			$riepilogo.= "<table cellpadding = \"8\" cellspacing=\"0\" class=\"riepilogo\">\n<tbody valign=\"top\">\n";
			$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
			$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Descrizione: ".$descrizioneX."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

			if(isset($viaY)) {
				$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
				$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
				$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
			}
			else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
			
			$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
			$riepilogo.= "<tr><th>Descrizione della merce</th>\n<th>Pagamento a carico di</th>\n<th>byteCouriers</tr>\n";
			$riepilogo.= "<tr><td>Numero colli: ".$n_colliX."<br />Altezza: ".$altezzaX." cm<br />Larghezza: ".$larghezzaX." cm<br />";
			$riepilogo.= "Lunghezza: ".$lunghezzaX." cm<br />Peso: ".$pesoX." kg<br />Fragile: ".$fragileX."</td>";
			$riepilogo.= "<td>".$onereX."</td>";
			if(isset($via)){
				$riepilogo.= "<td>byteCourier1: ".$byteCourier1X;
			}else{
				$riepilogo.= "<td>byteCourier1: Non previsto";
			}
			
			if($byteCourier2X == ""){
				$riepilogo.= "<br />byteCourier2: Non ancora assegnato";
			}else{
				$riepilogo.= "<br />byteCourier2: ".$byteCourier2X;
			}
			$riepilogo.= "<p><u>Modalit&agrave; di assegnazione:</u><br />";
			if($autoX == "si"){
				$riepilogo.= " Autoassegnazione da parte dei byteCouriers </p></td></tr>";
			}else{
				$riepilogo.= " Assegnazione da parte dei gestori </p></td></tr>";
			}
			$riepilogo.= "</tbody>\n<tfoot>\n<tr><td style=\"width: 200px;\">Totale da pagare:</td><td colspan=\"2\"  style=\"text-align: right;\">".$prezzoX." &euro;</td></tr></tfoot>";
			$riepilogo.= "</table>";
			$riepilogo.= "</div>";
			
			echo $riepilogo;
			
			//scandisco commenti.xml per stampare quelli associati a questa spedizione 
			$xmlCommento = "";
			foreach(file("XML/commenti.xml") as $nodo2){
				$xmlCommento.= trim($nodo2);
			}
			$doc2 = new DOMDocument();
			$doc2->loadXML($xmlCommento);
			$root2 = $doc2->documentElement;
			$commenti = $root2->childNodes;
			
			$interazioni = "<div class=\"commenti\"><h2> Commenti associati alla spedizione </h2>";
			$form = "";
			$flag = 0;
			
			for($k=0; $k<$commenti->length; $k++){
				$commento = $commenti->item($k);
				
				$id_sped = $commento->getAttribute('id_spedizione');
				
				if($id_sped == $id_spedizioneX) {
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
					
					$timestamp = $commento->lastChild;
					$timestampX = $timestamp->textContent; 	
					
					$interazioni.="<div class=\"commento\">";
					if($stato_avanzX == "Proposta"){
						$interazioni.= "<div class=\"prima\"><h3>Gestore</h3></div>\n<div class=\"seconda\"><h4>".$stato_avanzX."</h4></div>";
						$interazioni.= "<div class=\"contenuto\"><p>Ti &egrave; stata proposta una nuova tipologia di spedizione:</p><span>".$contenutoX."</span><p> Tempo di consegna: ".$tempo_consX."</p><p>Prezzo: ".$prezzoX." &euro;</p></div>";	
						$interazioni.= "<div class=\"time\"><p>".$timestampX."</p></div>";
					}else{
						$sql = "SELECT * FROM $user_table_name WHERE username = \"".$user."\"";
						if(!$resultQ = mysqli_query($connection, $sql)) {
							echo "<p> Si &egrave; verificato un errore </p>";
							exit();
						}
						
						$row = mysqli_fetch_array($resultQ);
						
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
					}
					$interazioni.="</div>";
					
					$flag = 1;
				}
			}	
			if($flag == 0){
				$interazioni.= "<p><em>Non ci sono commenti per questa spedizione</em></p>";
			}
			
			$interazioni.="</div>";
			echo $interazioni;
		}
		//CASO3: sped consegnata
		if($statoX == "Consegnato"){
			//stampa info ordine
			$riepilogo ="<div class=\"richiesta\" style=\"background-color: rgba(50, 205, 50,.5);\">";
			$riepilogo.= "<p style=\"color: firebrick; text-align: center;\"><strong> CODICE SPEDIZIONE: ".$id_spedizioneX." </strong></p>";
			$riepilogo.= "<p>Stato dell'ordine: ".$statoX."</p>";
			$riepilogo.= "<table cellpadding = \"8\" cellspacing=\"0\" class=\"riepilogo\">\n<tbody valign=\"top\">\n";
			$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
			$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Descrizione: ".$descrizioneX."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

			if(isset($viaY)) {
				$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
				$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
				$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
			}
			else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
			
			$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
			$riepilogo.= "<tr><th>Descrizione della merce</th>\n<th>Pagamento a carico di</th>\n<th>byteCouriers</tr>\n";
			$riepilogo.= "<tr><td>Numero colli: ".$n_colliX."<br />Altezza: ".$altezzaX." cm<br />Larghezza: ".$larghezzaX." cm<br />";
			$riepilogo.= "Lunghezza: ".$lunghezzaX." cm<br />Peso: ".$pesoX." kg<br />Fragile: ".$fragileX."</td>";
			$riepilogo.= "<td>".$onereX."</td>";
			if(isset($via)){
				$riepilogo.= "<td>byteCourier1: ".$byteCourier1X;
			}else{
				$riepilogo.= "<td>byteCourier1: Non previsto";
			}
			$riepilogo.= "<br />byteCourier2: ".$byteCourier2X;
			$riepilogo.= "<p><u>Modalit&agrave; di assegnazione:</u><br />";
			if($autoX == "si"){
				$riepilogo.= " Autoassegnazione da parte dei byteCouriers </p></td></tr>";
			}else{
				$riepilogo.= " Assegnazione da parte dei gestori </p></td></tr>";
			}
			$riepilogo.= "</tbody>\n<tfoot>\n<tr><td style=\"width: 200px;\">Totale da pagare:</td><td colspan=\"2\"  style=\"text-align: right;\">".$prezzoX." &euro;</td></tr></tfoot>";
			$riepilogo.= "</table>";
			
			$riepilogo.="</div>";
			echo $riepilogo;
			
			//scandisco commenti.xml per stampare quelli associati a questa spedizione 
			$xmlCommento = "";
			foreach(file("XML/commenti.xml") as $nodo2){
				$xmlCommento.= trim($nodo2);
			}
			$doc2 = new DOMDocument();
			$doc2->loadXML($xmlCommento);
			$root2 = $doc2->documentElement;
			$commenti = $root2->childNodes;
			
			$interazioni = "<div class=\"commenti\"><h2> Commenti associati alla spedizione </h2>";
			$form = "";
			$flag = 0;
			
			for($k=0; $k<$commenti->length; $k++){
				$commento = $commenti->item($k);
				
				$id_sped = $commento->getAttribute('id_spedizione');
				
				if($id_sped == $id_spedizioneX) {
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
					
					$timestamp = $commento->lastChild;
					$timestampX = $timestamp->textContent; 	
					
					$interazioni.="<div class=\"commento\">";
					if($stato_avanzX == "Proposta"){
						$interazioni.= "<div class=\"prima\"><h3>Gestore</h3></div>\n<div class=\"seconda\"><h4>".$stato_avanzX."</h4></div>";
						$interazioni.= "<div class=\"contenuto\"><p>Ti &egrave; stata proposta una nuova tipologia di spedizione:</p><span>".$contenutoX."</span><p> Tempo di consegna: ".$tempo_consX."</p><p>Prezzo: ".$prezzoX." &euro;</p></div>";	
						$interazioni.= "<div class=\"time\"><p>".$timestampX."</p></div>";
					}else{
						$sql = "SELECT * FROM $user_table_name WHERE username = \"".$user."\"";
						if(!$resultQ = mysqli_query($connection, $sql)) {
							echo "<p> Si &egrave; verificato un errore </p>";
							exit();
						}
						
						$row = mysqli_fetch_array($resultQ);
						
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
					}
					$interazioni.="</div>";
					
					$flag = 1;
				}
			}	
			if($flag == 0){
				$interazioni.= "<p><em>Non ci sono commenti per questa spedizione</em></p>";
			}
			
			$interazioni.="</div>";
			echo $interazioni;
			
			
			//scandisco il file valutazioni.xml per la stampa della valutazione lasciata dal cliente
			$xmlValutazione = "";
			foreach(file("XML/valutazioni.xml") as $nodo1){
				$xmlValutazione.= trim($nodo1);
			}
			$doc1 = new DOMDocument();
			$doc1->loadXML($xmlValutazione);
			$root1 = $doc1->documentElement;
			$valutazioni = $root1->childNodes; 	
			$j=0; 
			$trovato = 0;
			$voto = "";
			while($j<$valutazioni->length && $trovato==0){
				$valutazione = $valutazioni->item($j);
				$id_sped = $valutazione->getAttribute('id_spedizione');
				
				if($id_sped == $_POST['id_spedizione']) {
					
					$username = $valutazione->firstChild; 
					$usernameX = $username->textContent;

					$soddisfazione = $username->nextSibling;
					$soddisfazioneX = $soddisfazione->textContent;
					
					$rapidita = $valutazione->lastChild;
					$rapiditaX = $rapidita->textContent;
					
					$tot = $soddisfazioneX*(3/5) + $rapiditaX*(2/5);
					
					$trovato = 1;
				} 
				$j+=1;
			}
		
			if($trovato == 1){
				$voto ="<div class=\"valutazioni\">";
				$voto.= "<div class=\"valutazione\"><div class=\"first\"><h2>Valutazione spedizione n° ".$_POST['id_spedizione']."</h2></div>";
				$voto.= "<div class=\"username\"><p>Username: ".$usernameX."</p></div>";
				$voto.= "<div class=\"voto\"><p>Soddisfazione: ".$soddisfazioneX."<br />Rapidit&agrave;: ".$rapiditaX."</p></div>";
				$voto.= "<div class=\"tot\"><p>Totale: ".$tot."</p></div>";
				$voto.= "</div>";
				$voto.= "</div>";
			}
			echo $voto;
		}
	}
	
	
?>