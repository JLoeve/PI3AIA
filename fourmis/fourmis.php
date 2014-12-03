<?PHP
/****
Finir niveau_trace
*/


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
			echo "<p>  Performance du nouveau graphe: $meilleur_temporaire<br /></p>\n";
		}
	}

	function niveau_trace($parcouru, $nb_bus)
	{
		$pheromones=0;
		
		$memoire_des_graphes = array('rang', 'numero_du_graphe', 'nb_de_bus_du_graphe', 'valeur_de_phero'); 

		$i=0; 
		foreach($memoire_des_graphes as $param) 
		{ 
		$array[$param] = $value[$i++]; 
		} 
		

		if($parcouru == "False")
		{
			
		}
	}

}

