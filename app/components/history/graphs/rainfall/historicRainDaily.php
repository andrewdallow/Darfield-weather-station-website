<?php

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

$query = "SELECT UNIX_TIMESTAMP(LogDate) AS LogDate, TotRainFall FROM Dayfile WHERE LogDate >= DATE_FORMAT(CURDATE() - INTERVAL " . $interval ." MONTH, '%Y-%m-%d') ORDER BY LogDate ASC";

$result = $mysqli->query($query);
if (!$result) {
	die("ERROR - Bad Select Statement");
}

// import the rows and put the data into arrays
while($row = $result->fetch_row()) {
	$date[] = (float)$row[0];
	$valTot[] = (float)$row[1];
}
$mysqli->close();

$rows = array(
    "datasets"=>[[
        "name"=>"Daily Rainfall",
        "data"=>$valTot,
        "pointStart"=>$date[0] * 1000,
        "pointInterval"=> 24 * 3600 * 1000, //one day
        "type"=>"column",
        "unit"=>" mm",
        "color"=>"#0174DF"
        ]
    ]);

header("Content-type: text/json");
header("Cache-Control: private");
header("Cache-Control: access plus 4 hour");
echo json_encode($rows);
