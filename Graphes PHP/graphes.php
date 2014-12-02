<?PHP
$tabTerminus = Array();

$row = 0;
if (($handle = fopen("terminus.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 100, ";")) !== FALSE)
	{
		if($row > 0)
		{		
			$num = count($data);
			echo "<p> $num champs à la ligne $row: <br /></p>\n";
			for ($c=0; $c < $num; $c++)
			{
				echo $data[$c] . "<br />\n";				
				if($c > 0)
					$tabTerminus[$row-1][$c-1] = $data[$c];
			}
		}
		$row++;
    }
    fclose($handle);
	echo"<pre>";
	print_r($tabTerminus);
	echo"</pre>";
}
?>