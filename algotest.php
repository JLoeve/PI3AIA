<?php

require_once("classes/voyage.php");
require_once("classes/sommet.php");
require_once("classes/graphe.php");
require_once("classes/orm.php");


$orm = new ORM("data/terminus.csv", "data/horaires.csv");

$tab_terminus = $orm->get_tab_terminus();
$tab_horaires = $orm->get_tab_horaires();


$sommets = Array();
$unique_id = 0;

foreach($tab_horaires as $ligne){

	foreach ($ligne as $sens){

		foreach ($sens as $voyage){

			$t_hdep = explode(":", $voyage["hdep"]);
			$t_harr = explode(":", $voyage["harr"]);
			$hdep = $t_hdep[0] * 60 + $t_hdep[1];
			$harr = $t_harr[0] * 60 + $t_harr[1];
				
			$v = new Voyage(
				$voyage["tdep"],
				$voyage["tarr"],
				$hdep,
				$harr,
				$voyage["sens"],
				$voyage["ligne"],
				$voyage["voyage"],
				$voyage["dist"]
			);

			$sommets[] = new Sommet($unique_id, $v);
			$unique_id++;					
		}
	}
}

	$nbBus = 0;
	$nbKm  = 0;
	$nbTps = 0;

	$voyDepMin = premierVoyage($sommets);
	$voyageActu = $voyDepMin;
	$sommets[$voyageActu]->set_parcouru(true);
	print_r($sommets[$voyageActu]);
	$bus = "bus".($nbBus+1).",l".$sommets[$voyageActu]->get_voyage()->get_ligne().":".
					$sommets[$voyageActu]->get_voyage()->get_sens().
					":v".$sommets[$voyageActu]->get_voyage()->get_voyage();
	echo "<br>";
	
	for($i=0; $i < count($sommets) ; $i++){
		if($i == $voyageActu){
			$minTemps = 2000;
			$solution = false;
			
			//echo "voyage actu = $voyageActu <br>";
			
			for($j=0; $j < count($sommets) ; $j++){
				
				if($sommets[$j]->get_parcouru() == false && $j != $voyageActu){
				
			
					
					$tpsSupp = voyageLePlusProche(
						$sommets[$voyageActu]->get_voyage()->get_harr(), 
						$sommets[$j]->get_voyage()->get_hdep(), 
						$sommets[$voyageActu]->get_voyage()->get_tarr(),  
						$sommets[$j]->get_voyage()->get_tdep()
					);
					
			
					//print_r($sommets[$j]->get_voyage());
					//echo "<br>Entre ".$voyageActu." et ".$j." : " .$tpsSupp."";
					if($tpsSupp >= 0){
						if($tpsSupp < $minTemps){
							$minTemps=$tpsSupp;
							$minVoyage = $j;
							$solution = true;
						}
					}
				}
			}
			if($solution == true){
				// On se deplace vers le voyage le plus proche
				$voyageActu = $minVoyage;
				$bus = $bus.",l".$sommets[$voyageActu]->get_voyage()->get_ligne().":".
							$sommets[$voyageActu]->get_voyage()->get_sens().
							":v".$sommets[$voyageActu]->get_voyage()->get_voyage();
				$sommets[$voyageActu]->set_parcouru(true);

			}else{
				//Nouveau Bus
				$nbBus++;
				$voyDepMin = premierVoyage($sommets);
				if($voyDepMin == -1){break;}
					$voyageActu = $voyDepMin;
					
					$bus = $bus."<br>bus".($nbBus+1).
					",l".$sommets[$voyageActu]->get_voyage()->get_ligne().":".
					$sommets[$voyageActu]->get_voyage()->get_sens().
					":v".$sommets[$voyageActu]->get_voyage()->get_voyage();
					
					$sommets[$voyageActu]->set_parcouru(true);
					//echo "voyage actu new bus = $voyageActu<br>";
			}
			$i = -1;
		}
	}
	echo "# Loïc Trichaud, Adrien Mathaly, Justine Sabbatier, Julien Loeve<br>";
	
	echo $nbBus.",1234,1234";
	echo "<br>".$bus;
	
	$cptFalse = 0;
	$cptTrue = 0;
	foreach ($sommets as $i => $s){
			if($s->get_parcouru() == false){
				$cptFalse++;
			}else{
				$cptTrue++;
			}
		}
		echo "<br><br>Fait : ".$cptTrue;
		echo "<br>Pas Fait : ".$cptFalse;
		echo "<br>Total : ".($cptFalse+$cptTrue);

// ===========================================================================================================================	
// ===========================================================================================================================	
	// Trouver le premier voyage dispo de la journée ! 
	// retourne le n° du voyage
	function premierVoyage($sommets){
	
		$heureDepMin = 2000;
		$voyDepMin = -1;
		foreach ($sommets as $i => $s){
			if($s->get_parcouru() == false){
				if($s->get_voyage()->get_hdep() < $heureDepMin){
					$heureDepMin = $s->get_voyage()->get_hdep();
					$voyDepMin = $i;
				}
			}
		}
		return $voyDepMin;
	}



// ===========================================================================================================================	
// ===========================================================================================================================	

function voyageLePlusProche($heureArrivee, $heureDepart, $term1, $term2){

	global $tab_terminus;
	//echo "<br>".substr($term1,1,1)." -  ".substr($term2,1,1);
	
	$distTerminus = $tab_terminus[$term1][$term2];
	//echo "Arrivee_S1:$heureArrivee <br> Depart_S2:$heureDepart <br> Liaison:$distTerminus<br>";
	if($distTerminus < 5){
		$heureFinale = $heureArrivee + $distTerminus + (5-$distTerminus);
	}else{
		$heureFinale = $heureArrivee + $distTerminus;
	}
	$tempsSup = $heureDepart - $heureFinale;

	if($tempsSup < 5){
	
	}
	if(($tempsSup+5) >= 5 ){ 
		
		return $tempsSup;
	
	}else{
		return -1;
	}
}


// ===========================================================================================================================	
// ===========================================================================================================================	


?>