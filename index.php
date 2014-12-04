<?php


ini_set("memory_init", "2048M");
set_time_limit(36000000);


/*-----------------------------------------------------------
 * INCLUSION DES CLASSES
 *-----------------------------------------------------------*/

require_once("classes/voyage.php");
require_once("classes/sommet.php");
require_once("classes/graphe.php");
require_once("classes/orm.php");


/*-----------------------------------------------------------
 * EXTRACTION DES DONNEES
 *-----------------------------------------------------------*/

//$orm = new ORM("data/terminus.csv", "data/horaires.csv", "data/dist_terminus.csv");
$orm = new ORM("data\\terminus.csv", "data\\horaires.csv", "data\\dist_terminus.csv");

$tab_terminus = $orm->get_tab_terminus();
$tab_terminus_distance = $orm->get_tab_terminus_distance();
$tab_horaires = $orm->get_tab_horaires();

//$orm->print_tab_terminus();
//$orm->print_tab_horaires();
//$orm->print_nb_voyages();

/*-----------------------------------------------------------
 * CONSTRUCTION DU GRAPHE
 *-----------------------------------------------------------*/
function premierVoyage($sommets_param){

	$heureDepMin = 20000;
	$voyDepMin = 0;
	foreach ($sommets_param as $i => $s){
		if($s->get_parcouru() == false){
			if($s->get_voyage()->get_hdep() < $heureDepMin){
				$heureDepMin = $s->get_voyage()->get_hdep();
				$voyDepMin = $s;
			}
		}
	}
	return $voyDepMin;
}

// Initialisation des sommets du graphe

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

$graphe = new Graphe($sommets, $tab_terminus);
//$graphe->print_nb_sommet();

// Construction des arcs du graphe grace à la liste d'adjacence de chaque sommet

foreach($graphe->get_sommets() as $sommet)
{	
	foreach ($graphe->get_sommets() as $voisin)
	{
		if($sommet->get_id() != $voisin->get_id())
			$graphe->ajouter_arc($sommet, $voisin);
	}
}

$nb_test = 0;
while(true)
{	
	echo "** Reset **\n"; 
	foreach($graphe->get_sommets() as $sommet)
	{
		$sommet->set_parcouru(false);
	}
	
	$nb_parcouru = 0;
	$bus = Array();
	$distance_totale = 0;
	$temps_total = 0;
	$cpt_bus = 1;

	$trouve = true;
	$bus_courant = Array("txt" => "", "distance"=>0, "temps"=>0, "voyages"=>Array());
	$bus_courant["txt"] = "bus".$cpt_bus;
	while($nb_parcouru < $graphe->get_nb_sommet())
	{
		if (empty($bus_courant["voyages"])) // Si aucun voyage pour le moment
		{
			$premier_sommet = premierVoyage($graphe->get_sommets()); // Trouver le premier voyage/sommet

			if (!$premier_sommet)
			$bus_courant["distance"] = $tab_terminus_distance[0][$premier_sommet->get_voyage()->get_tdep()];
			$bus_courant["temps"] = $tab_terminus[0][$premier_sommet->get_voyage()->get_tdep()];
			$bus_courant["distance"] += $premier_sommet->get_voyage()->get_dist();
			$bus_courant["temps"] += (($premier_sommet->get_voyage()->get_harr()) - ($premier_sommet->get_voyage()->get_hdep()));
			$bus_courant["txt"] .= ",".$premier_sommet->to_text();
			$bus_courant["voyages"][] = $premier_sommet->get_id();
			$premier_sommet->set_parcouru(true);
			$nb_parcouru ++;
			//echo "premier sommet : ".$premier_sommet->get_id()."\n";
			$trouve = true;
		}
		else
		{
			//echo "Else\n";
			// Determiner le prochain voyage/sommet //		
			$dernier_sommet	= $graphe->get_sommet(end($bus_courant["voyages"]));
			//echo "dernier sommet : ".$dernier_sommet->get_id()."\n";
			$liste_voisins = $dernier_sommet->get_voisins();
			// Choix du voisin parmi la liste d'ID (paramètre IA, pour l'instant on prend le plus prêt)
			$temps_min = 9999999;
			$trouve = false;
	//---	// Mode voisin "plus proche"
		/*	foreach($liste_voisins as $voisin)
			{
				if (!$graphe->get_sommet($voisin[0])->get_parcouru())
				{
					if($voisin[1] < $temps_min)
					{
						$trouve = true;
						$id_suivant = $voisin[0];
						$temps_min = $voisin[1];
					}
				}
			}*/
	//---	// Mode voisin random
		//print_r($liste_voisins);
			$tab_voisins_libre = Array();
			foreach($liste_voisins as $voisin)
			{
				if(!$graphe->get_sommet($voisin[0])->get_parcouru())
					$tab_voisins_libre[] = $voisin[0];
			}			
			$nb_voisin_libre = count($tab_voisins_libre);
			if ($nb_voisin_libre >1)
			{
				$random = rand(0, 1);
				$id_suivant = $tab_voisins_libre[$random];
				$trouve = true;
			}
			else if ($nb_voisin_libre >0)
			{
				$random = rand(0, $nb_voisin_libre-1);
				$id_suivant = $tab_voisins_libre[$random];
				$trouve = true;
			}
	//---	// Fin modes
			if($trouve)
			{
				$sommet_suivant = $graphe->get_sommet($id_suivant);
				//echo "nouveau sommet : ".$sommet_suivant->get_id()."\n";
				$dernier_sommet	= $graphe->get_sommet(end($bus_courant["voyages"]));
				$bus_courant["distance"] += $tab_terminus_distance[$dernier_sommet->get_voyage()->get_tarr()][$sommet_suivant->get_voyage()->get_tdep()];
				$bus_courant["distance"] += $sommet_suivant->get_voyage()->get_dist();
				$bus_courant["temps"] += $tab_terminus[$dernier_sommet->get_voyage()->get_tarr()][$sommet_suivant->get_voyage()->get_tdep()];
				$bus_courant["temps"] += (($sommet_suivant->get_voyage()->get_harr()) - ($sommet_suivant->get_voyage()->get_hdep()));
				$bus_courant["txt"] .= ",".$sommet_suivant->to_text();
				
				$bus_courant["voyages"][] = $sommet_suivant->get_id();
				$sommet_suivant->set_parcouru(true);
				$nb_parcouru ++;
			}
		}	
		if(!$trouve || $nb_parcouru == $graphe->get_nb_sommet())
		{
			$dernier_sommet	= $graphe->get_sommet(end($bus_courant["voyages"]));
			$bus_courant["distance"] += $tab_terminus_distance[$dernier_sommet->get_voyage()->get_tarr()][0];
			$bus_courant["temps"] += $tab_terminus[$dernier_sommet->get_voyage()->get_tarr()][0];
			$distance_totale += $bus_courant["distance"];
			$temps_total += $bus_courant["temps"];
			$bus[] = $bus_courant;
			$cpt_bus++;		
			$trouve = false;
			$bus_courant = Array("txt" => "", "distance"=>0, "temps"=>0, "voyages"=>Array());		
			$bus_courant["txt"] = "bus".$cpt_bus;
		}
	}
	//print_r($bus[0]);
	echo "Nb bus : ".count($bus)."\n";
	echo "Distance : $distance_totale\n";
	echo "Temps : $temps_total\n";
	$filename = "solutions\\res_".date("Y-m-d_H-i-s")."_".$nb_test."_jloeve.csv";

	//ecriture du fichier de sortie
	file_put_contents($filename, "#Justine Sabbatier, Adrien Mathaly, Loïc Trichaud, Julien Loève\n", LOCK_EX);
	file_put_contents($filename, count($bus).",".$temps_total.",".$distance_totale."\n" , FILE_APPEND | LOCK_EX);
	foreach($bus as $bus_courant)
	{
		file_put_contents($filename, $bus_courant["txt"]."\n", FILE_APPEND | LOCK_EX);
	}
	file_put_contents("solutions\\liste_solutions_jloeve.csv",count($bus).",".$distance_totale.",".$temps_total.",".$filename."\n", FILE_APPEND);
	$nb_test ++;
}
?>
