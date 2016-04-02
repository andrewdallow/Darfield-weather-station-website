<?php

include ('../../forbidden/b_rw_details.php');

// Standard Source view option check
function check_sourceview() {
    global $SITE;

    if (isset($_GET['view']) && $_GET['view'] == 'sce') {
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

$validParam = array("temperature", "humidity", "pressure", "rainfall", "wind");
$selectedParam = array();
$tbl_name = 'dayfile';
$ret = array();

foreach ($validParam as $param) {
    if (filter_input(INPUT_GET, $param, FILTER_NULL_ON_FAILURE)) {
        $selectedParam[$param] = filter_input(INPUT_GET, $param);
    }
}

if (empty($selectedParam)) {
    die("No valid parameters supplied");
}

//Connect to Weather data database 
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $database);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

foreach ($selectedParam as $key => $param) {
    if ($key == 'temperature') {
        //Yearly Temperature Data
        if ($param == "yearly") {
             //$ret["xData"][]
            $escaped_param = $mysqli->real_escape_string($param);

            $queryYears = "SELECT DATE_FORMAT(LogDate,'%Y') FROM " . $tbl_name
                    . "GROUP BY DATE_FORMAT(LogDate,'%Y')";
            $resultYears = $mysqli->query($queryYears);

            if (!$resultYears) {
                echo "Error: " . $queryYears . "<br>" . $mysqli->error;
            }

            while ($rowYear = $resultYears->fetch_row()) {
                $currYear = $rowYear[0];
                $currYear1 = $currYear + 1;

                // construct a new query for this year
                $query = "SELECT ROUND(AVG(AvgTemp),1), MONTH(LogDate) "
                        . "FROM " . $tbl_name . "WHERE LogDate >= '"
                        . $currYear . "-01-01' AND LogDate < '" . $currYear1
                        . "-01-01' GROUP BY DATE_FORMAT(LogDate,'%c') "
                        . "ORDER BY DATE_FORMAT(LogDate,'%m')";

                $result = $mysqli->query($query);

                if (!$result) {
                    echo "Error: " . $queryYears . "<br>" . $mysqli->error;
                }
                $valTot = array();
                $m = 1;
                // import the rows and put the data into arrays
                while ($row = $result->fetch_row()) {
                    // Do we need to pad array with zeros for missing months?
                    while ($m < (int) $row[1]) {
                        $valTot[$m - 1] = 0;
                        $m++;
                    }
                    $valTot[] = (float) $row[0];
                    $m++;
                }
                $rows[] = $valTot;
                $years[] = $currYear;
                
                 $ret["datasets"][] = [
                     "name"=>"$currYear",
                     "data"=>$valTot,
                     "unit" => "&deg;C",
                     "type" => 'column',
                 ];
            }
            
                    
            foreach ($rows as $row) {
               
            }
            // put into a single array
            
            
        }
    }
}

$mysqli->close();
