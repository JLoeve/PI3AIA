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
	 * METHODS
	 *-----------------------------------------------------------*/

	function ajouter_arc($sommet1, $sommet2){

		// Test si voisin
		$sommet1->ajouter_voisin($sommet2);
	}

	function print_sommet($id){

		echo "<pre>";
		var_dump($this->sommets[$id]);
		echo "</pre>";
	}


	function print_nb_sommet(){

		echo "Nb sommet = ".count($this->sommets);
	}


	function get_sommets(){

		return $this->sommets;
	}
}

?>