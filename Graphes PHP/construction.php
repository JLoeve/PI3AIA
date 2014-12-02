<?PHP
include "classes/sommet.php";

$tab_terminus = Array();
$tab_horaires = Array();
$nb_voyages;
$nb_parcouru = 0;

function lire_terminus($filename)
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
					$tabTerminus[$row-1][$c-1] = $data[$c];
			}
		}
		$row++;
    }
    fclose($handle);
/*	echo"<pre>";
	print_r($tabTerminus);
	echo"</pre>";*/
}

function lire_horaires($filename)
{
	$tab_horaires["l1"]['a']['v1']["tdep"] = "T3";
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
	$tab_horaires["l1"]['r']['v2']["voyage"] = 2;
}

function compte_voyages($tab)
{
	$cpt = 0;
	foreach($tab as $ligne)
		foreach ($ligne as $sens)
			foreach ($sens as $voyage)
				$cpt ++;
}

lire_terminus("terminus.csv");
lire_horaires("horaires.csv");

compte_voyages($tab_horaires);

$solution == "";
$bus = Array();

while($nb_parcouru < $nb_voyages)
{
	$bus_courant = Array("txt" => Array(), "distance"=>0, "temps"=>0, "voyages"=>Array());
	if (!end($bus_courant["voyages"])
	
	
}

?>