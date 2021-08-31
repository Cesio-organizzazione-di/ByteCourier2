<?php
	function elimina_tipologia($id) {
		//troviamo il pacchetto associato a quella spedizione
		$xmlPacchetto = "";
		foreach(file("XML/tipologie.xml") as $nodo1){
			$xmlPacchetto.= trim($nodo1);
		}
		$doc1 = new DOMDocument();
		$doc1->loadXML($xmlPacchetto);
		$root1 = $doc1->documentElement;
		$pacchetti = $root1->childNodes;
		
		$j=0;
		$occorrenza=0;
		
		while($j<$pacchetti->length) {
			$pacchetto = $pacchetti->item($j);
			
			$id_pacchetto = $pacchetto->firstChild;
			$id_pacchettoX = $id_pacchetto->textContent;
			
			$stato = $pacchetto->getAttribute('stato');
			
			if($stato == "Eliminato" && $id_pacchettoX = $id)
				$occorrenza = 1;
			
			$i+=1;
		}
		
		//se è stato eliminato controllo che non sia associato a nessun'altra spedizione
		if($occorrenza == 1) {
			$xmlSpedizione = "";
			foreach(file("XML/spedizioni.xml") as $nodo2){
				$xmlSpedizione.= trim($nodo2);
			}
			$doc2 = new DOMDocument();
			$doc2->loadXML($xmlSpedizione);
			$root = $doc2->documentElement;
			$spedizioni = $root2->childNodes;
			
			$k = 0;
			$trovato = 0;
			
			while($k<$spedizioni->length && $occorrenza == 0) {
				$spedizione = $spedizioni->item($k);
				$tipologia = $spedizione->getAttribute("tipologia");
				$stato = $spedizione->getAttribute("stato");
				
				if($tipologia == $id && ($stato == "In sospeso" || $stato == "In carico")) 
					$trovato = 1;
				
				$k+=1;
			}
			
			//se il pacchetto eliminando è associato a spedizioni completate o rifiutate => il pacchetto viene eliminato definitivamente
			if($trovato == 0) {
				$root1->removeChild($pacchetto);
				
				$doc1->save('XML/tipologie.xml'); 
			}
		}
	}
?>