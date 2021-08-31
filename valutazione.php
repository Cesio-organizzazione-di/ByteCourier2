<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("./connessione.php");

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Valutazione - ByteCourier2 </title>
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
		
	</body>
</html>

<?php
	if(isset($_POST['invio'])){
		//gestire caso in cui la valutazione sia già stata inserita
		if(empty($_POST['soddisfazione']) || empty($_POST['rapidita'])){
			header('Location: ricerca.php');
			exit();
		}
			
		$xmlValutazione = "";
		foreach(file("XML/valutazioni.xml") as $nodo1){
			$xmlValutazione.= trim($nodo1);
		}
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlValutazione);
		$root1 = $doc1->documentElement;
		
		$valutazione = $doc1->createElement("valutazione");
		
		$id_spedizione = $doc1->createAttribute("id_spedizione");
		$id_spedizione->value = "{$_POST['invio']}";
		
		$root1->appendChild($valutazione);
		$valutazione->appendChild($id_spedizione);
		
		$username = $doc1->createElement("username","{$_SESSION['username']}");
		$valutazione->appendChild($username);
		
		$soddisfazione = $doc1->createElement("soddisfazione", "{$_POST['soddisfazione']}");
		$valutazione->appendChild($soddisfazione);

		$rapidita = $doc1->createElement("rapidita", "{$_POST['rapidita']}");
		$valutazione->appendChild($rapidita);

		$doc1->save('XML/valutazioni.xml');
		
		$tot = $_POST['soddisfazione']*(3/5) + $_POST['rapidita']*(2/5); 
		
		$voto = "<div class = \"valutazioni\">";
		$voto.= "<h1>Valutazione pubblicata con successo</h1>";
		$voto.= "<div class=\"valutazione\" style = \"margin-top: 50px;\"><div class=\"first\"><h2>Valutazione spedizione n° ".$_POST['invio']."</h2></div>";
		$voto.= "<div class=\"username\"><p>Username: ".$_SESSION['username']."</p></div>";
		$voto.= "<div class=\"voto\"><p>Soddisfazione: ".$_POST['soddisfazione']."<br />Rapidit&agrave;: ".$_POST['rapidita']."</p></div>";
		$voto.= "<div class=\"tot\"><p>Totale: ".$tot."</p></div>";
		$voto.= "</div>";
		$voto.= "<a href=\"home_cli.php\">Torna alla home </a>";
		$voto.="</div>";
		
		
		echo $voto;
		
		//aggiorno la valutazione media di ogni corriere che ha provveduto all'ordine
		//scandisco il file spedizioni.xml per trovare questa spedizione
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
		
		while($i<$spedizioni->length && $occorrenza == 0){
			$spedizione = $spedizioni->item($i);
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			if($id_spedizioneY == $_POST['invio'] ){//di questa spedizione mi interessano i nomi dei due byteCourier
				$id_spedizioneX = $id_spedizione->textContent;
				
				$byteCourier1 = $id_spedizione->nextSibling;
				$byteCourier1X = $byteCourier1->textContent;
		
				$byteCourier2 = $byteCourier1->nextSibling;
				$byteCourier2X = $byteCourier2->textContent;
				
				$occorrenza = 1;
			}
			$i+=1;
		}
		
		if($occorrenza == 1){
			//scandisco le valutazioni, e per ogni valutazione individuo la spedizione determinando se la coppia (valutazione,spedizione) è associata a uno fra byteCourier1X o byteCourier2X
			$xmlValutazione = "";
			foreach(file("XML/valutazioni.xml") as $nodo2){
				$xmlValutazione.= trim($nodo2);
			}
			$doc2 = new DOMDocument();
			$doc2->loadXML($xmlValutazione);
			$root2 = $doc2->documentElement;
			$valutazioni = $root2->childNodes; 	
			$j=0; 
			$count = 0;
			$somme = 0;
			$media = 0;
			while($j<$valutazioni->length){ //scandisco tutte le valutazioni
				$valutazione = $valutazioni->item($j);
				$id_sped = $valutazione->getAttribute('id_spedizione');
				
				$xmlSpedizione = "";
				foreach(file("XML/spedizioni.xml") as $nodo){
					$xmlSpedizione.= trim($nodo);
				}
				$doc = new DOMDocument();
				$doc->loadXML($xmlSpedizione);
				$root = $doc->documentElement;
				$spedizioni = $root->childNodes;
				
				$i = 0;
				$trovato = 0;
				
				while($i<$spedizioni->length && $trovato == 0){//scandisco (per ogni valutazione) tutte le spedizioni
					$spedizione = $spedizioni->item($i);
				
					$id_spedizione = $spedizione->firstChild;
					$id_spedizioneY = $id_spedizione->textContent;
					
					$byteCourier1 = $id_spedizione->nextSibling;
					$byteCourier1X = $byteCourier1->textContent;
			
					$byteCourier2 = $byteCourier1->nextSibling;
					$byteCourier2X = $byteCourier2->textContent;
					
					if($id_spedizioneY == $id_sped){//inidividuo qella spedizione che ha lo stesso id_spedizione della valutazione
						
						$username = $valutazione->firstChild;

						$soddisfazione = $username->nextSibling;
						$soddisfazioneX = $soddisfazione->textContent;
						
						$rapidita = $valutazione->lastChild;
						$rapiditaX = $rapidita->textContent;
						
						$tot = $soddisfazioneX*(3/5) + $rapiditaX*(2/5);
						$somme += $tot;
						
						$count+=1;
						$trovato = 1;
					}
					$i+=1;
				}
				$j+=1;
			}
			
			$media = $somme / $count;
			
			if($byteCourier1X != ""){
				$sql1 = "UPDATE $user_table_name 
						SET valutazione = '{$media}'
						WHERE username = '{$byteCourier1X}'";
						
				if(!$resultQ = mysqli_query($connection, $sql1)){
					printf("<p>Si è verificato un errore!</p>");
					exit();
				}
			}
			
			if($byteCourier2X != ""){
				$sql2 = "UPDATE $user_table_name 
						SET valutazione = '{$media}'
						WHERE username = '{$byteCourier2X}'";
						
				if(!$resultQ = mysqli_query($connection, $sql2)){
					printf("<p>Si è verificato un errore!</p>");
					exit();
				}
			}
		}
	}
?>