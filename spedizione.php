<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Home Corriere - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_bc.css" />
	</head>
	<body class="bc">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "home_bc.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "corr_in_sospeso.php"> Spedizioni in sospeso </a>
			<a href = "corr_in_carico.php"> Spedizioni in carico </a>
			<a href = "corr_completate.php"> Spedizioni completate </a>
		</div>
		
		<div class="richiesta">
			<?php 
				sped();
			?>
		</div>
		
	</body>
</html>

<?php
	function sped(){
		require("./connessione.php");
		//CASO1: richiesta in sospeso che verrà presa in carico da questo byteCourier
		if(isset($_POST['sospeso'])){
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
			
			while($i<$spedizioni->length && $occorrenza == 0) {
				$spedizione = $spedizioni->item($i);
			
				$id_spedizione = $spedizione->firstChild;
				$id_spedizioneY = $id_spedizione->textContent;
				
				if($id_spedizioneY == $_POST['sospeso']) {
					$id_spedizioneX = $id_spedizione->textContent;
					
					$spedizione->setAttribute('stato', "In carico");
					
					$byteCourier1 = $id_spedizione->nextSibling;
					$byteCourier1X = $byteCourier1->textContent;
			
					$byteCourier2 = $byteCourier1->nextSibling;
					$byteCourier2X = $byteCourier2->textContent;
					
					$destinatario = $byteCourier2->nextSibling;
						
					$n_colli = $destinatario->nextSibling;
					
					$tipo_collo = $n_colli->nextSibling;
						
					$onere = $tipo_collo->nextSibling;
					
					$ritiro= $onere->nextSibling;
					
					//se è previsto il ritiro da casa e non è stato assegnato il byteCourier1
					if(isset($ritiro->firstChild) && $byteCourier1->textContent == "") {
						$byteCourier1->textContent = $_SESSION['username'];
						$sql = " UPDATE $user_table_name
								SET stato = \"busy\"
								WHERE username = '{$_SESSION['username']}'
								";
			
						if(!$resultQ = mysqli_query($connection, $sql)){
							printf("<p>Si è verificato un errore!</p>");
							exit();
						}
					}
					//se bisogna assegnare il byteCourier2 
					else {
						$byteCourier2->textContent = $_SESSION['username'];
						$sql = " UPDATE $user_table_name
								SET stato = \"busy\"
								WHERE username = '{$_SESSION['username']}'
								";
			
						if(!$resultQ = mysqli_query($connection, $sql)){
							printf("<p>Si è verificato un errore!</p>");
							exit();
						}
					}
					$occorrenza = 1;
					$doc->save('XML/spedizioni.xml');
					
					
				}
				$i+=1;
			}	
			echo "<h2> Hai preso in carico la spedizione ".$_POST['sospeso']."</h2>";
			
			stampa($_POST['sospeso']);
			stampa_commenti($_POST['sospeso']);
		}
		
		
		//CASO2: spedizione in carico e il corriere può aggiornare gli stati di avanzamento
		if(isset($_POST['carico'])){
			stampa($_POST['carico']);
			stampa_commenti($_POST['carico']);
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo){
				$xmlSpedizione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlSpedizione);
			$root = $doc->documentElement;
			$spedizioni = $root->childNodes;
			
			$i = 0;
			$occorrenza = 0;
			
			while($i<$spedizioni->length && $occorrenza == 0) {
				$spedizione = $spedizioni->item($i);
			
				$id_spedizione = $spedizione->firstChild;
				$id_spedizioneY = $id_spedizione->textContent;
				
				if($id_spedizioneY == $_POST['carico']) {
					$id_spedizioneX = $id_spedizione->textContent;
					
					$byteCourier1 = $id_spedizione->nextSibling;
					$byteCourier1X = $byteCourier1->textContent;
			
					$byteCourier2 = $byteCourier1->nextSibling;
					$byteCourier2X = $byteCourier2->textContent;
			
					$occorrenza = 1;
				}
				$i+=1;
			}
			
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
				
				if($id_sped == $_POST['carico']) {
					$stato_avanz = $commento->firstChild; 
					$stato_avanzX = $stato_avanz->textContent;
				}
			}
			
			$form = "<div class=\"interazione\"><h2>Aggiorna la spedizione</h2><form action=\"aggiornamento.php\" method=\"post\">";
			//se questo bc sta svolgendo la prima fase
			if($byteCourier1X == $_SESSION['username'] && empty($byteCourier2X)){ 
				if(!isset($stato_avanz)){
					$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
					$form.= "<option selected=\"selected\" value=\"Transito verso cliente per il ritiro\"> Transito verso cliente per il ritiro </option>";
					$form.= "</select>";
				}else{
					switch($stato_avanzX){
						case "Transito verso cliente per il ritiro":
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Pacco ritirato\"> Pacco ritirato </option>";
							$form.= "<option value=\"Problemi nel ritiro\"> Problemi nel ritiro </option>";
							$form.= "</select>";
						break;
						case "Pacco ritirato":
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Transito verso centro\"> Transito verso centro </option>";
							$form.= "</select>";
						break;
						case "Transito verso centro":
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Pacco consegnato in centrale\"> Pacco consegnato in centrale </option>";
							$form.= "</select>";
						break;
						default:
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Transito verso cliente per il ritiro\"> Transito verso cliente per il ritiro </option>";
							$form.= "<option value=\"Problemi nel ritiro\"> Problemi nel ritiro </option>";
							$form.= "</select>";
						break;
					}
				}
			}
				
			//se questo corriere è il secondo corriere
			if($byteCourier2X == $_SESSION['username']){
				if(!isset($stato_avanz) || $stato_avanzX == "Problemi nella consegna" || $stato_avanzX == "Risposta"){
					$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
					$form.= "<option selected=\"selected\" value=\"Ritiro pacco dal centro effettuato\"> Ritiro pacco dal centro effettuato </option>";
					$form.= "</select>";
				}else{
					switch($stato_avanzX){
						case "Pacco consegnato in centrale":
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Ritiro pacco dal centro effettuato\"> Ritiro pacco dal centro effettuato </option>";
							$form.= "</select>";
						break;
						case "Ritiro pacco dal centro effettuato":
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Transito verso destinatario\"> Transito verso destinatario </option>";
							$form.= "</select>";
						break;
						case "Transito verso destinatario":
							$form.= "Stato avanzamento: <select name=\"stato_avanz\">";
							$form.= "<option selected=\"selected\" value=\"Consegna effettuata\"> Consegna effettuata </option>";
							$form.= "<option value=\"Problemi nella consegna\"> Problemi nella consegna </option>";
							$form.= "</select>";
						break;
					}
				}
			}
			$form.= "<br />Lascia un commento: <input type=\"text\" name=\"descrizione\" size=\"40\" />";
			$form.= "<input class=\"ok\" type =\"submit\" name =\"invio\" value =\"".$_POST['carico']."\" title=\"Invia aggiornamento\" />";
			$form.= "</form></div>";
			
			echo $form;
		}
		
		
		//CASO3 spedizione completata
		if(isset($_POST['completo'])){
			stampa($_POST['completo']);
			stampa_commenti($_POST['completo']);
			$xmlValutazione = "";
			foreach(file("XML/valutazioni.xml") as $nodo){
				$xmlValutazione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlValutazione);
			$root = $doc->documentElement;
			$valutazioni = $root->childNodes;
			
			$occorrenza = 0;
			$i = 0;
			while($i<$valutazioni->length && $occorrenza == 0){
				$valutazione = $valutazioni->item($i);
				
				$id_sped = $valutazione->getAttribute("id_spedizione");
				
				if($id_sped == $_POST['completo']){
					$occorrenza = 1;
					
					$username = $valutazione->firstChild;
					$usernameX = $username->textContent;
					
					$soddisfazione = $username->nextSibling;
					$soddisfazioneX = $soddisfazione->textContent;
					
					$rapidita = $valutazione->lastChild;
					$rapiditaX = $rapidita->textContent;
				}
				$i+=1;
			}
			
			if($occorrenza == 1){
				$tot = $soddisfazioneX*(3/5) + $rapiditaX*(2/5);
				
				$voto = "<div class=\"valutazioni\">";
				$voto.= "<div class=\"valutazione\"><div class=\"first\"><h2>Valutazione spedizione n° ".$_POST['completo']."</h2></div>";
				$voto.= "<div class=\"username\"><p>Username: ".$usernameX."</p></div>";
				$voto.= "<div class=\"voto\"><p>Soddisfazione: ".$soddisfazioneX."<br />Rapidit&agrave;: ".$rapiditaX."</p></div>";
				$voto.= "<div class=\"tot\"><p>Totale: ".$tot."</p></div>";
				$voto.= "</div>";
				$voto.= "</div>";
			
				echo $voto;
			}
			
		}		
	}
	
	function stampa($id_sped){
		require("./connessione.php");
		$xmlSpedizione = "";
		foreach( file("XML/spedizioni.xml") as $nodo1){
			$xmlSpedizione.= trim($nodo1);
		}
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlSpedizione);
		$root1 = $doc1->documentElement;
		$spedizioni = $root1->childNodes;
		$i=0;
		$trovato = 0;
		while($i<$spedizioni->length && $trovato == 0){
			$spedizione = $spedizioni->item($i);
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			if($id_spedizioneY == $id_sped){
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
				
				//accedo al file tipologie.xml
				$xmlPacchetto = "";
				foreach(file("XML/tipologie.xml") as $nodo2){
					$xmlPacchetto.= trim($nodo2);
				}
				$doc2 = new DOMDocument();
				$doc2->loadXML($xmlPacchetto);
				$root2 = $doc2->documentElement;
				$pacchetti = $root2->childNodes;
				$j=0;
				$trovato1 = 0;
				while($j<$pacchetti->length && $trovato1 == 0){
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
						
						$trovato1 = 1;
					}
					$j+=1;
				}
				$trovato = 1;
			}
			$i+=1;
		}
		
		$sql = "SELECT * 
				FROM $user_table_name U
				WHERE U.username = \"$usernameX\"
				";
				
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p> Si &egrave; verificato un errore </p>";
			exit();
		}
		
		$row = mysqli_fetch_array($resultQ);
		
		if($statoX == "In carico")
			$sped = "<div class=\"spedizione\" style=\"background-color: rgba(255, 215, 0,.5);\">";
		else if($statoX == "Consegnato")
			$sped = "<div class=\"spedizione\" style=\"background-color: rgba(50, 205, 50,.5);\">";
			
		$sped.= "<p><strong>CODICE SPEDIZIONE ".$id_spedizioneX."</strong></p>";
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
								Nome: ".$nomeY."<br />Descrizione:<span class=\"wrap\">".$descrizioneX."</span>Tempo di consegna: ".$tempo_consX."
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
			$sped.= 		"<tr class=\"importante\"><th>ByteCourier 1</th><td colspan=\"2\">".$byteCourier1X."</td></tr>";
		}
		else {
			$sped.= 		"<tr class=\"importante\"><th>ByteCourier 1</th><td colspan=\"2\"> Nessuno </td></tr>";		
		}
		if($byteCourier2X != "") {
			$sped.= 		"<tr class=\"importante\"><th>ByteCourier 2</th><td colspan=\"2\">".$byteCourier2X."</td></tr>";
		}
		else {
			$sped.= 		"<tr class=\"importante\"><th>ByteCourier 2</th><td colspan=\"2\"> Nessuno </td></tr>";		
		}
		
		$sped.= 		"<tr><td>Importo totale:</td><td colspan=\"2\">".$prezzoX."&euro;</td></tr>";
	
		$sped.= "	</tbody>
				 </table>
				 </div>";
		
		
		echo $sped;
	}
	
	function stampa_commenti($id_spedi){
		require("./connessione.php");
		$xmlCommento = "";
		foreach(file("XML/commenti.xml") as $nodo2){
			$xmlCommento.= trim($nodo2);
		}
		$doc2 = new DOMDocument();
		$doc2->loadXML($xmlCommento);
		$root2 = $doc2->documentElement;
		$commenti = $root2->childNodes;
		
		$interazioni = "<h2> Commenti associati alla spedizione </h2>";
		$form = "";
		$flag = 0;
		
		for($k=0; $k<$commenti->length; $k++){
			$commento = $commenti->item($k);
			
			$id_sped = $commento->getAttribute('id_spedizione');
			
			if($id_sped == $id_spedi) {
				$user = $commento->getAttribute('username'); 
				
				$stato_avanz = $commento->firstChild; 
				if(isset($stato_avanz)) {
					$stato_avanzX = $stato_avanz->textContent;
				}
				
				$contenuto = $stato_avanz->nextSibling;
				if(isset($contenuto)) {
					$contenutoX = $contenuto->textContent;	
				}
				
				$timestamp = $commento->lastChild;
				$timestampX = $timestamp->textContent; 	
				
				$sql = "SELECT * FROM $user_table_name WHERE username = \"".$user."\"";
				if(!$resultQ = mysqli_query($connection, $sql)) {
					echo "<p> Si &egrave; verificato un errore </p>";
					exit();
				}
				
				$row = mysqli_fetch_array($resultQ);
				
				if($row['tipo_utente'] == "bc") {
					$interazioni.= "<div class=\"commento\"><div class=\"prima\"><h4>ByteCourier</h4></div>\n<div class=\"seconda\"><h3>".$stato_avanzX."</h3></div>";
					$interazioni.= "<div class=\"contenuto\"><p>".$contenutoX."</p></div>";
					$interazioni.= "<div class=\"time\"><p>".$timestampX."</p></div>";
					$interazioni.= "</div>";
					$flag = 1;
				}
				
			}
		}	
		if($flag == 0){
			$interazioni.= "<p><em>Non ci sono commenti per questa spedizione</em></p>";
		}

		echo $interazioni;
	}	
?>