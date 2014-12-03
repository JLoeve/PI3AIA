<?php

class Graphe{

	/*-----------------------------------------------------------
	 * ATTRIBUTS
	 *-----------------------------------------------------------*/

	private $sommets;

	/*-----------------------------------------------------------
	 * CONSTRUCTEURS
	 *-----------------------------------------------------------*/

	function __construct($init_sommets){

		$this->sommets = $init_sommets;

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
		$sommet1->ajouter_voisin($sommet2);
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