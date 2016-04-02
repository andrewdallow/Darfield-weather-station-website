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

$queryYears = "SELECT DATE_FORMAT(LogDate,'%Y') FROM Dayfile GROUP BY DATE_FORMAT(LogDate,'%Y')";

// get the years for the database
$resultYears = $mysqli->query($queryYears);
if (!$resultYears) {
	die("ERROR - Bad Select Statement 1");
}

$cpt = 0;
$ret = array(
    "categories"=>["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
        "Sep", "Oct", "Nov", "Dec"],
    "datasets"=>[]
);
// for each year get the data
while($rowYear = $resultYears->fetch_row()) {
    $currYear = $rowYear[0];
    $currYear1 = $currYear + 1;

    // construct a new query for this year
    $query = "SELECT ROUND(AVG(AvgTemp),1), MONTH(LogDate) FROM Dayfile WHERE LogDate >= '" . $currYear . "-01-01' AND LogDate < '" . $currYear1 . "-01-01' GROUP BY DATE_FORMAT(LogDate,'%c') ORDER BY DATE_FORMAT(LogDate,'%m')";

    $result = $mysqli->query($query);
    if (!$result) {
		die("ERROR - Bad Select Statement query " . $currYear);
	}

    $valTot = array();
    $m = 1;
    // import the rows and put the data into arrays
    while($row = $result->fetch_row()) {
        // Do we need to pad array with zeros for missing months?
        while ($m < (int)$row[1]) {
            $valTot[$m - 1] = 0;
            $m++;
        }
        $valTot[] = (float)$row[0];
        $m++;
    }
        
        $ret['datasets'][] = [
            "name"=>"$currYear",
            "data"=>$valTot,
            "type"=>"column",
            "unit"=>"&degC"
        ];
}
$mysqli->close();

// put into a single array
//$ret = array($years, $rows);

header("Content-type: text/json");
header("Cache-Control: private");
header("Cache-Control: access plus 4 hour");
echo json_encode($ret);

