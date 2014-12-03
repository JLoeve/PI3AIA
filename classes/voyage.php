<?php


class Voyage{
	
	/*-----------------------------------------------------------
	 * ATTRIBUTS
	 *-----------------------------------------------------------*/

	private $tdep;
	private $tarr;
	private $hdep;
	private $harr;
	private $sens;
	private $ligne;
	private $voyage;
	private $dist;

	/*-----------------------------------------------------------
	 * CONSTRUCTEURS
	 *-----------------------------------------------------------*/

	function __construct($init_tdep, $init_tarr, $init_hdep, $init_harr, $init_sens, $init_ligne, $init_voyage, $init_dist){

		$this->tdep = $init_tdep;
		$this->tarr = $init_tarr;
		$this->hdep = $init_hdep;
		$this->harr = $init_harr;
		$this->sens = $init_sens;
		$this->ligne = $init_ligne;
		$this->voyage = $init_voyage;
		$this->dist = $init_dist;
	}

	/*-----------------------------------------------------------
	 * GETTERS
	 *-----------------------------------------------------------*/

	function get_tdep(){

		return $this->tdep;
	}

	function get_tarr(){

		return $this->tarr;
	}

	function get_hdep(){

		return $this->hdep;
	}

	function get_harr(){

		return $this->harr;
	}

	function get_sens(){

		return $this->sens;
	}

	function get_ligne(){

		return $this->ligne;
	}

	function get_voyage(){

		return $this->voyage;
	}

	function get_dist(){

		return $this->dist;
	}

	/*-----------------------------------------------------------
	 * SETTERS
	 *-----------------------------------------------------------*/

	function set_tdep($new_tdep){

		$this->tdep = $new_tdep;
	}

	function set_tarr($new_tarr){

		$this->tarr = $new_tarr;
	}

	function set_hdep($new_hdep){

		$this->hdep = $new_hdep;
	}

	function set_harr($new_harr){

		$this->harr = $new_harr;
	}

	function set_sens($new_sens){

		$this->sens = $new_sens;
	}

	function set_ligne($new_ligne){

		$this->ligne = $new_ligne;
	}

	function set_voyage($new_voyage){

		$this->voyage = $new_voyage;
	}

	function set_dist($new_distance){

		$this->dist = $new_distance;
	}

	/*-----------------------------------------------------------
	 * METHODS
	 *-----------------------------------------------------------*/

}


?>