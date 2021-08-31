<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$msg = "";
session_start();
if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

	if(isset($_POST['invio'])) {
		if(empty($_POST['nome']) || empty($_POST['via']) || empty($_POST['n_civico']) || empty($_POST['citta']) || empty($_POST['CAP']) || empty($_POST['telefono']) || empty($_POST['n_colli']) || empty($_POST['altezza']) || empty($_POST['lunghezza']) || empty($_POST['larghezza']) || empty($_POST['peso']) || empty($_POST['fragile']) || empty($_POST['ritiro']) || empty($_POST['onere']) || empty($_POST['tipologia'])) {
			if(isset($_POST['nome']))
				$_SESSION['nome1'] = $_POST['nome'];
			if(isset($_POST['via']))
				$_SESSION['via1'] = $_POST['via'];
			if(isset($_POST['n_civico']))
				$_SESSION['civico1'] = $_POST['n_civico'];
			if(isset($_POST['citta']))
				$_SESSION['citta1'] = $_POST['citta'];
			if(isset($_POST['CAP']))
				$_SESSION['CAP1'] = $_POST['CAP'];
			if(isset($_POST['telefono']))
				$_SESSION['telefono1'] = $_POST['telefono'];
			if(isset($_POST['altezza']))
				$_SESSION['altezza1'] = $_POST['altezza'];
			if(isset($_POST['larghezza']))
				$_SESSION['larghezza1'] = $_POST['larghezza'];
			if(isset($_POST['lunghezza']))
				$_SESSION['lunghezza1'] = $_POST['lunghezza'];
			if(isset($_POST['peso']))
				$_SESSION['peso1'] = $_POST['peso'];
			if(isset($_POST['n_colli']))
				$_SESSION['n_colli1'] = $_POST['n_colli'];
			if(isset($_POST['fragile']))
				$_SESSION['fragile1'] = $_POST['fragile'];
			
			
			$_SESSION['invio'] = $_POST['invio'];
			header("Location: richiesta.php");
			exit();
		}
	}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Riepilogo ordine - ByteCourier2</title>
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>
	<body class="cliente">
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class="corpo">
			<h1> Riepilogo del tuo ordine </h1>
			
			<?php 
				
				crea_richiesta();
				stampa();
			?>	
			
			<form action = "ordine.php" method = "post">
				<div class="bottoni">
					<input type = "submit" name = "accetta" value = "Conferma" /> 
					<input type = "submit" name = "rifiuta" value = "Annulla" />
				</div>
			</form>
		</div>
	</body>
</html>


<?php
	//funzione che genera un id unico per ogni spedizione
	function id() {
		$xmlSpedizione = "";
		$x = 1;
		foreach( file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		$spedizioni = $root->childNodes;
		for($i=0; $i<$spedizioni->length; $i++){
				$spedizione = $spedizioni->item($i);
				$id_spedizione = $spedizione->firstChild;
				$id_spedizioneX = $id_spedizione->textContent;
				$x = ($id_spedizioneX) + 1;
		}
	
		return $x;	
	}
	
	//inserisce la richiesta appena compilata nel file XML
	function crea_richiesta(){
		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		
		/*viene creato un elemento "spedizione" e viene aggiunto come figlio all'elemento radice.
		Cio' verra' fatto anche con gli altri elementi e sottoelementi di "spedizione".
        Laddove richiesto verra' specificato anche il valore che l'elemento deve assumere.		*/
		
		$spedizione = $doc->createElement("spedizione");
		
		$username = $doc->createAttribute("username");
		$username->value = "{$_SESSION['username']}";
		
		$tipologia = $doc->createAttribute("tipologia");
		$tipologia->value = "{$_POST['tipologia']}";
		
		$stato = $doc->createAttribute("stato");
		$stato->value = "In sospeso";
		
		$root->appendChild($spedizione);
		$spedizione->appendChild($username);
		$spedizione->appendChild($tipologia);
		$spedizione->appendChild($stato);
		
		$y = id();
	
		$id_spedizione = $doc->createElement("id_spedizione", "$y");
		$spedizione->appendChild($id_spedizione);

		$byteCourier1= $doc->createElement("byteCourier1", "");
		$spedizione->appendChild($byteCourier1);
		
		$byteCourier2= $doc->createElement("byteCourier2","");
		$spedizione->appendChild($byteCourier2);
		
		$destinatario = $doc->createElement("destinatario");
		$spedizione->appendChild($destinatario);
		
		$nome= $doc->createElement("nome", "{$_POST['nome']}");
		$destinatario->appendChild($nome);
		
		$indirizzo= $doc->createElement("indirizzo");
		$destinatario->appendChild($indirizzo);
		
		$via= $doc->createElement("via","{$_POST['via']}");
		$indirizzo->appendChild($via);
		
		$n_civico= $doc->createElement("n_civico","{$_POST['n_civico']}");
		$indirizzo->appendChild($n_civico);
		
		$citta= $doc->createElement("citta","{$_POST['citta']}");
		$indirizzo->appendChild($citta);
		
		$CAP= $doc->createElement("CAP","{$_POST['CAP']}");
		$indirizzo->appendChild($CAP);
		
		$telefono= $doc->createElement("telefono", "{$_POST['telefono']}");
		$destinatario->appendChild($telefono);
		
		$n_colli= $doc->createElement("n_colli","{$_POST['n_colli']}");
		$spedizione->appendChild($n_colli);

		$tipo_collo= $doc->createElement("tipo_collo");
		$spedizione->appendChild($tipo_collo);
		
		$dimensione= $doc->createElement("dimensione");
		$tipo_collo->appendChild($dimensione);
		
		$altezza= $doc->createElement("altezza","{$_POST['altezza']}");
		$dimensione->appendChild($altezza);
		
		$larghezza= $doc->createElement("larghezza","{$_POST['larghezza']}");
		$dimensione->appendChild($larghezza);
		
		$lunghezza= $doc->createElement("lunghezza","{$_POST['lunghezza']}");
		$dimensione->appendChild($lunghezza);
		
		$peso= $doc->createElement("peso", "{$_POST['peso']}");
		$tipo_collo->appendChild($peso);
		
		$fragile= $doc->createElement("fragile", "{$_POST['fragile']}");
		$tipo_collo->appendChild($fragile);
		
		$onere= $doc->createElement("onere","{$_POST['onere']}");
		$spedizione->appendChild($onere);
		
		$ritiro= $doc->createElement("ritiro");
		$spedizione->appendChild($ritiro);
		
		if($_POST['ritiro'] == "si") {
			
			$via= $doc->createElement("via","{$_SESSION['via']}");
			$ritiro->appendChild($via);
		
			$n_civico= $doc->createElement("n_civico","{$_SESSION['n_civico']}");
			$ritiro->appendChild($n_civico);
			
			$citta= $doc->createElement("citta","{$_SESSION['citta']}");
			$ritiro->appendChild($citta);
			
			$CAP= $doc->createElement("CAP","{$_SESSION['CAP']}");
			$ritiro->appendChild($CAP);
		}
		
		$auto= $doc->createElement("auto","no");
		$spedizione->appendChild($auto);

		//permette di salvare il documento in un file xml. In particolare, sovrascrivo il nuovo documento con la
		//spedizione aggiunta nel file xml iniziale.
		$doc->save('XML/spedizioni.xml');
	}
	
	//stampa la richiesta di spedizione
	function stampa() {
		//accedo all'ultimo figlio della radice "spedizioni"
		$xmlSpedizione = "";
		foreach( file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		$spedizioni = $root->childNodes;
		
		for($i=0; $i<$spedizioni->length; $i++){
			$spedizione = $spedizioni->item($i);
		}
		
		$id_spedizione = $spedizione->firstChild;
		$id_spedizioneX = $id_spedizione->textContent;
		
		$byteCourier1 = $id_spedizione->nextSibling;
		
		$byteCourier2 = $byteCourier1->nextSibling;
		
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
		
		if($_POST['ritiro'] == "si") {
			$ritiro= $onere->nextSibling;
				$via = $ritiro->firstChild;
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
		
		for($i=0; $i<$pacchetti->length; $i++){
			$pacchetto = $pacchetti->item($i);
			
			$id_pacchetto = $pacchetto->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			if($id_pacchettoX == $_POST['tipologia']) {
				$nome = $id_pacchetto->nextSibling;
				$nomeY = $nome->textContent;
				
				$descrizione = $nome->nextSibling;
				
				$tempo_cons = $descrizione->nextSibling;
				$tempo_consX = $tempo_cons->textContent;
				
				$prezzo = $pacchetto->lastChild;;
				$prezzoX = $prezzo->textContent;
			}
			$descrizione = $nome->nextSibling;
			
			$tempo_cons = $descrizione->nextSibling;
		
			$prezzo = $pacchetto->lastChild;
	
		}
		
		//stampa 
		$riepilogo = "<p style=\"color: firebrick; text-align: center;\"><strong> CODICE SPEDIZIONE: ".$id_spedizioneX."</strong></p>";
		$riepilogo.= "<table cellpadding = \"8\" cellspacing=\"0\" class=\"riepilogo\">\n<tbody valign=\"top\">\n";
		$riepilogo.= "<tr>\n<th>Destinatario</th>\n<th>Tipologia di spedizione</th>\n<th>Ritiro presso</th>\n</tr>";
		$riepilogo.= "<tr>\n<td>Nominativo: ".$nomeX."<br />Numero di telefono: ".$telefonoX."</td>\n<td rowspan = \"2\">Nome: ".$nomeY."<br />Tempo di consegna: ".$tempo_consX."</td>\n";

		if($_POST['ritiro'] == "si") {
			$riepilogo.= "<td rowspan = \"2\"><u>Domicilio del mittente</u><br />";
			$riepilogo.= "Via: ".$viaY." ".$n_civicoY."<br />";
			$riepilogo.= "Citt&agrave;: ".$cittaY."<br />CAP: ".$CAPY."</td></tr>\n";	
		}
		else $riepilogo.= "<td rowspan = \"2\">Centrale</td></tr>\n";
		
		$riepilogo.= "<tr><td><u>Indirizzo di spedizione</u><br />Via: ".$viaX." ".$n_civicoX."<br />";
		$riepilogo.= "Citt&agrave;: ".$cittaX."<br />CAP: ".$CAPX."</td></tr>\n";
		$riepilogo.= "<tr><th>Descrizione della merce</th>\n<th>Pagamento a carico di</th></tr>\n";
		$riepilogo.= "<tr><td>Numero colli: ".$n_colliX."<br />Altezza: ".$altezzaX." cm<br />Larghezza: ".$larghezzaX." cm<br />";
		$riepilogo.= "Lunghezza: ".$lunghezzaX." cm<br />Peso: ".$pesoX." kg<br />Fragile: ".$fragileX."</td>";
		$riepilogo.= "<td>".$onereX."</td></tr>";
		$riepilogo.= "</tbody>\n<tfoot>\n<tr><td>Totale da pagare:</td><td colspan=\"2\">".$prezzoX." &euro;</td></tr>\n</tfoot>\n";
		$riepilogo.= "</table>";
		
		echo $riepilogo;
		
		
	}	
?>