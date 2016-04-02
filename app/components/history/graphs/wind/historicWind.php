<?php

//include ('/var/www/vhosts/darfield-weather.co.nz/httpdocs/forbidden/b_rw_details.php');
include ('../../../../forbidden/b_rw_details.php');

// Standard Source view option check
function check_sourceview () {
    global $SITE;

    if ( isset($_GET['view']) && $_GET['view'] == 'sce' ) {
        $filenameReal = __FILE__;
        $download_size = filesize($filenameReal);
        header('Pragma: public');
        header('Cache-Control: private');
        header('Cache-Control: no-cache, must-revalidate');
        header("Content-type: text/plain");
        header("Accept-Ranges: bytes");
        header("Content-Length: $download_size");
        header('Connection: close');
        readfile($filenameReal);
        exit;
    }
}

// Just list the PHP source?
check_sourceview();

if (filter_input(INPUT_GET, "months")) {
        $interval = filter_input(INPUT_GET, "months");
    } else {
	die("No 'months' parameter supplied");
}

if ($interval == "") {
	die("Invalid number of months specified");
}

//Connect to Weather data database 
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $database);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

#
# The db querys
#

if (!($mysqli->query("SET time_zone='+12:00'"))) {
	die("ERROR - TZ Statement");
}

//$query = "SELECT UNIX_TIMESTAMP(LogDate) AS LogDate, HighWindGust, HighAvgWSpeed, TotWindRun FROM Dayfile WHERE LogDate >= DATE_FORMAT(CURDATE() - INTERVAL " . $interval ." MONTH, '%Y-%m-%d') ORDER BY LogDate ASC";
$query = "SELECT UNIX_TIMESTAMP(LogDate) AS LogDate, HighWindGust, HighAvgWSpeed FROM Dayfile WHERE LogDate >= DATE_FORMAT(CURDATE() - INTERVAL " . $interval ." MONTH, '%Y-%m-%d') ORDER BY LogDate ASC";

$result = $mysqli->query($query);
if (!$result) { printf ("ERROR - Bad Select Statement"); exit; }

// import the rows and put the data into arrays
while($row = $result->fetch_row()) {
	$title[]   = (float)$row[0];
	$valGust[] = (float)$row[1];
	$valAvg[]  = (float)$row[2];
}
$mysqli->close();
$rows = array(
    "datasets"=>[[
        "name"=>"Avg Speed",
        "data"=>$valAvg,
        "pointStart"=>$title[0] * 1000,
        "pointInterval"=> 24 * 3600 * 1000, //one day
        "type"=>"area",
        "color"=>"#60A91C",
        "unit"=>" km/h",
        "fillOpacity"=> 0.6
        
        
        ], [
        "name"=>"Max Gust Speed",
        "data"=>$valGust,
        "pointStart"=>$title[0] * 1000,
        "pointInterval"=> 24 * 3600 * 1000, //one day
        "type"=>"area",
        "color"=>"red",
        "fillOpacity"=> 0.1,
        "unit"=>" km/h"
        ]
    ]);


// put into a single array
// Have to add a offset of 10 minutes to the start-date for some reason?!

header("Content-type: text/json");
header("Cache-Control: private");
header("Cache-Control: access plus 4 hour");
echo json_encode($rows);
