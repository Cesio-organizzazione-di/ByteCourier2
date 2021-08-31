<?php
	error_reporting(E_ALL &~E_NOTICE);
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
	function aggiorna_corriere(){
		require("./connessione.php");
		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo2){
			$xmlSpedizione.= trim($nodo2);
		}
		$doc2 = new DOMDocument();
		$doc2->loadXML($xmlSpedizione);
		$root2 = $doc2->documentElement;
		$spedizioni = $root2->childNodes;
		
		$count = 0;
		
		for($k=0; $k<$spedizioni->length; $k++) {
			$spedizione = $spedizioni->item($k);
			
			$stato = $spedizione->getAttribute("stato");
			if($stato == "In carico"){
		
				$id_spedizione = $spedizione->firstChild;
				
				$byteCourier1 = $id_spedizione->nextSibling;
				$byteCourier1X = $byteCourier1->textContent;
		
				$byteCourier2 = $byteCourier1->nextSibling;
				$byteCourier2X = $byteCourier2->textContent;
				
				if($byteCourier1X == $_SESSION['username'] && !isset($byteCourier2))
					$count+=1;
				
				if($byteCourier2X == $_SESSION['username'])
					$count+=1;
			}
		}
		
		if($count == 0){
			
			$sql = "UPDATE $user_table_name 
					SET stato = \"free\"
					WHERE username = '{$_SESSION['username']}'";
					
			if(!$resultQ = mysqli_query($connection, $sql)){
				printf("<p>Si è verificato un errore!</p>");
				exit();
			}
		}
	}
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title>Eliminazione spedizione - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_bc.css" />
	</head>
	<body class="bc">
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "home_bc.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "corr_in_sospeso.php"> Spedizioni in sospeso </a>
			<a href = "corr_in_carico.php"> Spedizioni in carico </a>
			<a href = "corr_completate.php"> Spedizioni completate </a>
		</div>
	</body>
</html>



<?php

	if(isset($_POST['invio'])) {
		//genero il commento corrispondente allo stato di avanzamento
		$xmlCommento = "";
		foreach( file("XML/commenti.xml") as $nodo1){
			$xmlCommento.= trim($nodo1);
		}
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlCommento);
		$root1 = $doc1->documentElement;
		
		$commento = $doc1->createElement("commento");
		
		$id_spedizione1 = $doc1->createAttribute("id_spedizione");
		$id_spedizione1->value = "{$_POST['invio']}";
		
		$username = $doc1->createAttribute("username");
		$username->value = "{$_SESSION['username']}";
		
		
		$root1->appendChild($commento);
		$commento->appendChild($id_spedizione1);
		$commento->appendChild($username);

	
		$stato_avanz = $doc1->createElement("stato_avanz", "{$_POST['stato_avanz']}");
		$commento->appendChild($stato_avanz);

		if(isset($_POST['descrizione'])){
			$descrizione = $doc1->createElement("descrizione", "{$_POST['descrizione']}");
		}else{
			$descrizione = $doc1->createElement("descrizione", "");
		}
		$commento->appendChild($descrizione);
		
		$data = date("Y-m-d H:i:s");
		
		$timestamp= $doc1->createElement("timestamp", $data);
		$commento->appendChild($timestamp);

		$doc1->save('XML/commenti.xml');
		
		//aggiorno lo stato della spedizione
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
			
			$id_pacchetto = $spedizione->getAttribute('tipologia');
			
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			
			if($id_spedizioneY == $_POST['invio']) {
				
				switch($_POST['stato_avanz']){
					case "Pacco consegnato in centrale":
						$spedizione->setAttribute('stato', "In sospeso");
					break;
					case "Consegna effettuata":
						$spedizione->setAttribute('stato', "Consegnato");
					break;
					case "Problemi nel ritiro":
						$spedizione->setAttribute('stato', "Rifiutato");
					break;
					case "Problemi nella consegna":
						$spedizione->setAttribute('stato', "In sospeso");
						$auto = $spedizione->lastChild;
						$auto->textContent = "no";
					break;
				}
				$occorrenza = 1;
				$doc->save('XML/spedizioni.xml');
				aggiorna_corriere();
			}
			$i+=1;
		}	
	}
	if($occorrenza == 1){
		$interazioni = "<div class=\"corpo\">";
		$interazioni.= "<h2>Aggiornamento effettuato con successo</h2>";
		$interazioni.= "<div class=\"commento\"><div class=\"prima\"><h4>ByteCourier</h4></div>\n<div class=\"seconda\"><h3>".$_POST['stato_avanz']."</h3></div>";
		$interazioni.= "<div class=\"contenuto\"><p>".$_POST['descrizione']."</p></div>";
		$interazioni.= "<div class=\"time\"><p>".$data."</p></div>";
		$interazioni.= "</div>";
		echo $interazioni;
	}
	else
		echo "<h2 style=\"margin-top: 15%\">Problemi nell'aggiornamento della spedizione</h2>";
?>

