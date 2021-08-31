<?php
	$db_name = "lweb9";
	$user_table_name = "Utente";
	
	//TENTATIVO DI CONNESSIONE
	$connection = new mysqli("localhost", "lweb9", "lweb9", $db_name);
	
	//CONTROLLO CONNESSIONE
	if(mysqli_connect_errno()) {
		printf("Errore di connessione al db: %s\n", mysqli_connect_error($connection));
		exit();
	}
?>