<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('/var/www/vhosts/darfield-weather.co.nz/httpdocs/forbidden/b_rw_details.php');

$param_retainVal = 48;
$param_retainUnit = 'hour';

date_default_timezone_set('Pacific/Auckland');
$lf = '<br />';
$SITE = array();
$SITE['source'] = '/var/www/vhosts/darfield-weather.co.nz/httpdocs/data/realtime.json';

//Connect to Weather data database
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $database);
$param_table = "realtime1";
$CreateQuery = "CREATE TABLE $param_table (dateTime datetime NOT NULL,"
        . "temp decimal(5,1),hum varchar(3),dew decimal(5,1),"
        . "wind decimal(5,1),windDir varchar(3),gust decimal(5,1),"
        . "gustDir varchar(3),baro decimal(6,2),rFall decimal(4,1),windChill decimal(5,1),"
        . "PRIMARY KEY(dateTime))";

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if ($param_retainVal > 0) {
    $DeleteEntries = "DELETE IGNORE FROM $param_table WHERE dateTime < DATE_SUB(NOW(), INTERVAL $param_retainVal $param_retainUnit )";
}

if ($param_retainVal > 0) {
    echo 'Deleting old realtime records...';
    if (!$mysqli->query($DeleteEntries)) {
        echo "Error: Failed to delete records:$lf $DeleteEntries $lf";
        echo $mysqli->error + $lf;
        
    } else {
        echo "done.$lf";
    }
}

//Read Realtime JSON file
$jsonData = file_get_contents($SITE['source']);

if ($jsonData) {
    //Convert JSON object to PHP associtive array
    $data = json_decode($jsonData, TRUE);

    // check if the table is available
    $res = $mysqli->query("SHOW TABLES LIKE '$param_table'");
    if ($res->num_rows == 0) {
        // no table, so create it
        echo "Table does not exist, creating it...$lf";
        $result = $mysqli->query($CreateQuery);
    }

    //format dateTime
    //$time = strptime($data.time, '%d-%h-$G %k:%M');
    $time = DateTime::createFromFormat('j/M/Y H:i', $data['time']);

    if (!($mysqli->query("SET time_zone='+12:00'"))) {
        die("ERROR - TZ Statement");
    }

    //Extract the array values
    $dateTime = $time->format("Y-m-d H:i:s");
    $temp = $data['outTemp']["value"];
    $hum = $data['humidity']["value"];
    $dew = $data['dewpoint']["value"];
    $wind = $data['windSpeed']["value"];
    $windDir = $data['windDir'];
    $gust = $data['windGust']["value"];
    $gustDir = $data['windGustDir'];
    $baro = $data['barometer']["value"];
    $rFall = $data['rainSumDay']["value"];
    $windChill = $data['windchill']["value"];

    $sql = "INSERT INTO $param_table (dateTime, temp, hum, dew,"
            . " wind, windDir, gust, gustDir, baro, rFall, windChill)"
            . " VALUES ('$dateTime', '$temp', '$hum', '$dew', '$wind', '$windDir',"
            . " $gust, $gustDir, $baro, $rFall, $windChill)";

    if ($mysqli->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
} else {
    echo $SITE['source'] + ' does not exist.';
}

$mysqli->close();
