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

$orm = new ORM("data/terminus.csv", "data/horaires.csv", "data/dist_terminus.csv");

$tab_terminus = $orm->get_tab_terminus();
$tab_terminus_distance = $orm->get_tab_terminus_distance();
$tab_horaires = $orm->get_tab_horaires();

//$orm->print_tab_terminus();
//$orm->print_tab_horaires();
//$orm->print_nb_voyages();

/*-----------------------------------------------------------
 * CONSTRUCTION DU GRAPHE
 *-----------------------------------------------------------*/

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

$trouve = true;
$bus_courant = Array("txt" => "", "distance"=>0, "temps"=>0, "voyages"=>Array());
while($nb_parcouru < $graphe->get_nb_sommet())
{
	if(!$trouve)
	{
		$bus[] = $bus_courant;
		$bus_courant = Array("txt" => "", "distance"=>0, "temps"=>0, "voyages"=>Array());
	}
	if (!end($bus_courant["voyages"])) // Si aucun voyage pour le moment
	{		
		$premier_sommet = $graphe->get_sommet(1);// Trouver le premier voyage/sommet (Loïc la fonction là !), en attendant on prend le premier sommet
		$bus_courant["voyages"][] = $premier_sommet;
	}
	else
	{
		// Determiner le prochain voyage/sommet //
		$dernier_sommet	= end($bus_courant["voyages"]);
		//echo $dernier_sommet;
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
			$sommet_suivant->set_parcouru(true);
			$bus_courant["voyages"][] = $sommet_suivant;
			$nb_parcouru ++;	
		}
	}
}

?>
