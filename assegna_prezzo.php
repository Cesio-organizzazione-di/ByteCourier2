<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Assegna prezzo - ByteCourier2 </title>
		<link rel = "stylesheet" type = "text/css" href = "stile_ges.css" />
	</head>
	<body class="ges">
	<div class = "logo_piccolo">
			<img src = "immagini/logo_white.png" alt = "ByteCourier2" />
		</div>
		<?php if(isset($_POST['assegna'])) { ?>
			<h1 class="titolo"> Inserisci prezzo </h1>
			<form class="prezzo" action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post">
				<p>Prezzo: <input type = "text" name = "prezzo" /> &euro;</p>
				<input class="ok" type = "submit" name = "aggiorna" value = "<?php echo $_POST['assegna']?>" />
			</form>
			<div class="link"><a href = "prezzi.php"> Torna alle tipologie di spedizione </a></div>
		<?php } ?>
		
		
	</body>
</html>

<?php
	if(isset($_POST['aggiorna'])) {
		if($_POST['prezzo']){
			$xmlPacchetto = "";
			foreach(file("XML/tipologie.xml") as $nodo){
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
				if($id_pacchettoX == $_POST['aggiorna']) {
					$prezzo = $pacchetto->lastChild;
					$prezzo->textContent = "{$_POST['prezzo']}";
					
					$occorrenza = 1;
					$doc->save('XML/tipologie.xml');
				}
				$i+=1;
			}
			if($occorrenza == 1){
				echo "<h2 class=\"titolo\">Il prezzo &egrave; stato aggiornato con successo!</h2>";
				echo "<div style= \"margin-top: 20%;\" class=\"link\"><a href = \"prezzi.php\"> Torna alle tipologie di spedizione </a></div>";
			}
			else echo "<div class=\"titolo\"><p><em> Si &egrave; verificato un errore </em></p></div>"; 
		}
		else{
			echo "<h2 class=\"titolo\">Inserisci il prezzo per completare l'operazione</h2>";
			echo "<form class=\"avviso\" action =\"assegna_prezzo.php\" method=\"post\">
					<input class=\"indietro\" type=\"submit\" name=\"assegna\" value=\"{$_POST['aggiorna']}\" title=\"Assegna prezzo\" />
				  </form>";
		}
	}
?>