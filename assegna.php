<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once("./connessione.php");

session_start();
if (!isset($_SESSION['accessoPermesso'])) header('Location: login_team.php');


echo '<?xml version="1.0" encoding="UTF-8"?>';
$occorrenza = 0;

function analizza_commenti(){
	$x = 0;
	
	//prendo l'ultimo commento associato alla spedizione
	$xmlCommento = "";
	foreach( file("XML/commenti.xml") as $nodo){
		$xmlCommento.= trim($nodo);
	}
	$doc = new DOMDocument();
	$doc->loadXML($xmlCommento);
	$root = $doc->documentElement;
	$commenti = $root->childNodes;
	
	for($j=0; $j<$commenti->length; $j++){
		$commento = $commenti->item($j);
		$id_sped = $commento->getAttribute('id_spedizione');
		
		if($id_sped == $_POST['accetta']) {
			
			$stato_avanz = $commento->firstChild; 
			$stato_avanzX = $stato_avanz->textContent;
	
		}
	}

	if(isset($stato_avanz) && $stato_avanzX == "Problemi nella consegna"){
		$x = 1;
	}
	return $x;
}
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title>Modalità assegnazione corriere - ByteCourier2 </title>
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
		<div class="titolo">
			<h1> Modalità di assegnazione ByteCourier </h1>
			<p> Scegli una modalità di assegnazione per questa spedizione </p>
		</div>

		<div class="corpo"> 
			<?php 
				if(isset($_POST['accetta'])) {
				
					$oggetto = analizza_commenti();
			
					if($oggetto != 1){
						
				 ?>
					<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post" >
						<p><b>Assegna automaticamente un byteCourier</b><input class = "accetta" type = "submit" name = "auto" value = "<?php echo $_POST['accetta'] ?>" /></p>
						<p> Il primo byteCourier libero verr&agrave; assegnato a questa spedizione. Se non ci sono corrieri liberi verr&agrave; assegnato quello con la valutazione maggiore </p> 
						<hr />
						<p><b>Assegna manualmente un byteCourier</b><input class = "accetta" type = "submit" name = "manual" value = "<?php echo $_POST['accetta'] ?>" /> </p>
						<p> Visualizza la lista dei byteCourier e scegline uno a cui affidare la spedizione </p>
						<hr />
						<p><b>Auto-assegnazione di un byteCourier</b><input class = "accetta" type = "submit" name = "byte" value = "<?php echo $_POST['accetta'] ?>" /> </p>
						<p> La presente richiesta di spedizione verrà scelta manualmente da uno dei corrieri </p>	
					</form>
					<hr />
					<a href = "home_ges.php"> Torna alla home </a>
				</div>
			<?php }
				else{?>
					<form action = "<?php $_SERVER['PHP_SELF'] ?>" method = "post" >
						<p><b>Assegna manualmente un byteCourier</b><input class= "accetta" type = "submit" name = "manual" value = "<?php echo $_POST['accetta'] ?>" /> </p>
						<p> Visualizza la lista dei byteCourier e scegline uno a cui affidare la spedizione </p>
					</form>
					<hr />
					<a href = "home_ges.php"> Torna alla home </a>
				</div>
			<?php }	
				}			
			?>
				
		
	</body>
</html>

<?php 

	if(isset($_POST['auto'])) {
		//query che conta tutti i corrieri liberi per vedere se sono presenti 
		$sql = "SELECT COUNT(*)
				FROM $user_table_name 
				WHERE tipo_utente = \"bc\"
				AND abilitato = \"1\"
				AND stato = \"free\"
				";
		
		if(!$resultQ = mysqli_query($connection, $sql)) {
					echo "<p> Si &egrave; verificato un errore </p>";
					exit();
		}
		
		//variabile che contiene il numero di corrieri liberi
		$conta_free = mysqli_fetch_array($resultQ);	
		
		if($conta_free['0'] == 0){
			//query che restituisce il byteCourier con valutazione più alta (se non ci sono byteCouriers liberi)
			$sql1 = "SELECT * FROM $user_table_name WHERE abilitato = \"1\" AND tipo_utente = \"bc\" ORDER BY valutazione DESC";
			if(!$resultQ = mysqli_query($connection, $sql1)) {
					echo "<p> Si &egrave; verificato un errore </p>";
					exit();
			}
			$corriere1 = mysqli_fetch_array($resultQ);
		}else{
			//query che restituisce il primo byteCourier libero con la valutazione più alta
			$sql2 = "SELECT * FROM $user_table_name WHERE abilitato = \"1\" AND tipo_utente = \"bc\" AND stato = \"free\" ORDER BY valutazione DESC";
			if(!$resultQ = mysqli_query($connection, $sql2)) {
					echo "<p> Si &egrave; verificato un errore </p>";
					exit();
			}
			$corriere1 = mysqli_fetch_array($resultQ);
		}
		
		//accedo al file xml spedizioni e lo scandisco per modificare il campo byteCourier
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
			
			$usernameY = $spedizione->getAttribute('username');
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			
			if($id_spedizioneY == $_POST['auto']) {
				$id_spedizioneX = $id_spedizione->textContent;
				
				$tipologiaX = $spedizione->getAttribute('tipologia');
				
				$spedizione->setAttribute('stato', "In carico");
				
				$byteCourier1 = $id_spedizione->nextSibling;
		
				$byteCourier2 = $byteCourier1->nextSibling;
				
				$destinatario = $byteCourier2->nextSibling;
					
				$n_colli = $destinatario->nextSibling;
				
				$tipo_collo = $n_colli->nextSibling;
					
				$onere = $tipo_collo->nextSibling;
				
				$ritiro= $onere->nextSibling;
				
				//se è previsto il ritiro da casa o non è stato assegnato il byteCourier1
				if(isset($ritiro->firstChild) && $byteCourier1->textContent == "") {
					$byteCourier1->textContent = $corriere1['username'];
					$sql = " UPDATE $user_table_name
							SET stato = \"busy\"
							WHERE username = '{$corriere1['username']}'
							";
		
					if(!$resultQ = mysqli_query($connection, $sql)){
						printf("<p>Si è verificato un errore!</p>");
						exit();
					}
				}
				//se bisogna assegnare il byteCourier2 
				else {
					$byteCourier2->textContent = $corriere1['username'];
					$sql = " UPDATE $user_table_name
							SET stato = \"busy\"
							WHERE username = '{$corriere1['username']}'
							";
		
					if(!$resultQ = mysqli_query($connection, $sql)){
						printf("<p>Si è verificato un errore!</p>");
						exit();
					}
				}
				$occorrenza = 1;
				$doc->save('XML/spedizioni.xml');
					
			}
			$i+=1;
			
		}	
			echo "<h3> E' stato assegnato il corriere ".$corriere1['username']." per la spedizione ".$_POST['auto']."</h3><a href = \"home_ges.php\"> Torna alla home </a></div>";
	}
	
	if(isset($_POST['byte'])) {
		//accedo al file xml spedizioni e lo scandisco per modificare il campo byteCourier
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
			
			if($id_spedizioneY == $_POST['byte']) {
				$id_spedizioneX = $id_spedizione->textContent;
				
				$auto = $spedizione->lastChild;
				
				$auto->textContent = "si";

				$occorrenza = 1;
				$doc->save('XML/spedizioni.xml');
			}
			$i+=1;
			
		}	
		echo "<h3>Per la spedizione n°".$id_spedizioneX." &egrave; stata scelta l'auto-assegnazione da parte di un byteCourier</h3><a href = \"home_ges.php\"> Torna alla home </a></div>";
	}
	
	if(isset($_POST['manual'])){
		$_SESSION['manual'] = $_POST['manual'];
		$sql = "SELECT * FROM $user_table_name WHERE tipo_utente = \"bc\" ORDER BY valutazione DESC";
		
		if(!$resultQ = mysqli_query($connection, $sql)) {
			echo "<p><em>Errore durante l'accesso al database</p>";
			exit();
		}
		
		$bc = "";
		while($row = mysqli_fetch_array($resultQ)){
			if($row['stato'] == "busy")
				$bc.="<div class=\"bc\" style=\"background-color: firebrick; color: white;\">";
			else
				$bc.="<div class=\"bc\" style=\"background-color: lightgreen; color: black;\">";
			
			$bc.="<div class=\"stato\">".$row['stato']."</div><p><strong>Username: </strong>".$row['username']."</p>";
			if($row['valutazione'])
				$bc.="<p><strong>Valutazione: </strong>".$row['valutazione']."</p>";
			else 
				$bc.="<p><strong>Valutazione: </strong> -- </p>";
			$bc.="<p><strong>Nome: </strong>".$row['nome']."</p>";
			$bc.="<p><strong>Cognome: </strong>".$row['cognome']."</p>";
			$bc.="<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">";
			$bc.="<input class = \"accetta\" type=\"submit\" name = \"scelta\" value=\"".$row['username']."\" />";
			$bc.="</form>";
			$bc.="</div>";
		}
		
		echo $bc;
	}
	
	if(isset($_POST['scelta'])){
		//accedo al file xml spedizioni e lo scandisco per modificare il campo byteCourier
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
			
			$usernameY = $spedizione->getAttribute('username');
		
			$id_spedizione = $spedizione->firstChild;
			$id_spedizioneY = $id_spedizione->textContent;
			
			if($id_spedizioneY == $_SESSION['manual']) {
				$id_spedizioneX = $id_spedizione->textContent;
				
				$tipologiaX = $spedizione->getAttribute('tipologia');
				
				$spedizione->setAttribute('stato', "In carico");
				
				$byteCourier1 = $id_spedizione->nextSibling;
		
				$byteCourier2 = $byteCourier1->nextSibling;
				
				$destinatario = $byteCourier2->nextSibling;
					
				$n_colli = $destinatario->nextSibling;
				
				$tipo_collo = $n_colli->nextSibling;
					
				$onere = $tipo_collo->nextSibling;
				
				$ritiro= $onere->nextSibling;
				
				//se è previsto il ritiro da casa e non è stato assegnato il byteCourier1
				if(isset($ritiro->firstChild) && $byteCourier1->textContent == "") {
					$byteCourier1->textContent = $_POST['scelta'];
					$sql = " UPDATE $user_table_name
							SET stato = \"busy\"
							WHERE username = '{$_POST['scelta']}'
							";
		
					if(!$resultQ = mysqli_query($connection, $sql)){
						printf("<p>Si è verificato un errore!</p>");
						exit();
					}
				}
				//se bisogna assegnare il byteCourier2 
				else {
					$byteCourier2->textContent = $_POST['scelta'];
					$sql = " UPDATE $user_table_name
							SET stato = \"busy\"
							WHERE username = '{$_POST['scelta']}'
							";
		
					if(!$resultQ = mysqli_query($connection, $sql)){
						printf("<p>Si è verificato un errore!</p>");
						exit();
					}
				}
				$occorrenza = 1;
				$doc->save('XML/spedizioni.xml');
				
				
			}
			$i+=1;
		}	
		echo "<h3> E' stato assegnato il corriere ".$_POST['scelta']." per la spedizione ".$_SESSION['manual']."</h3><a href = \"home_ges.php\"> Torna alla home </a></div>";
	}

?>
