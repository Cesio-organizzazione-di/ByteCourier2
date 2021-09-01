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
		<link rel = "stylesheet" type = "text/css" href = "stile_adm.css" />
	</head>
	<body class = "admin">
		<div class = "logout">
			<a href = "logout_team.php"><img src = "immagini/logout.png" alt = "Logout" /> Logout </a>
		</div>
		
		<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		
		<div class = "menu">
			<a href = "home_adm.php"> <img src = "immagini/logo_blu.png" alt = "logo" title = "Home" /> </a>
			<p> Gestisci tipologie di spedizione</p>
			<a href = "utenti.php"> Gestisci utenti </a>
			<a href = "inserisci.php"> Inserisci personale </a>
		</div>
		<div class = "titolo">
			<h1> Tipologie di spedizione </h1>
		</div>
		
		<div class = "link">
			<a href = "aggiungi.php"><img src = "immagini/add.png" alt = "Aggiungi" /><p>Aggiungi un'offerta</p></a>
		</div>
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
		$occorrenza = 0;
		for($i=0; $i<$pacchetti->length; $i++) {
			$pacchetto = $pacchetti->item($i);
			
			$id_pacchetto = $pacchetto->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			$stato = $pacchetto->getAttribute('stato');
			
			if($stato != "Eliminato" || !$stato) {
				$occorrenza = 1;
				$nome = $id_pacchetto->nextSibling;
				$nomeX = $nome->textContent;
				
				$descrizione = $nome->nextSibling;
				$descrizioneX = $descrizione->textContent;
				
				$tempo_cons = $descrizione->nextSibling;
				$tempo_consX = $tempo_cons->textContent;
				
				$prezzo = $tempo_cons->nextSibling;
				
				if(!($prezzo->textContent)) {
					$offerte.= "<span class = \"pacchetto\">
									<h2> {$nomeX}</h2>
									<p><strong>Descrizione:</strong> {$descrizioneX}</p>
									<p><strong>Tempo di consegna:</strong> {$tempo_consX}</p>";
			 
					$offerte.= 		"<p> <strong>Prezzo:</strong> Non ancora assegnato </p>";
				
					$offerte.=		"<form action = \"modifica_p.php\" method = \"post\">
										<input class = \"modifica\" type=\"submit\" name=\"aggiorna\" value=\"{$id_pacchettoX}\" title = \"Modifica pacchetto\" /> 
										<input type=\"hidden\" name =\"nomeX\" value=\"{$nomeX}\" />
										<input type=\"hidden\" name = \"descrizioneX\" value=\"{$descrizioneX}\" />
										<input type=\"hidden\" name = \"tempo_consX\" value=\"{$tempo_consX}\" />
										<input class = \"elimina\" type=\"submit\" name=\"elimina\" value=\"{$id_pacchettoX}\" title = \"Elimina pacchetto\"/>
									</form>
								</span><hr />";
								
				}
				else {
					$offerte.= "<span class = \"pacchetto\">
									<h2> {$nomeX}</h2>
									<p><strong>Descrizione:</strong> {$descrizioneX}</p>
									<p><strong>Tempo di consegna:</strong> {$tempo_consX}</p>";
			 
					$offerte.= 		"<p> <strong>Prezzo:</strong> {$prezzo->textContent} &euro; </p>";
				
					$offerte.=		"<form action = \"modifica_p.php\" method = \"post\">
										<input class = \"modifica\" type=\"submit\" name=\"duplica\" value=\"{$id_pacchettoX}\" title = \"Modifica pacchetto\" /> 
										<input type=\"hidden\" name =\"nomeX\" value=\"{$nomeX}\" />
										<input type=\"hidden\" name = \"descrizioneX\" value=\"{$descrizioneX}\" />
										<input type=\"hidden\" name = \"tempo_consX\" value=\"{$tempo_consX}\" />
										<input class = \"elimina\" type=\"submit\" name=\"elimina\" value=\"{$id_pacchettoX}\" title = \"Elimina pacchetto\"/>
									</form>
								</span><hr />";
				}
			}else{
				$occorrenza = 1;
				$nome = $id_pacchetto->nextSibling;
				$nomeX = $nome->textContent;
				
				$descrizione = $nome->nextSibling;
				$descrizioneX = $descrizione->textContent;
				
				$tempo_cons = $descrizione->nextSibling;
				$tempo_consX = $tempo_cons->textContent;
				
				$prezzo = $tempo_cons->nextSibling;
				
				$offerte.= "<span class = \"pacchetto\">
								<h2> {$nomeX}</h2>
								<p><strong>Descrizione:</strong> {$descrizioneX}</p>
								<p><strong>Tempo di consegna:</strong> {$tempo_consX}</p>";
		 
				$offerte.= 		"<p> <strong>Prezzo:</strong> {$prezzo->textContent} &euro; </p>";
			
				$offerte.=		"<form action = \"modifica_p.php\" method = \"post\">
									<input class = \"ripristina\" type=\"submit\" name=\"ripristina\" value=\"{$id_pacchettoX}\" title = \"Ripristina pacchetto\" /> 
								</form>
							</span><hr />";
				
			}
		}
		$offerte.= "</div>";
		echo $offerte;
		
		if($occorrenza == 0) 
			echo "<p class = \"p\"><em>Non sono presenti tipologie di spedizione</em></p>";
	}
?>