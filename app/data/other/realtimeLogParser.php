<?php
//-----------------------------------------------------------------
// Parse a Cumulus realtime log file and return the requested fields
// along with the time stamps in a JSON format suitable for use with
// the Highcharts graphing package.
// Author: Mark Crossley
//
// Version 0.1 - 29 Nov 2012
// Version 0.2 - 03 May 2013 - added variable date format handling
//
// Call this script as e.g. ...realtimeLogParser.php?count=N&temp&dew
//  Where count=N returns the last N rows worth of data from the log file
//    if the count parameter is omitted all available records are returned.
//  Where &temp&dew returns the temperature & dew data, concatenate as
//    many record types as you wish.
//  The data arrays are returned in an object with the key name equal to
//  the param name supplied in the URL. In the example above the returned
//  object will be: {"temp": [[t0,v0],[t0,v0],...],
//                   "dew": [[t0,v0],[t1,v1],...]}
//-----------------------------------------------------------------

// The name of your realtime.txt log file
$logfile = 'realtime.log';
// The various delimiters used in your version of realtime.txt
$field_delimiter = ' ';
$date_delimiter = '-';
$time_delimiter = ':';
$date_format = 'ymd';  // valid formats are 'dmy', 'mdy', and 'ymd'

//-----------------------------------------------------------------

// Fields of realtime.txt file
// Use the same names as the corresponding web tags
$rfields = array(
    "date","time","temp","hum","dew","wspeed","wlatest","bearing",
    "rrate","rfall","press","currentwdir","beaufortnumber",
    "windunit","tempunitnodeg","pressunit","rainunit",
    "windrun","presstrendval","rmonth","ryear","rfallY","intemp","inhum","wchill",
    "temptrend","tempTH","TtempTH","tempTL","TtempTL",
    "windTM","TwindTM","wgustTM","TwgustTM",
    "pressTH","TpressTH","pressTL","TpressTL",
    "version","build","wgust","heatindex","humidex","UV","ET","SolarRad",
    "avgbearing","rhour", "forecastnumber", "isdaylight", "SensorContactLost",
    "wdir","cloudbasevalue","cloudbaseunit","apptemp","SunshineHours","CurrentSolarMax","IsSunny");

// Return the array position of the variable
function ret_rval($lookup) {
    global $rfields;
    $rtn = array_search($lookup, $rfields);

    if ($rtn !== FALSE) {
        return($rtn);
    } else {
        return("");
    }
}

// Standard Source view option check
function check_sourceview () {
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

function readLogfile($file, $delimiter) {
    $data = array();
    $handle =  @ fopen($file, "r");
    if ($handle) {
        while (!feof($handle)) {
            $buffer = fgets($handle);
            $buf_arr = explode($delimiter, $buffer);
            if ($buf_arr[0] != "") {
                $data[] = $buf_arr;
            }
        }
        fclose($handle);
    } else {
        echo "Failed to open log file.";
    }
    return $data;
}

// Strip any Byte order marker from UTF-8 format files
function rmBOM($string) {
    if (substr($string, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf)) {
        $string = substr($string, 3);
    }
    return $string;
}

// ============= Start it off... ============

// Just list the PHP source?
check_sourceview();

// Read data into array
$DATA = readLogfile($logfile, ' ');

// How many records?
$total_records = count($DATA);

// Default to returning all the data rows in the log file
$records = $total_records;

// Process the script arguments
$dataTypes = array();
foreach($_GET as $key => $value) {
    if ($key == 'count') {
        $records = $value;
    } elseif (in_array($key, $rfields)) {
        $dataTypes[] = $key;
    }
}

// Did we get any valid parameters?
if (count($dataTypes) == 0) {
    die("No valid data types supplied");
}

// Remove any BOM
rmBOM($DATA[0][0]);

// Read the required fields from $data into a series of arrays...
//
// for each required line in the data file
for ($i = max(0, $total_records - $records); $i < $total_records; $i++) {
    // send the date time to the client as Javascript millisecs since 1970 - as if it were UTC
    $t = explode($time_delimiter, $DATA[$i][1]);
    $d = explode($date_delimiter, $DATA[$i][0]);
    // gmmktime params (hr, min, sec, mon, day, yr)
    // PHP time is in seconds, multiply by 1000 to get millisecs for Javascript
    switch ($date_format) {
    case 'dmy':
        $tim = gmmktime($t[0], $t[1], $t[2], $d[1], $d[0], $d[2]) * 1000;
        break;
    case 'mdy':
        $tim = gmmktime($t[0], $t[1], $t[2], $d[0], $d[1], $d[2]) * 1000;
        break;
    case 'ymd':
        $tim = gmmktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]) * 1000;
        break;
    }
    // for each required data element
    foreach($dataTypes as $key) {
        ${"arr_" . $key}[]  = array($tim, (float)$DATA[$i][ret_rval($key)]);
    }
}

foreach($dataTypes as $key) {
    $return[$key] = ${'arr_' . $key};
}

header('Cache-Control: private');
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: text/json');

echo json_encode($return);

// ========== End of Module ===========
?>
