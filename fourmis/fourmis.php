<?PHP
/****
Finir niveau_trace
****/

class fourmis
{
	/*-----------------------------------------------------------
	 * ATTRIBUTS
	 *-----------------------------------------------------------*/
	private $meilleur_temporaire;
	private $nb_bus;
	private $parcouru;
	private $pheromones;
	
	/*-----------------------------------------------------------
	 * CONSTRUCTEURS
	 *-----------------------------------------------------------*/

	function __construct($meilleur_temporaire, $nb_bus, $parcouru){

		$this->mtemp = $meilleur_temporaire;
		$this->nbbus = $nb_bus;
		$this->parc = $parcouru;
		$this->phero = $pheromones;
	}
 
	/*-----------------------------------------------------------
	 * METHODS
	 *-----------------------------------------------------------*/
 
 
	function evaluer_perfomance($nb_bus)
	{
		if( $nb_bus < $meilleur_temporaire)
		{
			$meilleur_temporaire = $nb_bus
			echo "<p>  Performance du nouveau graphe: $meilleur_temporaire <br /></p>\n";
		}
	}

	function niveau_trace($parcouru, $nb_bus)
	{
		$pheromones=0;
		
		$memoire_des_graphes = array('rang', 'numero_du_graphe', 'nb_de_bus_du_graphe', 'valeur_de_phero'); 

		$rang=0; 
		foreach($graphe as $param) 
		{ 
			$rang ++; 
			array_push($memoire_des_graphes, $rang, $idgraphe, $nbbus, $phero);
		} 
		
		if($parcouru == "False")
		{
			$phero=0;
			else
			(
				$phero=($nbbus/0.1);
				echo "<p> Valeur pheromones: $phero <br /></p>\n";
				evaporation($phero);
			)	
		}
		
	}
	
	function evaporation($phero)
	{
		$phero=(70/100*$phero);
		return($phero);
	}
	

}

