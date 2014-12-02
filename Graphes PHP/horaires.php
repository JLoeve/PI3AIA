<?php

$row = 1;

if (($handle = fopen("horaires.csv", "r")) !== FALSE) {

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
                $t_horaires2[$iligne]['a']['v'.$ivoyage]['ligne'] = $iligne;
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

    echo "<pre>";
    print_r($t_horaires2);
    echo "</pre>";

    fclose($handle);
}
?>
