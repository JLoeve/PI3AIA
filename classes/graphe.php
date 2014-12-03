<?php

class Graphe{

	/*-----------------------------------------------------------
	 * ATTRIBUTS
	 *-----------------------------------------------------------*/

	private $sommets;
	private $tab_terminus;

	/*-----------------------------------------------------------
	 * CONSTRUCTEURS
	 *-----------------------------------------------------------*/

	function __construct($init_sommets, $tab){

		$this->sommets = $init_sommets;
		$this->tab_terminus = $tab;

	}

	/*-----------------------------------------------------------
	 * GETTERS
	 *-----------------------------------------------------------*/

	function get_sommet($id){

		return $this->sommets[$id];
	}


	function get_nb_sommet(){

		return count($this->sommets);
	}


	function get_sommets(){

		return $this->sommets;
	}
	
	/*-----------------------------------------------------------
	 * METHODS
	 *-----------------------------------------------------------*/

	function ajouter_arc($sommet1, $sommet2){
		// Test if voisin  possible {	
		$harr1 = $sommet1->get_voyage()->get_harr();
		$hdep2 = $sommet2->get_voyage()->get_hdep();
		$tarr1 = $sommet1->get_voyage()->get_tarr();
		$tdep2 = $sommet2->get_voyage()->get_tdep();
		
		$inter = $hdep2 - $harr1;
		$liaison = $this->tab_terminus[$tarr1][$tdep2];
		$heure_arrive = $harr1 + $liaison;
		if($heure_arrive <= $hdep2) // On PEUT y aller à temps
		{
			$attente = $hdep2 - $heure_arrive;
			if(($inter)>5)
			{
				$sommet1->ajouter_voisin($sommet2);
				return 0;
			}
		}
		return -1;
	}


	function print_nb_sommet(){

		echo "Nb sommet = ".count($this->sommets);
	}

	function print_sommet($id){

		echo "<pre>";
		var_dump($this->sommets[$id]);
		echo "</pre>";
	}
}

?>