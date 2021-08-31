<?php
	error_reporting(E_ALL &~E_NOTICE);
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title>Eliminazione spedizione - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
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
	</body>
</html>



<?php

	if(isset($_POST['rifiuta'])) {
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
		echo "<div class=\"titolo\"><h2> La spedizione n°".$_POST['rifiuta']." &egrave; stata rifiutata con successo</h2></div>";
		
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

		$descrizione = $doc1->createElement("descrizione", "Richiesta di spedizione rifiutata");
		$commento->appendChild($descrizione);
		
		$data = date("Y-m-d H:i:s");
		
		$timestamp= $doc1->createElement("timestamp", $data);
		$commento->appendChild($timestamp);

		$doc1->save('XML/commenti.xml');
	}
		
		

?>