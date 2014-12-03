<?php

class Sommet{

	/*-----------------------------------------------------------
	 * ATTRIBUTS
	 *-----------------------------------------------------------*/

	public $id;
	public $voyage;
	public $voisins = Array();
	public $parcouru = false;	
	
	/*-----------------------------------------------------------
	 * CONSTRUCTEURS
	 *-----------------------------------------------------------*/

	function __construct($init_id, $init_voyage){

		$this->id = $init_id;
		$this->voyage = $init_voyage;
		$this->parcouru = false;
	}
	
	/*-----------------------------------------------------------
	 * GETTERS
	 *-----------------------------------------------------------*/

	function get_id(){

		return $this->id;
	}

	function get_voyage(){

		return $this->voyage;
	}

	function get_voisins(){

		return $this->voisins;
	}

	function get_parcouru(){

		return $this->parcouru;
	}
	
	/*-----------------------------------------------------------
	 * SETTERS
	 *-----------------------------------------------------------*/
	
	function set_id($new_id){

		$this->id = $new_id;
	}

	function set_voyage($new_voyage){

		$this->voyage = $new_voyage;
	}


	function set_parcouru($new_parcouru){

		$this->parcouru = $new_parcouru;
	}
	
	function ajouter_voisin($sommet){

		// Calcul du temps entre l'arrivée et le départ
		$temps = ($sommet->get_voyage()->get_hdep()) - ($this->get_voyage()->get_harr());
		$this->voisins[] = Array($sommet->get_id(), $temps);
	}
	
	function supprimer_voisin($sommet){

		array_splice($voisins, $sommet->get_id(), 1);
	}

	/*-----------------------------------------------------------
	 * METHODES
	 *-----------------------------------------------------------*/
	
	function to_text()
	{
		return "l".$this->get_voyage()->get_ligne().":".$this->get_voyage()->get_sens().":v".$this->get_voyage()->get_voyage();
	}
	// Nothing
}

?>	