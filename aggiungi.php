<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
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
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Aggiungi offerta - ByteCourier2 </title>
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
			<h1> Aggiungi una tipologia di spedizione </h1>
		</div>
		
		<div class = "form">
			<?php if(isset($_POST['aggiungi']))
				if(($_POST['nome'])=="" || ($_POST['descrizione'])=="" || ($_POST['tempo_cons'])== "") 
					echo "<p><em>Completa tutti i campi per proseguire</em></p>";
				else if(($_POST['nome'])!="" && ($_POST['descrizione'])!="" && ($_POST['tempo_cons'])!= ""){
					aggiungi();
				}
			?>
			<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post" >
				<p class = "info"><strong>Nome:</strong></p><p class = "input"><input type = "text" name = "nome" <?php if(isset($_POST['nome'])) echo "value=\"{$_POST['nome']}\"" ?> /></p>
				<p class = "info"><strong>Descrizione:</strong></p><p class = "input"><textarea name = "descrizione"><?php if(isset($_POST['descrizione']))  echo "{$_POST['descrizione']}" ?></textarea></p>
				<p class = "info"><strong>Tempo di consegna:</strong></p><p class = "input"> <input type = "text" name = "tempo_cons" <?php if(isset($_POST['tempo_cons'])) echo "value=\"{$_POST['tempo_cons']}\"" ?> /></p>
				<div class = "bottoni">
					<input type = "submit" name = "aggiungi" value = "Aggiungi" />
				</div>
			</form>
		</div>
			<p class = "p"><a href = "tipologie.php">Torna alle tipologie di spedizione</a></p>
	</body>
</html>

<?php
	function aggiungi(){
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
		
		echo "<h2> Tipologia di spedizione inserita con successo! </h2>";
		$_POST['nome'] = NULL;
		$_POST['descrizione'] = NULL;
		$_POST['tempo_cons'] = NULL;
			
	}
?>