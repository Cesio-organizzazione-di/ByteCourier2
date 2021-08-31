<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	$msg = "";

	session_start();
	if (!isset($_SESSION['accessoPermesso'])) header('Location: login_cli.php');

	if(!isset($_SESSION['invio'])){
		$_SESSION['nome1'] = NULL;
		$_SESSION['via1'] = NULL;
		$_SESSION['civico1'] = NULL;
		$_SESSION['citta1'] = NULL;
		$_SESSION['CAP1'] = NULL;
		$_SESSION['telefono1'] = NULL;
		$_SESSION['citta1'] = NULL;
		$_SESSION['altezza1'] = NULL;
		$_SESSION['lunghezza1'] = NULL;
		$_SESSION['larghezza1'] = NULL;
		$_SESSION['peso1'] = NULL;
		$_SESSION['altezza1'] = NULL;
		$_SESSION['fragile1'] = NULL;
		$_SESSION['n_colli1'] = NULL;
	}
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Home - ByteCourier2 </title>
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
			<p> Richiedi spedizione </p>
			<a href = "ricerca.php"> Gestisci spedizione </a>
			<a href = "area_clienti.php"> Area Clienti </a>
		</div>
		
		<div class="corpo">
			<?php 
				if(isset($_SESSION['invio'])){
					$msg="<p style=\"text-align: center;\"><em>Completa tutti i campi per proseguire</em></p>";
					echo $msg;
					$_SESSION['invio'] = NULL;
				}
			?>
			<form action = "riepilogo.php" method = "post">
				<h3> Informazioni sul destinatario </h3>
				<div class="corpo_richiesta">
					<p><div class = "info">Nome/Cognome o Nome attivit&agrave;:</div><div class="input"><input  type = "text" name = "nome" <?php if(isset($_SESSION['nome1'])) echo "value=\"{$_SESSION['nome1']}\"" ?> /></div></p>
					<p style="margin-left: -30px; font-variant: small-caps"><u>Indirizzo di spedizione </u></p> 
					<p><div class = "info">Via:</div><div class="input"><input type = "text" name = "via" <?php if(isset($_SESSION['via1'])) echo "value=\"{$_SESSION['via1']}\""?> /></div></p> 
					<p><div class = "info">Numero civico:</div><div class="input"><input type = "text" name = "n_civico" <?php if(isset($_SESSION['civico1'])) echo "value=\"{$_SESSION['civico1']}\""?> /></div></p>
					<p><div class = "info">Citt&agrave;:</div><div class="input"><input type = "text" name = "citta" <?php if(isset($_SESSION['citta1'])) echo "value=\"{$_SESSION['citta1']}\""?> /></div></p>
					<p><div class = "info">CAP:</div><div class="input"><input type = "text" name = "CAP" <?php if(isset($_SESSION['CAP1'])) echo "value=\"{$_SESSION['CAP1']}\""?> /></div></p>
					<p><div class = "info">Telefono:</div><div class="input"><input type = "text" name = "telefono" <?php if(isset($_SESSION['telefono1'])) echo "value=\"{$_SESSION['telefono1']}\""?> /></div></p>
				</div>
				<hr />
				<h3> Quantit&agrave; e descrizione della merce da spedire </h3>
				<div class = "corpo_richiesta">
					<p> Numero colli: 
						<select name = "n_colli">
							<option <?php if(isset($_SESSION['n_colli1']) && $_SESSION['n_colli1'] == 1) echo "selected = \"selected\"";?> value = "1"> 1 </option>
							<option <?php if(isset($_SESSION['n_colli1']) && $_SESSION['n_colli1'] == 2) echo "selected = \"selected\"";?> value = "2"> 2 </option>
							<option <?php if(isset($_SESSION['n_colli1']) && $_SESSION['n_colli1'] == 3) echo "selected = \"selected\"";?> value = "3"> 3 </option>
							<option <?php if(isset($_SESSION['n_colli1']) && $_SESSION['n_colli1'] == 4) echo "selected = \"selected\"";?>value = "4"> 4 </option>
						</select>
					</p>
					<p style="margin-left: -30px; font-variant: small-caps"><u>Dimensioni</u></p>
					<p><div class = "info">Altezza:</div><div class="input"><input type = "text" name = "altezza" <?php if(isset($_SESSION['altezza1'])) echo "value=\"{$_SESSION['altezza1']}\""?> /> cm</div> </p>
					<p><div class = "info">Larghezza:</div><div class="input"><input type = "text" name = "larghezza" <?php if(isset($_SESSION['larghezza1'])) echo "value=\"{$_SESSION['larghezza1']}\""?> /> cm</div> </p>
					<p><div class = "info">Lunghezza:</div><div class="input"><input type = "text" name = "lunghezza" <?php if(isset($_SESSION['lunghezza1'])) echo "value=\"{$_SESSION['lunghezza1']}\""?> /> cm</div> </p>
					<p><div class = "info">Peso:</div><div class="input"><input type = "text" name = "peso" <?php if(isset($_SESSION['peso1'])) echo "value=\"{$_SESSION['peso1']}\""?> /> kg</div> </p>
					<p> Fragile: 
						<select name = "fragile">
							<option <?php if(isset($_SESSION['fragile1']) && $_SESSION['fragile1'] == "si") echo "selected = \"selected\"";?> value = "si"> Si </option>
							<option <?php if(isset($_SESSION['fragile1']) && $_SESSION['fragile1'] == "no") echo "selected = \"selected\"";?> value = "no"> No </option>
						</select>
					</p>
				</div>
				<hr />
				<h3> Ritiro da casa? </h3>
				<div class = "corpo_richiesta">
					<p> <input type = "radio" name="ritiro" value = "si" /> Si </p>
					<p> <input type = "radio" name="ritiro" value = "no" /> No </p>
				</div>
				<hr />
				<h3> Indicare chi pagher&agrave; le spese di trasporto </h3>
				<div class = "corpo_richiesta">
					<p> <input type = "radio" name = "onere" value = "Mittente" /> Mittente </p>
					<p> <input type = "radio" name = "onere" value = "Destinatario" /> Destinatario </p>
				</div>
				<hr />
				<h3> Scegli la tipologia di spedizione </h3> 
				<div class = "corpo_richiesta">
					<?php $lista= stampa_pacchetti(); echo $lista; ?>
				</div>
				<div class="bottoni">
					<input type = "submit" name = "invio" value = "Invia ordine" />
				</div>
			</form>
		</div>
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
				$offerte.= "<span class=\"pacchetto\">
								<input type=\"radio\" name=\"tipologia\" value=\"{$id_pacchettoX}\" /> <strong>{$nomeX}</strong>
								<p><span style=\"color:maroon;\">Descrizione</span>: {$descrizioneX}</p>
								<p><span style=\"color:maroon;\">Tempo di consegna</span>: {$tempo_consX}</p>
								<p><span style=\"color:maroon;\">Prezzo</span>: {$prezzoX} &euro;</p>
							</span>";
			}
		}
		return $offerte;
	}
?>