<?PHP
include "classes/sommet.php";

$tab_terminus = Array();
$tab_horaires = Array();
$nb_voyages;
$nb_parcouru = 0;

function lire_terminus($filename)
{
	$tab = "";
	$row = 0;
	if (($handle = fopen($filename, "r")) !== FALSE) 
	{
		while (($data = fgetcsv($handle, 100, ",")) !== FALSE)
		{
			if($row > 0)
			{		
				$num = count($data);
				//echo "<p> $num champs à la ligne $row: <br /></p>\n";
				for ($c=0; $c < $num; $c++)
				{
					//echo $data[$c] . "<br />\n";				
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


function lire_horaires($filename)
{
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

		//echo "<pre>";
		//print_r( $t_horaires["l1"]["a"] );
		//echo "</pre>";

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
					$t_horaires2[$iligne]['a']['v'.$ivoyage]['tdep'] = $tdep;
					$t_horaires2[$iligne]['a']['v'.$ivoyage]['tarr'] = $tarr;
					$t_horaires2[$iligne]['a']['v'.$ivoyage]['sens'] = 'a';
					$t_horaires2[$iligne]['a']['v'.$ivoyage]['ligne'] = substr($iligne, 1);
					$t_horaires2[$iligne]['a']['v'.$ivoyage]['voyage'] = $ivoyage;
					$t_horaires2[$iligne]['a']['v'.$ivoyage]['parcouru'] = 0;
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
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['tdep'] = $tdep;
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['tarr'] = $tarr;
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['sens'] = 'r';
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['ligne'] = substr($iligne, 1);
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['voyage'] = $ivoyage;
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['parcouru'] = 0;
					$t_horaires2[$iligne]['r']['v'.$ivoyage]['dist'] = $ligne['r']["dist"][$ivoyage];
					
				}
			}
		}

/*		echo "<pre>";
		print_r($t_horaires2);
		echo "</pre>";*/

		fclose($handle);
	}
	return $t_horaires2;

	/*$tab_horaires["l1"]['a']['v1']["tdep"] = "T3";
	$tab_horaires["l1"]['a']['v1']["tarr"] = "T4";
	$tab_horaires["l1"]['a']['v1']["hdep"] = 469;
	$tab_horaires["l1"]['a']['v1']["hdep"] = 492;
	$tab_horaires["l1"]['a']['v1']["dist"] = 10;
	$tab_horaires["l1"]['a']['v1']["sens"] = 'a';
	$tab_horaires["l1"]['a']['v1']["ligne"] = 1;
	$tab_horaires["l1"]['a']['v1']["voyage"] = 1;
	
	$tab_horaires["l1"]['r']['v1']["tdep"] = "T4";
	$tab_horaires["l1"]['r']['v1']["tarr"] = "T3";
	$tab_horaires["l1"]['r']['v1']["hdep"] = 498;
	$tab_horaires["l1"]['r']['v1']["hdep"] = 523;
	$tab_horaires["l1"]['r']['v1']["dist"] = 10;
	$tab_horaires["l1"]['r']['v1']["sens"] = 'r';
	$tab_horaires["l1"]['r']['v1']["ligne"] = 1;
	$tab_horaires["l1"]['r']['v1']["voyage"] = 1;
	
	$tab_horaires["l1"]['a']['v2']["tdep"] = "T2";
	$tab_horaires["l1"]['a']['v2']["tarr"] = "T4";
	$tab_horaires["l1"]['a']['v2']["hdep"] = 635;
	$tab_horaires["l1"]['a']['v2']["hdep"] = 649;
	$tab_horaires["l1"]['a']['v2']["dist"] = 10;
	$tab_horaires["l1"]['a']['v2']["sens"] = 'a';
	$tab_horaires["l1"]['a']['v2']["ligne"] = 1;
	$tab_horaires["l1"]['a']['v2']["voyage"] = 2;
	
	$tab_horaires["l1"]['r']['v2']["tdep"] = "T4";
	$tab_horaires["l1"]['r']['v2']["tarr"] = "T2";
	$tab_horaires["l1"]['r']['v2']["hdep"] = 621;
	$tab_horaires["l1"]['r']['v2']["hdep"] = 636;
	$tab_horaires["l1"]['r']['v2']["dist"] = 10;
	$tab_horaires["l1"]['r']['v2']["sens"] = 'r';
	$tab_horaires["l1"]['r']['v2']["ligne"] = 1;
	$tab_horaires["l1"]['r']['v2']["voyage"] = 2;*/
}

function compte_voyages($tab)
{
	$cpt = 0;
	foreach($tab as $ligne)
		foreach ($ligne as $sens)
			foreach ($sens as $voyage)
				$cpt ++;
	return $cpt;
}

$tab_terminus = lire_terminus("terminus.csv");
/*		echo"<pre>";
		print_r($tab_terminus);
		echo"</pre>";*/
		
$tab_horaires = lire_horaires("horaires.csv");
	/*	echo "<pre>";
		print_r($tab_horaires);
		echo "</pre>";*/

$nb_voyages = compte_voyages($tab_horaires);	
		echo "<pre>";
		print_r($nb_voyages);
		echo "</pre>";
		
$graphe = Array();
$cpt = 0;
	foreach($tab_horaires as $ligne)
		foreach ($ligne as $sens)
			foreach ($sens as $voyage)
			{
				$tmp_som = new Sommet($voyage);
				$tmp_som->set_id($cpt);
				$graphe[] = $tmp_som;
				$cpt++;				
			}
	
foreach($graphe as $sommet)
{	
	foreach ($graphe as $voisin)
	{
		if($sommet->get_id() != $voisin->get_id())
			$sommet->ajouter_voisin($voisin);
	}
}
echo "<pre>";
print_r($graphe);
echo "</pre>";		
/*
$solution == "";
$bus = Array();

while($nb_parcouru < $nb_voyages)
{
	$bus_courant = Array("txt" => Array(), "distance"=>0, "temps"=>0, "voyages"=>Array());
	if (!end($bus_courant["voyages"])
	
	
}
*/
?>