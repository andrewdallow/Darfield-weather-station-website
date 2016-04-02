<?php
//-----------------------------------------------------------------
// Query a Cumulus realtime MySQL table and return the requested fields
// along with the time stamps in a JSON format suitable for use with
// the Highcharts graphing package.
// Author: Mark Crossley
//
// Version 0.3 - 27 May 2013
// MODDED 01 July 2013 - Added custom UV_AVG column
//
// Call this script as e.g. ...
//         realtimeLogTable.php?recordAge=NNN&recordUnit=XXX&nth=NNN&temp&press
//         realtimeLogTable.php?recordAge=6&recordUnit=hour&nth=5&temp&press
//  Where:
//    recordAge=NNN
//		Returns records newer than NNN recordUnits, 6 hours in this example
//    recordUnit=XXX
//		The units to use for recordAge (second,minute,hour,day,week,month,quarter,year)
//    nth=NNN [OPTIONAL parameter]
//		If supplied the script will only return every n'th row from the realtime table, otherwise all
//      rows within the time range are returned. Useful for reducing large data sets for presentation.
//      This example will return every 5th row from the table
//    &temp&press
//		Returns the temperature and pressure data - concatenate as many record types as you wish.
//
//  The data arrays are returned in an object with the key name equal to
//  the param name supplied in the URL. In the example above the returned
//  object will be: {"temp": [[t0,v0],[t1,v1],...],
//                   "press": [[t0,v0],[t1,v1],...]}
//-----------------------------------------------------------------

// EDIT THIS NEXT SECTION CAREFULLY
// ----------------------------------------------------------------
// The server host name or number running your MySQL database
// usually 127.0.0.1 or localhost will suffice
//$dbhost = '127.0.0.1';
//
// The username used to log-in to your database server
//$dbuser = 'user';
//
// The password used to log-in to your database server
//$dbpassword = 'password';
//
// The name of the MySQL database we will store the tables in
//$database = 'database';
//
// A better way of entering your login details is to put them in a separate script
// and include this here. This script should be placed in an area accessible to PHP
// but not web users. Your login details will not then beexposed by crashing this
// script.
// e.g. ...
include ('/var/www/vhosts/darfield-weather.co.nz/httpdocs/forbidden/b_rw_details.php');

// The name of your realtime log table
$logtable = 'Realtime';

//-----------------------------------------------------------------

// Acceptable recordUnit parameter values
$validunits = array('second', 'minute', 'hour', 'day', 'week', 'month', 'quarter', 'year');

// Columns of realtime log table
// Use the same names as the corresponding web tags
$rfields = array(
    'datetime','temp','hum','dew','wspeed','wlatest','bearing',
    'rrate','rfall','press','currentwdir','beaufortnumber',
    'windunit','tempunitnodeg','pressunit','rainunit',
    'windrun','presstrendval','rmonth','ryear','rfallY','intemp','inhum','wchill',
    'temptrend','tempTH','TtempTH','tempTL','TtempTL',
    'windTM','TwindTM','wgustTM','TwgustTM',
    'pressTH','TpressTH','pressTL','TpressTL',
    'version','build','wgust','heatindex','humidex','UV','ET','SolarRad',
    'avgbearing','rhour', 'forecastnumber', 'isdaylight', 'SensorContactLost',
    'wdir','cloudbasevalue','cloudbaseunit','apptemp','SunshineHours','CurrentSolarMax','IsSunny',
    'UV_AVG');

// Standard Source view option check
function check_sourceview () {
    global $SITE;
    if (isset($_GET['view']) && $_GET['view'] == 'sce') {
        $filenameReal = __FILE__;
        $download_size = filesize($filenameReal);
        header('Pragma: public');
        header('Cache-Control: private');
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: text/plain');
        header('Accept-Ranges: bytes');
        header("Content-Length: $download_size");
        header('Connection: close');
        readfile($filenameReal);
        exit;
    }
}

// ============= Start it off... ============

// Just list the PHP source?
check_sourceview();

// check parameters (just presence, no value checking)
if (array_key_exists('recordAge', $_GET)) {
	$recordAge = $_GET['recordAge'];
} else {
	die('You must supply a "recordAge" parameter');
}

if (array_key_exists('recordUnit', $_GET)) {
	$recordUnit = strtolower($_GET['recordUnit']);
	if (!in_array($recordUnit, $validunits)) {
		die("invalid 'recordUnit' supplied - '$recordUnit'");
	}
} else {
		die('You must supply a "recordVal" parameter');
}

if (array_key_exists('nth', $_GET)) {
	$nth = $_GET['nth'];
} else {
	$nth = '';
}

// Process the script value arguments
$dataTypes = array();
foreach($_GET as $key => $value) {
    if (in_array($key, $rfields)) {
        $dataTypes[] = $key;
    }
}

// Did we get any valid parameters?
if (count($dataTypes) == 0) {
    die('No valid return data types supplied');
}

// Connect to the datbase
$con = mysql_connect($dbhost, $dbuser, $dbpassword);
if (!$con) {
	die('Failed to connect to the database server');
}

if (!mysql_select_db($database, $con)) {
	die('Failed to connect to the database on the server');
}

$result = mysql_query('SET time_zone="+0:00"');
if (!$result) {
	die('ERROR - TZ Statement');
}

// Was the 'nth' parameter supplied? If not, do a standard query, else, only return every nth row from the table
if ($nth === '') {

	// Start of query string, then...
	$query = 'SELECT UNIX_TIMESTAMP(LogDateTime)';

	// add query data columns, then..
	foreach($dataTypes as $key) {
		$query .= ', ' . $key;
	}

	// end of query string.
//	$query .= " FROM $logtable WHERE LogDateTime > DATE_SUB(NOW(), INTERVAL $recordAge $recordUnit) ORDER BY LogDateTime ASC";
	$query .= " FROM $logtable WHERE LogDateTime > (SELECT MAX(LogDateTime) - INTERVAL $recordAge $recordUnit FROM $logtable) ORDER BY LogDateTime ASC";

} else {  // Only return every nth row

	// Start of query string, then...
	$query = 'SELECT ts';

	// add query data columns, then..
	foreach($dataTypes as $key) {
		$query .= ', ' . $key;
	}
	$query .= ' FROM ( SELECT @row := @row +1 AS rownum, UNIX_TIMESTAMP(LogDateTime) AS ts';

	// add query data columns, then..
	foreach($dataTypes as $key) {
		$query .= ', ' . $key;
	}
	// end of query string.
//	$query .= " FROM $logtable WHERE LogDateTime > DATE_SUB(NOW(), INTERVAL $recordAge $recordUnit) ORDER BY LogDateTime ASC) as DT WHERE MOD(rownum,$nth)=0";
	$query .= " FROM $logtable WHERE LogDateTime > (SELECT MAX(LogDateTime) - INTERVAL $recordAge $recordUnit FROM $logtable) ORDER BY LogDateTime ASC) as DT WHERE MOD(rownum,$nth)=0";

	// initialise the row counter
	$result = mysql_query('SET @row := 0;');
	if (!$result) {
		die("ERROR - Bad SQL Statement: $query");
	}
}

// Perform query
$result = mysql_query($query);
if (!$result) {
	die("ERROR - Bad Select Statement: $query");
}

// for each of the returned rows, put the data into a series of arrays...
while($row = mysql_fetch_row($result)) {
	$tim = $row[0] * 1000;
	$i = 1;
	foreach($dataTypes as $key) {
        ${"arr_$key"}[]  = array($tim, (float)$row[$i++]);
    }
}

// build the return array
foreach($dataTypes as $key) {
    $return[$key] = ${'arr_' . $key};
}

header('Cache-Control: private');
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: text/json  charset=UTF-8');

// encode the result and echo it back to the client
echo json_encode($return);

// ========== End of Module ===========
?>
