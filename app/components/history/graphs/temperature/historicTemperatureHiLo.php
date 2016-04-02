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

$query = "SELECT MAX(MaxTemp), MIN(MinTemp), ROUND(AVG(MaxTemp), 1), ROUND(AVG(MinTemp), 1) FROM Dayfile GROUP BY MONTH(LogDate)";

$result = $mysqli->query($query);
if (!$result) {
	die("ERROR - Bad Select Statement");
}

// import the rows and put the data into arrays
while($row = $result->fetch_row()) {
	$valMax[] = (float)$row[0];
	$valMin[] = (float)$row[1];
	$valAvgMaxMin[] = array((float)$row[3], (float)$row[2]);
}

// put into a single array
$rows = array(
    "yAxis"=> [
            "plotBands"=>[ [
                "from"=> -200,
                "to"=> 0,
                'color'=> "rgba(68, 170, 213, .1)"
                ]
            ]
        ],
    "categories"=>["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
        "Sep", "Oct", "Nov", "Dec"],
    "datasets"=>[[
        "name"=>"Extreme Min",
        "data"=>$valMin,
        "type"=>"line",
        "color"=>"blue",
        "unit"=>"&degC",
        "lineWidth"=>0.7
        
    ], [
        "name"=>"Extreme Max",
        "data"=>$valMax,
        "type"=>"line",
        "color"=>"red",
        "unit"=>"&degC",
        "lineWidth"=>0.7
    ], [
        "name"=>"Avg Min-Max",
        "data"=>$valAvgMaxMin,
        "type"=>"columnrange",
        "color"=>"#0174DF",
        "unit"=>"&degC",        
        "groupPadding"=>0.1,
        "pointPadding"=>0.1,
        "borderWidth"=>0
    ]     
]       
);
$mysqli->close();

header("Content-type: text/json");
header("Cache-Control: private");
header("Cache-Control: access plus 4 hour");
echo json_encode($rows);
