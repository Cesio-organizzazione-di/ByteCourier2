<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$msg = "";

session_start();
if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

echo '<?xml version="1.0" encoding="UTF-8"?>';

?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Tipologie di spedizione - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
		<div class = "logout">
			<a href = "logout_team.php"> <img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		<div class = "menu">
			<a href = "home_ges.php"> <img src = "immagini/logo1.png" alt = "logo" title = "Home" /> </a>
			<a href = "richieste.php"> Visualizza richieste di spedizione </a>
			<a href = "spedizioni.php"> Monitora spedizioni </a>
			<a href = "bytecouriers.php"> ByteCouriers </a>
			<p> Assegna prezzi </p>
		</div>
		
		<h1 class="titolo"> Tipologie di spedizione </h1>
		
		<?php 
			stampa_pacchetti();
		?>
	</body>
</html>

<?php
	function stampa_pacchetti(){
		$xmlPacchetto = "";
		foreach( file("XML/tipologie.xml") as $nodo){
			$xmlPacchetto .= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlPacchetto);
		$root = $doc->documentElement;
		$pacchetti = $root->childNodes;
		$offerte = "<div class = \"pacchetti\">";
		$trovato = 0;
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
			
			
			$prezzo = $tempo_cons->nextSibling;
			$prezzoX = $prezzo->textContent;

			if(!$stato){
				$offerte.= "<span class=\"pacchetto\">
								<h3 class=\"titolo\"> {$nomeX}</h3>
								<p><strong>Descrizione</strong>: {$descrizioneX}</p>
								<p><strong>Tempo di consegna</strong>: {$tempo_consX}</p>";
							if($prezzoX == ""){
				$offerte.= 		"<p><strong>Prezzo</strong>: Non ancora assegnato </p>";	
				$offerte.= 		"<form action = \"assegna_prezzo.php\" method = \"post\">
									<input class=\"mod\" type=\"submit\" name=\"assegna\" value=\"{$id_pacchettoX}\" title = \"Modifica prezzo\" />
								 </form>";
				$trovato = 1;
							}else{
				$offerte.= 		"<p><strong>Prezzo</strong>:".$prezzoX."</p>";
							}
				$offerte.= "</span><hr />";
				
			}
		}
		if($trovato == 0)
			$offerte.= "<h2 class=\"titolo\" style = \"height: 70px;\">Non ci sono tipologie di spedizione senza prezzo</h2>";
		$offerte.="</div>";
		echo $offerte;
	}
?>