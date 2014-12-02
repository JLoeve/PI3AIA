<?php
// Retourne la liste des sommets

$tab_terminus = Array();

$minTemps = 56;
$liaisonTerminus = 5;
$hArrivee = 810;
$hDepart = 820;

$tab_terminus = lire_terminus("terminus.csv");

$minTemps = voyageLePlusProche($hArrivee, $hDepart, $liaisonTerminus, $minTemps);

function voyageLePlusProche($heureArrivee, $heureDepart){

	global $tab_terminus;
	$distTerminus = $tab_terminus[1][2];
	echo "Arrivee_S1:$heureArrivee <br> Depart_S2:$heureDepart <br> Liaison:$distTerminus<br>";

	$heureFinale = $heureArrivee + $distTerminus;
	$tempsSup = $heureDepart - $heureFinale;

	if(($tempsSup+5) >= 5 ){ 
		
		echo "Ok pour le trajet :  ".$tempsSup." min d'attente";
	
	}else{
		echo "Pas le temps : ".($heureDepart-$heureFinale);
	}
	
	return $tempsSup;

}




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






?>