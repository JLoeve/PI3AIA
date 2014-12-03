<?php


ini_set("memory_init", "1024M");
set_time_limit(1600);


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
function premierVoyage($sommets){

	$heureDepMin = 20000;
	$voyDepMin = 0;
	foreach ($sommets as $i => $s){
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
$graphe->print_sommet(538);
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
	

$solution = "";
$bus = Array();
$nb_parcouru = 0;
$distance_totale = 0;
$temps_total = 0;
$cpt_bus = 1;

$trouve = true;
$bus_courant = Array("txt" => "", "distance"=>0, "temps"=>0, "voyages"=>Array());
$bus_courant["txt"] = "bus".$cpt_bus;
while($nb_parcouru < $graphe->get_nb_sommet())
{
	if(!$trouve)
	{
		$dernier_sommet	= $graphe->get_sommet(end($bus_courant["voyages"]));
		$bus_courant["distance"] += $tab_terminus_distance[$dernier_sommet->get_voyage()->get_tarr()][0];
		$bus_courant["temps"] += $tab_terminus[$dernier_sommet->get_voyage()->get_tarr()][0];
		$distance_totale += $bus_courant["distance"];
		$temps_total += $bus_courant["temps"];
		$bus[] = $bus_courant;
		$cpt_bus++;
		$bus_courant = Array("txt" => "", "distance"=>0, "temps"=>0, "voyages"=>Array());		
		$bus_courant["txt"] = "bus".$cpt_bus;
	}
	if (!end($bus_courant["voyages"])) // Si aucun voyage pour le moment
	{
		$premier_sommet = premierVoyage($graphe->get_sommets()); // Trouver le premier voyage/sommet
		$premier_sommet->set_parcouru(true);
		$bus_courant["distance"] = $tab_terminus_distance[0][$premier_sommet->get_voyage()->get_tdep()];
		$bus_courant["temps"] = $tab_terminus[0][$premier_sommet->get_voyage()->get_tdep()];
		$bus_courant["distance"] += $premier_sommet->get_voyage()->get_dist();
		$bus_courant["temps"] += (($premier_sommet->get_voyage()->get_harr()) - ($premier_sommet->get_voyage()->get_hdep()));
		$bus_courant["txt"] .= ",".$premier_sommet->to_text();
		$bus_courant["voyages"][] = $premier_sommet->get_id();
		$nb_parcouru ++;
		//echo "premier sommet : ".$premier_sommet->get_id()."\n";
		$trouve = true;
	}
	else
	{
		// Determiner le prochain voyage/sommet //
		$dernier_sommet	= $graphe->get_sommet(end($bus_courant["voyages"]));
		//echo "dernier sommet : ".$dernier_sommet->get_id()."\n";
		$liste_voisins = $dernier_sommet->get_voisins();
		// Choix du voisin parmi la liste d'ID (paramètre IA, pour l'instant on prend le plus prêt)
		$temps_min = 9999999;
		$trouve = false;
		foreach($liste_voisins as $voisin)
		{
			//echo $graphe->get_sommet($voisin[0])->get_id();
			if (!$graphe->get_sommet($voisin[0])->get_parcouru())
			{
				$trouve = true;
				if($voisin[1] < $temps_min)
				{
					$id_suivant = $voisin[0];
					$temps_min = $voisin[1];
				}
			}
		}
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
			$sommet_suivant->set_parcouru(true);
			
			$bus_courant["voyages"][] = $sommet_suivant->get_id();
			$nb_parcouru ++;
		}	
	}
}
//print_r($bus[0]);
echo "Nb bus : ".count($bus)."\n";
echo "Distance : $distance_totale\n";
echo "Temps : $temps_total\n";
$filename = "res_".date("Y-m-d_H-i-s")."_jloeve.csv";

//ecriture du fichier de sortie
file_put_contents($filename, "#Justine Sabbatier, Adrien Mathaly, Loïc Trichaud, Julien Loève\n");
file_put_contents($filename, count($bus).",".$temps_total.",".$distance_totale."\n" , FILE_APPEND | LOCK_EX);
foreach($bus as $bus_courant)
{
	file_put_contents($filename, $bus_courant["txt"]."\n", FILE_APPEND | LOCK_EX);
}

print_r($bus[5]);
print_r ($graphe->get_sommet($bus[5]["voyages"][0])->get_voisins());
print_r ($graphe->get_sommet($bus[5]["voyages"][1])->get_voyage());
?>
