<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title>ByteCourier2</title>
		<link rel = "stylesheet" type = "text/css" href = "stile_team.css" />
	</head>

	<body class="index">
		<p class="sopra">
			<a href="login_team.php" title = "Accedi come personale">Login team</a>
		</p>
		
		<p class="sopra">
			<a href="login_cli.php" title = "Accedi come cliente">Login cliente</a>
		</p>
		
		<div class = "logo1">
			<img src = "immagini/logo_large.png" alt = "logo" />
		</div>
		
		<div class="titolo">
			<h1> Le soluzioni BC2 </h1>
			<p> Spedisci online con i migliori corrieri. Risparmia fino a un 70%. Invia in Italia al miglior prezzo. </p>
			<h2>Scopri le nostre offerte!</h2>
		</div>
		
		<div class = "pacchetti">
			<?php 
				$pacchetti = pacchetti();
				echo $pacchetti;
			?>
		</div>
	</body>
</html>
<?php
	function pacchetti(){
		$xmlPacchetto = "";
		foreach( file("XML/tipologie.xml") as $nodo){
			$xmlPacchetto .= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlPacchetto);
		$root = $doc->documentElement;
		$pacchetti = $root->childNodes;
		$offerte = "";
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
			
			if($prezzoX && !$stato){
				$offerte.= "<div class=\"pacchetto\">
								<h1>{$nomeX}</h1>
								<p class = \"descrizione\"><span style=\"color:maroon;\">Descrizione</span>: {$descrizioneX}</p>
								<p><span style=\"color:maroon;\">Tempo di consegna</span>: {$tempo_consX}<hr />
								<span style=\"color:maroon; margin-left: 5%;\">Prezzo</span>: {$prezzoX} &euro;</p>
							</div>";
			}
		}
		return $offerte;
	}
?>