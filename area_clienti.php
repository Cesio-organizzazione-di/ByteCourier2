<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
	require_once("./connessione.php");
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Area clienti - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>
	<body class = "cliente">
		<div class = "logout">
			<a href = "logout_cli.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "richiesta.php"> Richiedi spedizione </a>
			<a href = "ricerca.php"> Gestisci spedizione </a>
			<p> Area Clienti </p>
			<a href = "home_cli.php"> <img src = "immagini/logo.png" alt = "logo" title = "Home" /> </a>
		</div>
		
		<div class = "titolo1">
			<h1> AREA CLIENTI </h1>
			<hr />
			<h3> Visualizza tutte le tue spedizioni, attuali e passate </h3>
		</div>
		
		<div class="destra">
			<form action = "mod.php" method = "POST">	
				<?php
					$sql = "SELECT * FROM $user_table_name WHERE username = '{$_SESSION['username']}'";
					
					if(!$resultQ = mysqli_query($connection, $sql)) {
						echo "<p>Questi dati non corrispondono a nessun account.<a href = \"login_cli.php\">Riprova</a> o <a href = \"signup.php\">registrati</a>.</p>";
						exit();
					}

					$row = mysqli_fetch_array($resultQ);
					
					if($row){
						if($row['tipo_utente'] == "cli_p") {
							$_SESSION['via'] = $row['via'];
							$_SESSION['n_civico'] = $row['n_civico'];
							$_SESSION['citta'] = $row['citta'];
							$_SESSION['CAP'] = $row['CAP'];
							$_SESSION['telefono'] = $row['telefono'];
				?>
							<h3> Il tuo profilo: </h3>
							<img src = "immagini/<?php echo $_SESSION['avatar']?>.png" alt="foto profilo" />
							<p> <strong>Username:</strong> <?php echo $_SESSION['username'] ?> </p>
							<p> <strong>Nome:</strong> <?php echo $_SESSION['nome'] ?> </p>
							<p> <strong>Cognome:</strong> <?php echo $_SESSION['cognome'] ?> </p>
							<p> <strong>Data di nascita:</strong> <?php echo $_SESSION['data_nascita'] ?> </p>
							<p> <strong>Email:</strong> <?php echo $_SESSION['email'] ?> </p>
							<p> <strong>Indirizzo:</strong> <?php echo $_SESSION['via']." ".$_SESSION['n_civico'].", ".$_SESSION['citta'].", ".$_SESSION['CAP']; ?> <br /> <input type =  "submit" name = "indirizzo" value = "Clicca qui" /> per modificare il tuo indirizzo</p>
							<p> <strong>Numero di telefono:</strong> <?php echo $_SESSION['telefono']; ?> <br /> <input type =  "submit" name = "telefono" value = "Clicca qui" /> per modificare il tuo numero di telefono </p>							
				<?php	
						}
						if($row['tipo_utente'] == "cli_az") {
							$_SESSION['via'] = $row['via'];
							$_SESSION['n_civico'] = $row['n_civico'];
							$_SESSION['citta'] = $row['citta'];
							$_SESSION['CAP'] = $row['CAP'];
							$_SESSION['telefono'] = $row['telefono'];
				?>
							<h3> Il tuo profilo: </h3>
							<img src = "immagini/<?php echo $_SESSION['avatar']?>.png" alt="foto profilo" />
							<p> <strong>Username:</strong> <?php echo $_SESSION['username'] ?> </p>
							<p> <strong>Nome attivit&agrave;:</strong> <?php echo $_SESSION['nome_attivita'] ?> </p>
							<p> <strong>Partita IVA:</strong> <?php echo $_SESSION['p_iva'] ?> </p>
							<p> <strong>Codice fiscale:</strong> <?php echo $_SESSION['cf'] ?> </p>
							<p> <strong>Settore di impiego:</strong> <?php echo $_SESSION['settore'] ?> </p>
							<p> <strong>Email:</strong> <?php echo $_SESSION['email'] ?> </p>
							<p> <strong>Indirizzo:</strong> <?php echo $_SESSION['via']." ".$_SESSION['n_civico'].", ".$_SESSION['citta'].", ".$_SESSION['CAP']; ?> <br /> <input type =  "submit" name = "indirizzo" value = "Clicca qui" /> per modificare il tuo indirizzo</p>
							<p> <strong>Numero di telefono:</strong> <?php echo $_SESSION['telefono']; ?> <br /> <input type =  "submit" name = "telefono" value = "Clicca qui" /> per modificare il tuo numero di telefono </p>
				<?php
						}
					}
				?>
			</form>
		</div>
		
		<div class="filtri">
			<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
				<input class="filtro" type = "submit" name = "invio" value = "Tutte le spedizioni" />
				<input class="filtro" type = "submit" name = "invio" value = "In sospeso" />
				<input class="filtro" type = "submit" name = "invio" value = "In carico" />
				<input class="filtro" type = "submit" name = "invio" value = "Consegnate" />
				<input class="filtro" type = "submit" name = "invio" value = "Rifiutate" />
			</form>
		</div>
		
		<div class="tutte_le_spedizioni">
			<?php spedizioni(); ?>
		</div>
	</body>
</html>

<?php
	function spedizioni(){
		if(empty($_POST['invio']) || $_POST['invio'] == "Tutte le spedizioni"){
			echo "<h1>Tutte le spedizioni</h1>";
			echo "<em> Clicca sul codice della spedizione per visualizzarne i dettagli </em>";
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo){
				$xmlSpedizione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlSpedizione);
			$root = $doc->documentElement;
			$spedizioni = $root->childNodes;
			$sped="";
			$flag = 0;
			
			for($i=0; $i<$spedizioni->length; $i++){
				$spedizione = $spedizioni->item($i);
				$username = $spedizione->getAttribute('username');
				
				if($username == $_SESSION['username']){
					$statoX = $spedizione->getAttribute('stato');
					$flag = 1;
			
					$id_spedizione = $spedizione->firstChild;
					$id_spedizioneX = $id_spedizione->textContent;

					switch($statoX){
						case "In sospeso":
							stampa_sospeso($id_spedizioneX);
						break;
						case "In carico": 
							stampa_carico($id_spedizioneX);
						break;
						case "Consegnato":
							stampa_consegnato($id_spedizioneX);
						break;
						case "Rifiutato":
							stampa_rifiutato($id_spedizioneX);
						break;					
					}
				}
			}
			if($flag == 0) {
				echo "<h3>Non hai ancora effettuato nessuna spedizione</h3>";
				echo "<p ><a class = \"link\" href = \"richiesta.php\">Richiedi una spedizione</a></p>";
			}
		}
		else if($_POST['invio'] == "In sospeso"){
			echo "<h1>Tutte le spedizioni in sospeso</h1>";
			echo "<em> Clicca sul codice della spedizione per visualizzarne i dettagli </em>";
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo){
				$xmlSpedizione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlSpedizione);
			$root = $doc->documentElement;
			$spedizioni = $root->childNodes;
			
			$occorrenza = 0;
			
			for($i=0; $i<$spedizioni->length; $i++){
                $spedizione = $spedizioni->item($i);

                $statoX = $spedizione->getAttribute('stato');
                $username = $spedizione->getAttribute('username');

                if($statoX == "In sospeso" && $username == $_SESSION['username']){
                    $occorrenza = 1;

                    $id_spedizione = $spedizione->firstChild;
                    $id_spedizioneX = $id_spedizione->textContent;

                    stampa_sospeso($id_spedizioneX);
                }
            }
			if($occorrenza == 0) {
				echo "<h3>Non hai spedizioni in sospeso</h3>";
				echo "<p><a class = \"link\" href = \"richiesta.php\">Richiedi una spedizione</a></p>";
			}
		}
		
		
		else if($_POST['invio'] == "In carico"){
			echo "<h1>Tutte le spedizioni in carico</h1>";
			echo "<em> Clicca sul codice della spedizione per visualizzarne i dettagli </em>";
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo){
				$xmlSpedizione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlSpedizione);
			$root = $doc->documentElement;
			$spedizioni = $root->childNodes;
			
			$occorrenza = 0;
			
			for($i=0; $i<$spedizioni->length; $i++){
                $spedizione = $spedizioni->item($i);

                $statoX = $spedizione->getAttribute('stato');
                $username = $spedizione->getAttribute('username');

                if($statoX == "In carico" && $username == $_SESSION['username']){
                    $occorrenza = 1;

                    $id_spedizione = $spedizione->firstChild;
                    $id_spedizioneX = $id_spedizione->textContent;

                    stampa_carico($id_spedizioneX);
                }
            }
			if($occorrenza == 0) {
				echo "<h3>Non hai spedizioni in carico</h3>";
				echo "<p><a class = \"link\" href = \"richiesta.php\">Richiedi una spedizione</a></p>";
			}
		}
		
		else if($_POST['invio'] == "Consegnate"){
			echo "<h1>Tutte le spedizioni consegnate</h1>";
			echo "<em> Clicca sul codice della spedizione per visualizzarne i dettagli </em>";
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo){
				$xmlSpedizione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlSpedizione);
			$root = $doc->documentElement;
			$spedizioni = $root->childNodes;
			
			$occorrenza = 0;
			
			for($i=0; $i<$spedizioni->length; $i++){
                $spedizione = $spedizioni->item($i);

                $statoX = $spedizione->getAttribute('stato');
                $username = $spedizione->getAttribute('username');

                if($statoX == "Consegnato" && $username == $_SESSION['username']){
                    $occorrenza = 1;

                    $id_spedizione = $spedizione->firstChild;
                    $id_spedizioneX = $id_spedizione->textContent;

                    stampa_consegnato($id_spedizioneX);
                }
            }
			if($occorrenza == 0) {
				echo "<h3>Non ci sono spedizioni consegnate</h3>";	
				echo "<p><a class = \"link\" href = \"richiesta.php\">Richiedi una spedizione</a></p>";
			}
		}
		
		else if($_POST['invio'] == "Rifiutate"){
			echo "<h1>Tutte le spedizioni rifiutate</h1>";
			echo "<em> Clicca sul codice della spedizione per visualizzarne i dettagli </em>";
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo){
				$xmlSpedizione.= trim($nodo);
			}
			$doc = new DOMDocument();
			$doc->loadXML($xmlSpedizione);
			$root = $doc->documentElement;
			$spedizioni = $root->childNodes;
			
			$occorrenza = 0;
			
			for($i=0; $i<$spedizioni->length; $i++){
                $spedizione = $spedizioni->item($i);

                $statoX = $spedizione->getAttribute('stato');
                $username = $spedizione->getAttribute('username');

                if($statoX == "Rifiutato" && $username == $_SESSION['username']){
                    $occorrenza = 1;

                    $id_spedizione = $spedizione->firstChild;
                    $id_spedizioneX = $id_spedizione->textContent;

                    stampa_rifiutato($id_spedizioneX);
                }
            }
			if($occorrenza == 0) {
				echo "<h3>Non ci sono spedizioni rifiutate</h3>";
				echo "<p><a class = \"link\" href = \"richiesta.php\">Richiedi una spedizione</a></p>";
			}
		}
	}


	function stampa_sospeso($id){
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
			if($id_spedizioneY == $id){
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
		
		$riepilogo = "<form action=\"ricerca.php\" method=\"post\">";
		$riepilogo.= "<div class = \"sopra\" style = \"background-color: rgba(192, 192, 192,0.7);\"><p><strong> CODICE SPEDIZIONE </strong>
					<input class = \"bottone\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" title = \"Visualizza dettagli\"/>
				 </p></form>";
		$riepilogo.= "<p style = \"margin-bottom: 0\">Stato dell'ordine: <strong>".$statoX."</strong></p></div>";
		$riepilogo.= "<table>\n<tbody>\n";
		$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
		$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

		if(isset($viaY)) {
			$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
			$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
		}
		else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
		
		$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
		$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
		$riepilogo.= "</tbody>\n<tfoot>\n<tr><th>Totale da pagare:</th><td>".$prezzoX." &euro;</td></tr>";
		$riepilogo.= "\n</tfoot>\n";
		$riepilogo.= "</table>";
		echo $riepilogo;
	}
	
	function stampa_carico($id){
		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo){
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
			if($id_spedizioneY == $id){
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
				foreach( file("XML/tipologie.xml") as $nodo1){
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
		
		$riepilogo = "<form action=\"ricerca.php\" method=\"post\">";
		$riepilogo.= "<div class = \"sopra\" style = \"background-color: rgba(255, 215, 0,0.5);\"><p><strong> CODICE SPEDIZIONE </strong>
					<input class = \"bottone\" style = \"background-color: rgb(255, 215, 0);\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" title = \"Visualizza dettagli\"/>
				 </p></form>";
		$riepilogo.= "<p style = \"margin-bottom: 0\">Stato dell'ordine: <strong>".$statoX."</strong></p></div>";
		$riepilogo.= "<table>\n<tbody>\n";
		$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
		$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

		if(isset($viaY)) {
			$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
			$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
		}
		else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
		
		$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
		$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
		$riepilogo.= "</tbody>\n<tfoot>\n<tr><th>Totale da pagare:</th><td>".$prezzoX." &euro;</td></tr>";
		$riepilogo.= "</tfoot>\n";
		$riepilogo.= "</table>";
		echo $riepilogo;
	}
	
	function stampa_consegnato($id){
		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo){
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
			if($id_spedizioneY == $id){
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
				foreach( file("XML/tipologie.xml") as $nodo1){
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
					
					$nome= $id_pacchetto->nextSibling;
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
		
		$riepilogo = "<form action=\"ricerca.php\" method=\"post\">";
		$riepilogo.= "<div class = \"sopra\" style = \"background-color: rgba(50, 205, 50,0.5);\"><p><strong> CODICE SPEDIZIONE </strong>
					<input class = \"bottone\" style = \"background-color: rgb(50, 205, 50);\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" title = \"Visualizza dettagli\" />
				 </p></form>";
		$riepilogo.= "<p style = \"margin-bottom: 0\">Stato dell'ordine: <strong>".$statoX."</strong></p></div>";
		$riepilogo.= "<table>\n<tbody>\n";
		$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
		$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

		if(isset($viaY)) {
			$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
			$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
		}
		else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
		
		$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
		$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
		$riepilogo.= "</tbody>\n<tfoot>\n<tr><th>Totale da pagare:</th><td>".$prezzoX." &euro;</td></tr>";
		$riepilogo.= "\n</tfoot>\n";
		$riepilogo.= "</table>";
		echo $riepilogo;
	}
	
	function stampa_rifiutato($id){
		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo){
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
			if($id_spedizioneY == $id){
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
				foreach( file("XML/tipologie.xml") as $nodo1){
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
		
		$riepilogo = "<form action=\"ricerca.php\" method=\"post\">";
		$riepilogo.= "<div class = \"sopra\" style = \"background-color: rgba(178, 34, 34, 0.5);\"><p><strong> CODICE SPEDIZIONE </strong>
					<input class = \"bottone\" style = \"background-color: rgb(178, 34, 34);\" type=\"submit\" name=\"id_spedizione\" value=\"".$id_spedizioneX."\" title = \"Visualizza dettagli\"/>
				 </p></form>";
		$riepilogo.= "<p style = \"margin-bottom: 0\">Stato dell'ordine: <strong>".$statoX."</strong></p></div>";
		$riepilogo.= "<table>\n<tbody>\n";
		$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
		$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

		if(isset($viaY)) {
			$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
			$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
		}
		else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
		
		$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
		$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
		$riepilogo.= "</tbody>\n<tfoot>\n<tr><th>Totale da pagare:</th><td>".$prezzoX." &euro;</td></tr>";
		$riepilogo.= "</tfoot>\n";
		$riepilogo.= "</table>";
		echo $riepilogo;
	}
?>