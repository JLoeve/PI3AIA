<?php
// Retourne la liste des sommets

$minTemps = 56;
$liaisonTerminus = 5;
$hArrivee = 810;
$hDepart = 820;

$minTemps = voyageLePlusProche($hArrivee, $hDepart, $liaisonTerminus, $minTemps);

function voyageLePlusProche($heureArrivee, $heureDepart){

	global $tab_terminus;
	$distTerminus = $tab_terminus[1][2];
	echo "Arrivee_S1:$heureArrivee <br> Depart_S2:$heureDepart <br> Liaison:$distTerminus<br>";

	$heureFinale = $heureArrivee + $distTerminus;
	$tempsSup = $heureDepart - $heureFinale;

	if(($tempsSup+5) >= 5 ){ 
		
		echo "Ok pour le trajet :  ".$tempsSup." min d'attente";
	
	}else{
		echo "Pas le temps : ".($heureDepart-$heureFinale);
	}
	
	return $tempsSup;

}


?>