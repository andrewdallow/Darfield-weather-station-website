<?php

include ('../../../../forbidden/b_rw_details.php');
//include ('/var/www/vhosts/darfield-weather.co.nz/httpdocs/beta/forbidden/b_rw_details.php');

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

$query = "SELECT MAX(MaxTemp), MIN(MinTemp) FROM Dayfile";

$result = $mysqli->query($query);
if (!$result) {
	die("ERROR - Bad Select Statement");
}

while($row = $result->fetch_row()) {
	$maxRec = (float)$row[0];
	$minRec = (float)$row[1];
}


$query = "SELECT UNIX_TIMESTAMP(LogDate) AS LogDate, MaxTemp, MinTemp, AvgTemp FROM Dayfile WHERE LogDate >= DATE_FORMAT(CURDATE() - INTERVAL " . $interval ." MONTH, '%Y-%m-%d') ORDER BY LogDate ASC";

$result = $mysqli->query($query);
if (!$result) {
	die("ERROR - Bad Select Statement");
}

// import the rows and put the data into arrays
while($row = $result->fetch_row()) {
	$valMax[] = (float)$row[1];
	$valMin[] = (float)$row[2];
	$valAvg[] = (float)$row[3];
	$date[] = $row[0];
}

// put into a single array
// Have to add an offset of 10 minutes to the start-date for some reason?!
$rows = array(
    "max"=>$maxRec,
    "min"=>$minRec,
    "yAxis"=>[
        "plotBands"=>[ [
            "from"=> -200,
            "to"=>0,
            "color"=> "rgba(68, 170, 213, .1)"
            ],
            [
            "from"=> -200,
            "to"=> -$minRec,
            "color"=> "rgba(68, 170, 213, .2)"
            ],
            [
                "from"=> $maxRec,
                "to"=> 200,
                "color"=> "rgba(243, 170, 68, .2)"
            ]
        ],
        "plotLines"=>[ [
                "value"=> 0,
                "color"=> "rgb(0, 0, 180)",
                "width"=> 1,
                "zIndex"=> 2
            ]
            ]
    ],
    "datasets"=>[[
        "name"=>"Min Temperature",
        "data"=>$valMin,
        "pointStart"=>$date[0] * 1000,
        "pointInterval"=> 24 * 3600 * 1000, //one day
        "type"=>"line",
        "color"=>"blue",
        "unit"=>"&degC",
        "lineWidth"=>0.7 
        ], [
        "name"=>"Max Temperature",
        "data"=>$valMax,
        "pointStart"=>$date[0] * 1000,
        "pointInterval"=> 24 * 3600 * 1000, //one day
        "type"=>"line",
        "color"=>"red",
        "unit"=>"&degC",
        "lineWidth"=>0.7
        ], [
        "name"=>"Avg Temperature",
        "data"=>$valAvg,
        "pointStart"=>$date[0] * 1000,
        "pointInterval"=> 24 * 3600 * 1000, //one day
        "type"=>"line",
        "color"=>"green",
        "unit"=>"&degC",
        "lineWidth"=>0.7,
        "visible"=> false
        ]
    ]);

$mysqli->close();

header("Content-type: text/json");
header("Cache-Control: private");
header("Cache-Control: access plus 4 hour");
echo json_encode($rows);

?>
