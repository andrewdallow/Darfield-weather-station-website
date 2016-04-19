<?php

$files = array();


foreach (array_reverse(glob("../../../data/reports/NOAAYR*.txt")) as $filenameYear) {

    $fixedName = pathinfo($filenameYear, PATHINFO_FILENAME);
    $year = substr($fixedName, 6);
    

    $months = array();

    foreach (glob("/var/www/vhosts/darfield-weather.co.nz/httpdocs/data/reports/NOAAMO*$year.txt") as $filenameMonth) {
        $name = pathinfo($filenameMonth, PATHINFO_FILENAME);
        array_push($months,$name);
        //$months[] = $name;
    }
   
    array_push($files, array(
        "year"=> $fixedName,
        "months" => $months
    ));
}

header('Content-type: application/json');
echo json_encode($files);
?>
