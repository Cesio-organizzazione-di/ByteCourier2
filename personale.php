<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	require("./connessione.php");
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Gestisci utenti - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_adm.css" />
	</head>
	<body class = "admin">
		<div class = "logout">
			<a href = "logout_team.php"><img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu" id = "home">
			<a href = "home_adm.php"> <img src = "immagini/logo_blu.png" alt = "logo" title = "Home" /> </a>
			<a href = "tipologie.php"> Gestisci tipologie di spedizione</a>
			<a href = "utenti.php"> Gestisci utenti </a>
			<a href = "inserisci.php"> Inserisci personale </a>
		</div>
		<div class = "titolo">
			<h1> <?php echo $_POST['invio'] ?></h1>
		</div>
		
			<?php 
				personale();
			?>
		
		<?php 
			$sql = "SELECT * FROM $user_table_name WHERE username = \"".$_POST['invio']."\"";
		
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p><em>Errore durante l'accesso al database</p>";
			exit();
		}
		
		$row = mysqli_fetch_array($resultQ); 
		if($row['tipo_utente'] == "bc") {
		?>
		<div class = "tutte_le_spedizioni">
			<h2 class = "titolo"> Spedizioni associate a <?php echo $_POST['invio']?> </h2>
			<?php 
				spedizioni($_POST['invio']);
			?>
		</div>
		<p class="back"><a href="#home"><img src="immagini/up-arrow.png" class="tornasu" title="Torna su" /></a></p>
		<?php } ?>
		
		
	</body>
</html>
<?php
	function personale() {
		require("./connessione.php");
		//seleziono quel corriere dal database
		$sql = "SELECT * FROM $user_table_name WHERE username = \"".$_POST['invio']."\"";
		
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p><em>Errore durante l'accesso al database</p>";
			exit();
		}
		
		$row = mysqli_fetch_array($resultQ); 
		if($row['abilitato'] == 0) {
			$profilo = "<div class=\"corpo\" style=\"background-color: silver;\">";
			$profilo.= "<div class = \"info\"> Account disabilitato </div>";
		}
		else if($row['tipo_utente'] == "bc") {
			if($row['stato'] == "free") $profilo = "<div class = \"corpo\"style = \"background-color: rgba(50, 205, 50,0.8); color: white;\">";
			else $profilo = "<div class = \"corpo\"style = \"background-color: rgba(178, 34, 34,0.8); color: white;\">";
		}
		else if($row['tipo_utente'] == "ges") $profilo = "<div class = \"corpo\">";
			$profilo.= "<div class = \"info\"><strong>Nome: </strong>".$row['nome']." ".$row['cognome']."</div>";
			$profilo.= "<div class = \"info\"><strong>Data di nascita: </strong>".$row['data_nascita']."</div>";
			$profilo.= "<div class = \"indirizzo\"><u>Indirizzo:</u> <br />";
			$profilo.= "<strong>Via: </strong>".$row['via']." ".$row['n_civico']."<br />";
			$profilo.= "<strong>Citt&agrave;: </strong>".$row['citta'].", ";
			$profilo.= $row['CAP']."<br />";
			$profilo.= "</div>";
			$profilo.= "<div class = \"info\"><strong>Telefono: </strong>".$row['telefono']."</div>";
			$profilo.= "<div class = \"info\"><strong>E-mail: </strong>".$row['email']."</div>";
		
		if($row['tipo_utente'] == "bc") {
			$profilo.= "<div class = \"media\"><strong>Valutazione </strong>".$row['valutazione']."</div>";
		}
		

		if($row['abilitato'] == 0) {
			$profilo.= "<form action = \"abilita.php\" method = \"post\">";
			$profilo.= "<input class = \"abilita\" title = \"Abilita\" type = \"submit\" name = \"abilita\" value = \"{$_POST['invio']}\" />";
			$profilo.= "</form>";
		}
		else {
			$profilo.= "<form action = \"abilita.php\" method = \"post\">";
			$profilo.= "<input class = \"disabilita\" title = \"Disabilita\" type = \"submit\" name = \"disabilita\" value = \"{$_POST['invio']}\" />";
			$profilo.= "</form>";
		}
		$profilo.= "</div>";
		
		echo $profilo;
	}
	
	
	function spedizioni($username){
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
			
			if($statoX && ($bc1X == $username || $bc2X == $username)){
				
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
				//scandisco il file valutazioni.xml per la stampa della valutazione lasciata dal cliente
				$xmlValutazione = "";
				foreach(file("XML/valutazioni.xml") as $nodo2){
					$xmlValutazione.= trim($nodo2);
				}
				$doc2 = new DOMDocument();
				$doc2->loadXML($xmlValutazione);
				$root2 = $doc2->documentElement;
				$valutazioni = $root2->childNodes; 	
				$k=0; 
				$trovato = 0;
				while($k<$valutazioni->length && $trovato==0){
					$valutazione = $valutazioni->item($k);
					$id_sped = $valutazione->getAttribute('id_spedizione');
					
					if($id_sped == $id_spedizioneX) {
						
						$usernameZ = $valutazione->firstChild; 

						$soddisfazione = $usernameZ->nextSibling;
						$soddisfazioneX = $soddisfazione->textContent;
						
						$rapidita = $valutazione->lastChild;
						$rapiditaX = $rapidita->textContent;
						
						$tot = $soddisfazioneX*(3/5) + $rapiditaX*(2/5);
						
						$trovato = 1;
					}
					$k+=1;
				}
				
				
				if($statoX == "In sospeso") {
					$riepilogo = "<div class = \"sopra\" style = \"background-color: rgba(192, 192, 192,0.7);\"><p><strong> CODICE SPEDIZIONE </strong>".$id_spedizioneX."</p>";
				}
				else if ($statoX == "In carico") {
					$riepilogo = "<div class = \"sopra\" style = \"background-color: rgba(255, 215, 0,0.7);\"><p><strong> CODICE SPEDIZIONE </strong>".$id_spedizioneX."</p>";
				}
				else if ($statoX == "Consegnato") {
					$riepilogo = "<div class = \"sopra\" style = \"background-color: rgba(50, 205, 50,0.7);\"><p><strong> CODICE SPEDIZIONE </strong>".$id_spedizioneX."</p>";
				}
				else if ($statoX == "Rifiutato") {
					$riepilogo = "<div class = \"sopra\" style = \"background-color: rgba(178, 34, 34,0.7);\"><p><strong> CODICE SPEDIZIONE </strong>".$id_spedizioneX."</p>";
				}
				
				$riepilogo.= "<p style = \"margin-bottom: 0\">Stato dell'ordine: <strong>".$statoX."</strong></p>";
				$riepilogo.= "</div>";
				$riepilogo.= "<table>\n<tbody>\n";
				if($trovato == 1) {
					$riepilogo.= "<div class = \"valutazione\"><h4>Valutazione</h4>";
					$riepilogo.= "<p><u>Soddisfazione</u>: ".$soddisfazioneX."</p><p><u>Rapidit&agrave;</u>: ".$rapiditaX."</p>";
					$riepilogo.= "<p><strong>Totale:</strong> ".$tot."</p></div>";
				}
				$riepilogo.= "<tr><th>Descrizione della merce</th>\n<th>Pagamento a carico di</th>\n<th>byteCouriers</th></tr>\n";
				$riepilogo.= "<tr><td>Numero colli: ".$n_colliX."<br />Altezza: ".$altezzaX." cm<br />Larghezza: ".$larghezzaX." cm<br />";
				$riepilogo.= "Lunghezza: ".$lunghezzaX." cm<br />Peso: ".$pesoX." kg<br />Fragile: ".$fragileX."</td>";
				$riepilogo.= "<td>".$onereX."</td>";
				if($statoX == "In sospeso") {
					if(isset($via)){
					$riepilogo.= "<td>byteCourier1: Non ancora assegnato";
					}else{
						$riepilogo.= "<td>byteCourier1: Non previsto";
					}
					$riepilogo.= "<br />byteCourier2: Non ancora assegnato<br />";
					$riepilogo.= "<span><u>Modalit&agrave; di assegnazione:</u><br />";
					if($autoX == "si"){
						$riepilogo.= " Autoassegnazione da parte dei byteCouriers </span></td></tr>";
					}else{
						$riepilogo.= " Assegnazione da parte dei gestori </span></td></tr>";
					}
				}
				else if($statoX == "In carico") {
					if(isset($via)){
					$riepilogo.= "<td>byteCourier1: ".$byteCourier1X;
					}else{
						$riepilogo.= "<td>byteCourier1: Non previsto";
					}
					
					if($byteCourier2X == ""){
						$riepilogo.= "<br />byteCourier2: Non ancora assegnato<br />";
					}else{
						$riepilogo.= "<br />byteCourier2: ".$byteCourier2X."<br />";
					}
					$riepilogo.= "<span><u>Modalit&agrave; di assegnazione:</u><br />";
					if($autoX == "si"){
						$riepilogo.= " Autoassegnazione da parte dei byteCouriers </span></td></tr>";
					}else{
						$riepilogo.= " Assegnazione da parte dei gestori </span></td></tr>";
					}
				}
				else if($statoX == "Consegnato") {
					if(isset($via)){
					$riepilogo.= "<td>byteCourier1: ".$byteCourier1X;
					}else{
						$riepilogo.= "<td>byteCourier1: Non previsto";
					}
					$riepilogo.= "<br />byteCourier2: ".$byteCourier2X."<br />";
					$riepilogo.= "<span><u>Modalit&agrave; di assegnazione:</u><br />";
					if($autoX == "si"){
						$riepilogo.= " Autoassegnazione da parte dei byteCouriers </span></td></tr>";
					}else{
						$riepilogo.= " Assegnazione da parte dei gestori </span></td></tr>";
					}
				}
				else if($statoX == "Rifiutato") {
					if(isset($via)){
					$riepilogo.= "<td>byteCourier1: ".$byteCourier1X;
					}else{
						$riepilogo.= "<td>byteCourier1: Non previsto";
					}
					
					if($byteCourier2X == ""){
						$riepilogo.= "<br />byteCourier2: Non ancora assegnato<br />";
					}else{
						$riepilogo.= "<br />byteCourier2: ".$byteCourier2X."<br />";
					}
					$riepilogo.= "<span><u>Modalit&agrave; di assegnazione:</u><br />";
					if($autoX == "si"){
						$riepilogo.= " Autoassegnazione da parte dei byteCouriers </span></td></tr>";
					}else{
						$riepilogo.= " Assegnazione da parte dei gestori </span></td></tr>";
					}
				}
				$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
				$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."</td>\n";
				
			

				if(isset($viaY)) {
					$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
					$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
					$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td>\n";	
				}
				else $riepilogo.= "<td rowspan = \"2\">Centrale</td>\n";
				
				$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
				$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
				$riepilogo.= "</tbody>\n<tfoot>\n<tr><th>Totale da pagare:</th><td>".$prezzoX." &euro;</td></tr>";
				$riepilogo.= "\n</tfoot>\n";
				$riepilogo.= "</table>";
				echo $riepilogo;
			}
		}
		if($occorrenza == 0) {
				echo "<h3 style = \"font-weight: normal;\"> Non ci sono spedizioni associate a questo cliente </h3>";
		}
	}
?>