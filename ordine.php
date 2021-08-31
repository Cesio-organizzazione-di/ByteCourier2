<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$msg = "";

session_start();
if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Riepilogo ordine - ByteCourier2</title>
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
				if(isset($_POST['accetta'])) {
					echo "<h1> Richiesta ricevuta con successo! </h1>";
					echo "<p>La tua richiesta di spedizione &egrave; stata ricevuta con successo. Uno dei nostri gestori servir&agrave; presto la tua richiesta.</p>";
				}
				
				else if(isset($_POST['rifiuta'])) {
					echo "<h1> Richiesta annullata </h1>"; 
					elimina_richiesta();
				}
			?>
			<a href = "home_cli.php"> Torna alla home </a>
			<a href = "richiesta.php"> Effettua una nuova richiesta di spedizione </a>
		</div>
	</body>
</html>

<?php
	function elimina_richiesta() {

		$xmlSpedizione = "";
		foreach(file("XML/spedizioni.xml") as $nodo){
			$xmlSpedizione.= trim($nodo);
		}
		$doc = new DOMDocument();
		$doc->loadXML($xmlSpedizione);
		$root = $doc->documentElement;
		$spedizioni = $root->childNodes;
		
		$eliminando = $root->lastChild;
		
		$root->removeChild($eliminando);
		$doc->save('XML/spedizioni.xml');
	}
?>