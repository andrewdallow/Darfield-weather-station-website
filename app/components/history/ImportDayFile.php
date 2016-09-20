<?php

include ('/var/www/vhosts/darfield-weather.co.nz/httpsdocs/forbidden/b_rw_details.php');


$lf = '<br />';
$compassp = array('N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW');
$param_table = 'Dayfile';
$SITE = array();
$SITE['source'] = '/var/www/vhosts/darfield-weather.co.nz/httpsdocs/data/dayfile.json';
$param_file = "dayfile.json";
//
//
// --------- Nothing to edit below here ---------------------------
//-----------------------------------------------------------------




echo date('d/m/y - H:i:s', time()) . $lf;
echo "Importing to table: $param_table ...$lf";

// Setup the variables depending on what type of file we are importing -- Day file or Monthly Log


echo "Processing dayfile: $param_file $lf";

$CreateQuery = "CREATE TABLE $param_table (LogDate date NOT NULL ,HighWindGust decimal(4,1) NOT NULL,HWindGBear varchar(3) NOT NULL,THWindG varchar(5) NOT NULL,MinTemp decimal(5,1) NOT NULL,TMinTemp varchar(5) NOT NULL,MaxTemp decimal(5,1) NOT NULL,TMaxTemp varchar(5) NOT NULL," .
        "MinPress decimal(6,2) NOT NULL,TMinPress varchar(5) NOT NULL,MaxPress decimal(6,2) NOT NULL,TMaxPress varchar(5) NOT NULL,MaxRainRate decimal(4,1) NOT NULL,TMaxRR varchar(5) NOT NULL,TotRainFall decimal(6,2) NOT NULL,AvgTemp decimal(4,2) NOT NULL,TotWindRun decimal(5,1) NOT NULL," .
        "HighAvgWSpeed decimal(3,1),THAvgWSpeed varchar(5),LowHum varchar(3),TLowHum varchar(5),HighHum varchar(3),THighHum varchar(5),TotalEvap decimal(5,2),HoursSun decimal(3,1),HighHeatInd decimal(4,1),THighHeatInd varchar(5),HighAppTemp decimal(4,1),THighAppTemp varchar(5),LowAppTemp decimal(4,1)," .
        "TLowAppTemp varchar(5),HighHourRain decimal(4,1),THighHourRain varchar(5),LowWindChill decimal(4,1),TLowWindChill varchar(5),HighDewPoint decimal(4,1),THighDewPoint varchar(5),LowDewPoint decimal(4,1),TLowDewPoint varchar(5),DomWindDir varchar(3),HeatDegDays decimal(4,1),CoolDegDays decimal(4,1)," .
        "HighSolarRad varchar(5),THighSolarRad varchar(5),HighUV decimal(3,1),THighUV varchar(5),PRIMARY KEY(LogDate) ) COMMENT = \"Dayfile from Cumulus\"";

$StartOfInsertSQL = "REPLACE INTO $param_table (LogDate,HighWindGust,HWindGBear,THWindG,MinTemp,TMinTemp,MaxTemp,TMaxTemp,MinPress,TMinPress,MaxPress,TMaxPress,MaxRainRate,TMaxRR,TotRainFall,AvgTemp,TotWindRun,HighAvgWSpeed,THAvgWSpeed,LowHum,TLowHum,HighHum,THighHum,TotalEvap,HoursSun," .
        "HighHeatInd,THighHeatInd,HighAppTemp,THighAppTemp,LowAppTemp,TLowAppTemp,HighHourRain,THighHourRain,LowWindChill,TLowWindChill,HighDewPoint,THighDewPoint,LowDewPoint,TLowDewPoint,DomWindDir,HeatDegDays,CoolDegDays,HighSolarRad,THighSolarRad,HighUV,THighUV)";
$EndFieldCount = 45;

//Connect to Weather data database 
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $database);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!($mysqli->query("SET time_zone='+12:00'"))) {
	die("ERROR - TZ Statement");
}

//Read Realtime JSON file
$jsonData = file_get_contents($SITE['source']);

if ($jsonData) {
    //Convert JSON object to PHP associtive array
    $days = json_decode($jsonData, TRUE);
    
    
    // check if the table is available
    $res = $mysqli->query("SHOW TABLES LIKE '$param_table'");
    if ($res->num_rows == 0) {
        // no table, so create it
        echo "Table does not exist, creating it...$lf";
        $result = $mysqli->query($CreateQuery);
    }
    echo 'Inserting data... ';

    foreach ($days as $day) {
        // start building the SQL INSERT statement.
        //$date = DateTime::createFromFormat('Y-m-d', $day[0]);
        //$dateTime = $date->format("Y-m-d");
                
        //array_shift($day);
        
        $escaped_day = array();
        foreach ($day as $value) {
            $escaped_day[] = "'$value'";
        }
        
        $values = implode(",", $escaped_day); 
        
        $insert = $StartOfInsertSQL;
        $insert .= " Values($values)";
        
        if ($mysqli->query($insert) === TRUE) {
            echo "New record created successfully: " . $values . "<br>";
        } else {
            echo "Error: " . $insert . "<br>" . $mysqli->error;
        }
    }
} else {
    echo $SITE['source'] + ' does not exist.';}

$mysqli->close();

echo date('d/m/y - H:i:s', time()) . $lf;
?>
