<?php


ini_set("memory_init", "1024M");


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

$orm = new ORM("data/terminus.csv", "data/horaires.csv");

$tab_terminus = $orm->get_tab_terminus();
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

			$v = new Voyage(
				$voyage["tdep"],
				$voyage["tarr"],
				$voyage["hdep"],
				$voyage["harr"],
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

$graphe = new Graphe($sommets);
$graphe->print_sommet(1);
$graphe->print_nb_sommet();

// Construction des arcs du graphe grace à la liste d'adjacence de chaque sommet


foreach($graphe->get_sommets as $sommet)
{	
	foreach ($graphe->get_sommets as $voisin)
	{
		if($sommet->get_id() != $voisin->get_id())
			$graphe->ajouter_voisin($sommet, $voisin);
	}
}
/*
echo "<pre>";
print_r($graphe);
echo "</pre>";		

$solution == "";
$bus = Array();

while($nb_parcouru < $nb_voyages)
{
	$bus_courant = Array("txt" => Array(), "distance"=>0, "temps"=>0, "voyages"=>Array());
	if (!end($bus_courant["voyages"])
	
	
}
*/

?>
