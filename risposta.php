<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once("./connessione.php");

$msg = "";

session_start();
if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

echo '<?xml version="1.0" encoding="UTF-8"?>';

?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Gestione richiesta - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_cli.css" />
	</head>
	<body class="cliente">
		<div class = "logout">
			<a href = "logout_cli.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "home_cli.php"> <img src = "immagini/logo.png" alt = "logo" title = "Home" /> </a>
			<a href = "richiesta.php"> Richiedi spedizione </a>
			<a href = "ricerca.php"> Gestisci spedizione </a>
			<a href = "area_clienti.php"> Area Clienti </a>
		</div>
		
		<div class="ricevuto">
			<?php 
				if(isset($_POST['accetta'])){
					//scandisco i commenti associati a questa spedizione per recuperare la tipologia di sped proposta dal gestore
					$xmlCommento = "";
					foreach(file("XML/commenti.xml") as $nodo1){
						$xmlCommento.= trim($nodo1);
					}
					$doc1 = new DOMDocument();
					$doc1->loadXML($xmlCommento);
					$root1 = $doc1->documentElement;
					$commenti = $root1->childNodes;
					$j=0; 
					$trovato = 0;
					while($j<$commenti->length && $trovato==0){
						$commento = $commenti->item($j);
						$id_sped = $commento->getAttribute('id_spedizione');
						
						if($id_sped == $_POST['accetta']) {
							
							$stato_avanz = $commento->firstChild; 

							$contenuto = $stato_avanz->nextSibling;
							$contenutoX = $contenuto->textContent;
							
							$trovato = 1;
						} 
						$j+=1;
					}
					
					
					
					
					
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
						
						if($id_spedizioneY == $_POST['accetta']) {
							$id_spedizioneX = $id_spedizione->textContent;
							
							
							$tipologiaX = $spedizione->setAttribute('tipologia', "{$contenutoX}");
							
							$occorrenza = 1;
							$doc->save('XML/spedizioni.xml');
						}
						
						$i+=1;
					}
					echo "<h2> Hai accettato la proposta di cambio tipologia di spedizione con successo!</h2>";
					
					//genero un commento di tipo risposta
					$xmlCommento = "";
					foreach( file("XML/commenti.xml") as $nodo2){
						$xmlCommento.= trim($nodo2);
					}
					$doc2 = new DOMDocument();
					$doc2->loadXML($xmlCommento);
					$root2 = $doc2->documentElement;
					
					$commento = $doc2->createElement("commento");
					
					$id_spedizione1 = $doc2->createAttribute("id_spedizione");
					$id_spedizione1->value = "{$_POST['accetta']}";
					
					$username = $doc2->createAttribute("username");
					$username->value = "{$_SESSION['username']}";
					
					
					$root2->appendChild($commento);
					$commento->appendChild($id_spedizione1);
					$commento->appendChild($username);

				
					$stato_avanz = $doc2->createElement("stato_avanz", "Risposta");
					$commento->appendChild($stato_avanz);

					$descrizione = $doc2->createElement("descrizione", "Proposta accettata");
					$commento->appendChild($descrizione);
					
					$data = date("Y-m-d H:i:s");
					
					$timestamp= $doc2->createElement("timestamp", $data);
					$commento->appendChild($timestamp);

					$doc2->save('XML/commenti.xml');
				}
				
				else if(isset($_POST['rifiuta'])){
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
						
						if($id_spedizioneY == $_POST['rifiuta']) {
							$id_spedizioneX = $id_spedizione->textContent;
							
							$tipologiaX = $spedizione->getAttribute('tipologia');
							
							$spedizione->setAttribute('stato', "Rifiutato");
							
							
							$occorrenza = 1;
							$doc->save('XML/spedizioni.xml');
						}
						
						$i+=1;
					}
					echo "<h2> Proposta rifiutata con successo </h2>";
					
					$xmlCommento = "";
					foreach( file("XML/commenti.xml") as $nodo1){
						$xmlCommento.= trim($nodo1);
					}
					$doc1 = new DOMDocument();
					$doc1->loadXML($xmlCommento);
					$root1 = $doc1->documentElement;
					
					$commento = $doc1->createElement("commento");
					
					$id_spedizione1 = $doc1->createAttribute("id_spedizione");
					$id_spedizione1->value = "{$_POST['rifiuta']}";
					
					$username = $doc1->createAttribute("username");
					$username->value = "{$_SESSION['username']}";
					
					
					$root1->appendChild($commento);
					$commento->appendChild($id_spedizione1);
					$commento->appendChild($username);

				
					$stato_avanz = $doc1->createElement("stato_avanz", "Rifiutato");
					$commento->appendChild($stato_avanz);

					$descrizione = $doc1->createElement("descrizione", "Porposta rifiutata");
					$commento->appendChild($descrizione);
					
					$data = date("Y-m-d H:i:s");
					
					$timestamp= $doc1->createElement("timestamp", $data);
					$commento->appendChild($timestamp);

					$doc1->save('XML/commenti.xml');
					
					
				}
					
			?>
			<a href="home_cli.php"> Torna alla home </a>
		</div>
	</body>
</html>