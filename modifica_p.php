<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');
	
	function id() {
		$xmlPacchetto = "";
		$x = 1;
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
				$x = ($id_pacchettoX) + 1;
		}
		return $x;	
	}

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Modifica offerta - ByteCourier2 </title>
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
			<a href = "tipologie.php"> Gestisci tipologie di spedizione</a>
			<a href = "utenti.php"> Gestisci utenti </a>
			<a href = "inserisci.php"> Inserisci personale </a>
		</div>
		<div class = "titolo">
			<h1> Modifica la tipologia di spedizione </h1>
		</div>
		<?php 
			if(isset($_POST['aggiorna'])) {
		?>
		<div class = "form">
			<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post" >
				<p class = "info"><strong>Nome:</strong></p><p class = "input"><input type = "text" name = "nome" <?php echo "value=\"{$_POST['nomeX']}\"" ?> /></p>
				<p class = "info"><strong>Descrizione:</strong></p><p class = "input"><textarea name = "descrizione"><?php echo "{$_POST['descrizioneX']}" ?></textarea></p>
				<p class = "info"><strong>Tempo di consegna:</strong></p><p class = "input"> <input type = "text" name = "tempo_cons" <?php echo "value=\"{$_POST['tempo_consX']}\"" ?> /></p>
				<input class = "applica" type = "submit" name = "applica" value = "<?php echo $_POST['aggiorna']?>" title = "Applica modifiche" />
			</form>
			<p><em><strong>Nota bene:</strong> l'eventuale modifica del prezzo &egrave; a carico del gestore</em></p>
		</div>
		<p class = "p"><a href = "tipologie.php">Torna alle tipologie di spedizione</a></p>
		<?php 
			}
		?>
		
		<?php 
			if(isset($_POST['duplica'])) {
		?>
		<div class = "form">
			<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post" >
				<p class = "info"><strong>Nome:</strong></p><p class = "input"><input type = "text" name = "nome" <?php echo "value=\"{$_POST['nomeX']}\"" ?> /></p>
				<p class = "info"><strong>Descrizione:</strong></p><p class = "input"><textarea name = "descrizione"><?php echo "{$_POST['descrizioneX']}" ?></textarea></p>
				<p class = "info"><strong>Tempo di consegna:</strong></p><p class = "input"> <input type = "text" name = "tempo_cons" <?php echo "value=\"{$_POST['tempo_consX']}\"" ?> /></p>
				<input class = "applica" type = "submit" name = "doppione" value = "<?php echo $_POST['duplica']?>" title = "Applica modifiche" />
			</form>
			<p><em><strong>Nota bene:</strong> l'eventuale modifica del prezzo &egrave; a carico del gestore</em></p>
		</div>
		<p class = "p"><a href = "tipologie.php">Torna alle tipologie di spedizione</a></p>
		<?php 
			}
		?>
		
	</body>
</html>

<?php 
	//qui viene modificato il file xml con il contenuto della form
	if(isset($_POST['applica'])) {
		$xmlPacchetto = "";
		foreach( file("XML/tipologie.xml") as $nodo){
			$xmlPacchetto .= trim($nodo);
		}
		
		$doc = new DOMDocument();
		$doc->loadXML($xmlPacchetto);
		$root = $doc->documentElement;
		$pacchetti = $root->childNodes;
		$offerte = "";
		
		$i=0;
		$occorrenza=0;
		
		while($i<$pacchetti->length && $occorrenza == 0) {
			$pacchetto = $pacchetti->item($i);
			
			$id_pacchetto = $pacchetto->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			//vado a modificare i campi nell'xml che effettivamente sono stati inseriti nella form
			if($id_pacchettoX == $_POST['applica']) {			
				$nome = $id_pacchetto->nextSibling;
				
				if(($_POST['nome']) != "")
					$nome->textContent = "{$_POST['nome']}";
				
				$descrizione = $nome->nextSibling;
				
				if (($_POST['descrizione']) != "")
					$descrizione->textContent = "{$_POST['descrizione']}";
				
				$tempo_cons = $descrizione->nextSibling;
				if (($_POST['tempo_cons']) != "")
					$tempo_cons->textContent = "{$_POST['tempo_cons']}";
				
				$prezzo = $tempo_cons->nextSibling;
				
				$occorrenza = 1;
				$doc->save('XML/tipologie.xml');
			}
			$i+=1;
		}
		if($occorrenza == 1) {
			$offerta = "<div class = \"pacchetti\">";
			$offerta.="<h3>Tipologia di spedizione n° {$_POST['applica']}</h3>";
			$offerta.= "<span class = \"pacchetto\">
							<h2>{$nome->textContent}</h3>
							<p><strong>Descrizione:</strong> {$descrizione->textContent}</p>
							<p><strong>Tempo di consegna:</strong> {$tempo_cons->textContent}</p>";
							if($prezzo->textContent != "") {
			$offerta.=			"<p><strong>Prezzo:</strong> {$prezzo->textContent} &euro;</p>";
							}
			$offerta.=      "</span>";
			$offerta.= "</div>";
			echo $offerta;
			echo "<p class = \"p\"><a href = \"tipologie.php\">Torna alle tipologie di spedizione</a></p>";
		}
		else {
			echo "<h2>Si &egrave; verificato un problema</h2>";
		}
	}
	
	//si elimina la tipologia di spedizione selezionata
	if(isset($_POST['elimina'])) {
		$xmlEliminando = "";
		foreach( file("XML/tipologie.xml") as $nodo1){
			$xmlEliminando .= trim($nodo1);
		}
		
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlEliminando);
		$root1 = $doc1->documentElement;
		$pacchetti = $root1->childNodes;
		
		$j = 0;
		$occorrenza = 0;
		
		while($j<$pacchetti->length && $occorrenza == 0) {
			$eliminando = $pacchetti->item($j);
			
			$id_pacchetto = $eliminando->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			if($id_pacchettoX == $_POST['elimina']) 
				$occorrenza = 1;
			$j+=1;
		}
		
		if($occorrenza == 1) {
			$stato = $doc1->createAttribute("stato");
			$stato->value = ("Eliminato");
			$eliminando->appendChild($stato);
			
			$doc1->save('XML/tipologie.xml');
			echo "<h2 style = \"text-align: center; margin-top: 10%;\">La tipologia di spedizione n° ".$id_pacchettoX." &egrave; stata eliminata con successo!</h2>";
			echo "<p class = \"p\"><a href = \"tipologie.php\">Torna alle tipologie di spedizione</a></p>";
		}
		else 
			echo "<h2>Si &egrave; verificato un problema</h2>";
	}
	
	//se si modifica una tipologia già prezzata
	if(isset($_POST['doppione'])){
		$xmlPacchetto = "";
		foreach(file("XML/tipologie.xml") as $nodo){
			$xmlPacchetto.= trim($nodo);
		}
		
		$doc = new DOMDocument();
		$doc->loadXML($xmlPacchetto);
		$root = $doc->documentElement;
		
		$pacchetto = $doc->createElement("pacchetto");
		$root->appendChild($pacchetto);
		
		$y = id();
	
		$id_pacchetto = $doc->createElement("id_pacchetto", "$y");
		$pacchetto->appendChild($id_pacchetto);
		
		$nome = $doc->createElement("nome", "{$_POST['nome']}");
		$pacchetto->appendChild($nome);
		
		$descrizione = $doc->createElement("descrizione", "{$_POST['descrizione']}"); 
		$pacchetto->appendChild($descrizione);
		
		$tempo_cons = $doc->createElement("temp_cons", "{$_POST['tempo_cons']}"); 
		$pacchetto->appendChild($tempo_cons); 
		
		$prezzo = $doc->createElement("prezzo", "");
		$pacchetto->appendChild($prezzo); 
		
		$doc->save('XML/tipologie.xml');
		
		echo "<h2 class=\"titolo\">Modifica effettuata con successo </h2>";
		echo "<p class = \"p\"><a href = \"tipologie.php\">Torna alle tipologie di spedizione</a></p>";
	}
	
	//se si ripristina un pacchetto eliminato
	if(isset($_POST['ripristina'])){
		$xmlRipristinando = "";
		foreach(file("XML/tipologie.xml") as $nodo1){
			$xmlRipristinando.= trim($nodo1);
		}
		
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlRipristinando);
		$root1 = $doc1->documentElement;
		$pacchetti = $root1->childNodes;
		
		$j = 0;
		$occorrenza = 0;
		
		while($j<$pacchetti->length && $occorrenza == 0) {
			$ripristinando = $pacchetti->item($j);
			
			$id_pacchetto = $ripristinando->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			if($id_pacchettoX == $_POST['ripristina']) 
				$occorrenza = 1;
			$j+=1;
		}
		
		if($occorrenza == 1) {
			$ripristinando->setAttribute('stato', "");
			
			$doc1->save('XML/tipologie.xml');
			echo "<h2 style = \"text-align: center; margin-top: 10%;\">La tipologia di spedizione n° ".$id_pacchettoX." &egrave; stata ripristinata con successo!</h2>";
			echo "<p class = \"p\"><a href = \"tipologie.php\">Torna alle tipologie di spedizione</a></p>";
		}
		else 
			echo "<h2>Si &egrave; verificato un problema</h2>";
	}
?>