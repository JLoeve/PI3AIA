<?php

class ORM{
	
	/*-----------------------------------------------------------
	 * ATTRIBUTS
	 *-----------------------------------------------------------*/

	private $tab_terminus;
	private $tab_terminus_distance;
	private $tab_horaires;

	/*-----------------------------------------------------------
	 * CONSTRUCTEUR
	 *-----------------------------------------------------------*/

	function __construct($filename_terminus, $filename_horaires, $filename_terminus_distance){

		$this->tab_terminus = $this->lire_terminus($filename_terminus);
		$this->tab_terminus_distance = $this->lire_terminus_distance($filename_terminus_distance);
		$this->tab_horaires = $this->lire_horaires($filename_horaires);
	}

	/*-----------------------------------------------------------
	 * GETTERS
	 *-----------------------------------------------------------*/

	function get_tab_terminus(){

		return $this->tab_terminus;
	}

	function get_tab_horaires(){

		return $this->tab_horaires;
	}
	
	function get_tab_terminus_distance(){
	
		return $this->tab_terminus_distance;
	
	}

	/*-----------------------------------------------------------
	 * METHODS
	 *-----------------------------------------------------------*/

	function lire_terminus($filename){

		$tab = "";
		$row = 0;
		if (($handle = fopen($filename, "r")) !== FALSE) { 

			while (($data = fgetcsv($handle, 100, ",")) !== FALSE){

				if($row > 0){

					$num = count($data);
					for ($c=0; $c < $num; $c++){

						if($c > 0)
							$tab[$row-1][$c-1] = $data[$c];
					}
				}

				$row++;
			}

			fclose($handle);
		}

		return $tab;
	}
	
	function lire_terminus_distance($filename){

		$tab = "";
		$row = 0;
		if (($handle = fopen($filename, "r")) !== FALSE) { 

			while (($data = fgetcsv($handle, 100, ",")) !== FALSE){

				if($row > 0){

					$num = count($data);
					for ($c=0; $c < $num; $c++){

						if($c > 0)
							$tab[$row-1][$c-1] = $data[$c];
					}
				}

				$row++;
			}

			fclose($handle);
		}
		return $tab;
	}


	function lire_horaires($filename){

		$row = 1;

		if (($handle = fopen($filename, "r")) !== FALSE) {

			$t_horaires = "";
			$ligne = 0;
			$sensRetour = 0;
			$terminus = -1;

			while ( ($data = fgetcsv($handle, 1000, ",")) !== FALSE ) {

				$num = count($data);
				//echo "<p> $num champs à la ligne $row: <br /></p>\n";
				$row++;

				if( preg_match("#ligne#", $data[0]) ){

					$matches=array(); 
					preg_match("/[0-9]{1,2}$/", $data[0], $matches); 
					$sensRetour = ($matches[0] == $ligne) ? 1 : 0;
					$ligne = $matches[0];
					$terminus = -1;

				}else if( preg_match("#T#", $data[0]) ){

					$matches=array(); 
					preg_match("/[0-9]{1,2}$/", $data[0], $matches); 
					$sens = ($sensRetour == 0) ? 'a' : 'r';  
					$terminus++;

					for ($c=1; $c < $num; $c++) {

						$sens = ($sensRetour == 0) ? 'a' : 'r';
						$t_horaires[ "l".$ligne ][ $sens ][ $terminus ][ 0 ] = "t".$matches[0];
						$t_horaires[ "l".$ligne ][ $sens ][ $terminus ][ $c ] = $data[$c];

					}


				}else if( preg_match("#Dist#", $data[0]) ){

					for ($c=1; $c < $num; $c++){

						$sens = ($sensRetour == 0) ? 'a' : 'r';
						$t_horaires[ "l".$ligne ][ $sens ][ "dist" ][ $c ] = $data[$c];

					}

				}

			}

			$t_horaires2 = "";

			foreach ($t_horaires as $iligne => $ligne){

				for($ivoyage=1; $ivoyage < count($ligne['a'][0]); $ivoyage++){

					$dep = "";
					$arr = "";
					$tdep = "";
					$tarr = "";

					foreach ($ligne['a'] as $iterminus => $terminus) {

						if( is_numeric($iterminus) ){

							if( $dep == "" && $ligne['a'][$iterminus][$ivoyage] != "" ){

								$dep = $ligne['a'][$iterminus][$ivoyage];
								$tdep = $ligne['a'][$iterminus][0];
							}

							if( $ligne['a'][$iterminus][$ivoyage] != "" ){
								
								$arr = $ligne['a'][$iterminus][$ivoyage];
								$tarr = $ligne['a'][$iterminus][0];
							}

						}else{
							// Nothing
						}

						$t_horaires2[$iligne]['a']['v'.$ivoyage]['hdep'] = $dep;
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['harr'] = $arr;
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['tdep'] = substr($tdep, 1);
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['tarr'] = substr($tarr, 1);
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['sens'] = 'a';
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['ligne'] = substr($iligne, 1);
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['voyage'] = $ivoyage;
						$t_horaires2[$iligne]['a']['v'.$ivoyage]['dist'] = $ligne['a']["dist"][$ivoyage];
						
					}
				}

				for($ivoyage=1; $ivoyage < count($ligne['r'][0]); $ivoyage++){

					$dep = "";
					$arr = "";
					$tdep = "";
					$tarr = "";

					foreach ($ligne['r'] as $iterminus => $terminus) {

						if( is_numeric($iterminus) ){

							if( $dep == "" && $ligne['r'][$iterminus][$ivoyage] != "" ){

								$dep = $ligne['r'][$iterminus][$ivoyage];
								$tdep = $ligne['r'][$iterminus][0];
							}

							if( $ligne['r'][$iterminus][$ivoyage] != "" ){
								
								$arr = $ligne['r'][$iterminus][$ivoyage];
								$tarr = $ligne['r'][$iterminus][0];
							}

						}else{
							// Nothing
						}

						$t_horaires2[$iligne]['r']['v'.$ivoyage]['hdep'] = $dep;
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['harr'] = $arr;
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['tdep'] = substr($tdep, 1);
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['tarr'] = substr($tarr, 1);
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['sens'] = 'r';
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['ligne'] = substr($iligne, 1);
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['voyage'] = $ivoyage;
						$t_horaires2[$iligne]['r']['v'.$ivoyage]['dist'] = $ligne['r']["dist"][$ivoyage];
						
					}
				}
			}

			fclose($handle);
		}
		return $t_horaires2;
	}

	function print_nb_voyages(){

		$cpt = 0;

		foreach($this->tab_horaires as $iligne => $ligne){
			
			foreach ($ligne as $isens => $sens){

				//echo "Ligne $iligne - $isens - nb = ".count($sens)."<br>";

				foreach ($sens as $voyage){
					$cpt ++;
				}
			}
		}

		echo "Nb voyages = $cpt";
	}

	function print_tab_terminus(){

		echo "<pre>";
		print_r($this->tab_terminus);
		echo "</pre>";
	}

	function print_tab_horaires(){

		echo "<pre>";
		print_r($this->tab_horaires);
		echo "</pre>";
	}

}

?>