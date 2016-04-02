<?php

include ('../../forbidden/b_rw_details.php');

date_default_timezone_set('Pacific/Auckland');

$SITE = array();
$SITE['source'] = '../../data/realtime.json';

//Connect to Weather data database
$mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $database);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


//Associative array for realtime date used for JSON 
$data = [
    "xData" => [],
    "datasets" => [[
    [
        "size"=> ["height"=> 220],        
        "name" => "Temperature",
        "data" => [],
        "unit" => "&deg;C",
        "type" => 'line',
        "yAxis" => 0,
        "zIndex" => 3,
        "color" => "red"
    ], [
        "name" => "Dewpoint",
        "data" => [],
        "unit" => "&deg;C",
        "type" => 'line',
        "yAxis" => 0,
        "zIndex" => 2,
        "color" => "#642EFE"
    ], [
        "name" => "Wind Chill",
        "data" => [],
        "unit" => "&deg;C",
        "type" => 'line',
        "yAxis" => 0,
        "zIndex" => 1,
        "color" => "#04B4AE"
    ]
        ], [
            [
                "size"=> ["height"=> 220],
                "name" => "Wind Speed",
                "data" => [],
                "unit" => " km/h",
                "type" => 'areaspline',
                "yAxis" => 0,
                "zIndex" => 2,
                "color" => "#848484"
            ], [
                "name" => "Wind Gust",
                "data" => [],
                "unit" => " km/h",
                "type" => 'areaspline',
                "yAxis" => 0,
                "zIndex" => 1,
                "color" => "#60A91C"
            ]
        ],
        [[
        "size"=> ["height"=> 180],
        "name" => "Wind Direction",
        "data" => [],
        "unit" => "&deg;",
        "type" => 'line',
        "lineWidth"=>0,
        "yAxis" => 0,
        "color" => "#5882FA",
        "marker" => [
            "enabled" => true
        ],
        "lineWidth" => 0,
        "ymin"=>0,
        "ymax"=>360,
        "tickPositions"=>[0,90, 180, 270, 360]
            
            ]], [
            [
                "size"=> ["height"=> 200],
                "name" => "Humidity",
                "data" => [],
                "unit" => "%",
                "type" => 'line',
                "yAxis" => 0,
                "color" => "#DBA901"
            ], [
                "name" => "Barometric Pressure",
                "data" => [],
                "unit" => " hPa",
                "type" => 'line',
                "yAxis" => 1,
                "color" => "#A901DB"
            ]
        ], [[
        "size"=> ["height"=> 180],
        "name" => "Rain Fall",
        "data" => [],
        "unit" => " mm",
        "type" => 'area',
        "color" => "#2E2EFE",
        "ymin"=>0
            ]]
    ]
];

//Quary Realtime table
$sql = "SELECT * FROM realtime1";
$result = $mysqli->query($sql);
$timezone = 60 * 60 * 12 * 1000;

//Loop over quary result on insert into associative array
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $time = new DateTime($row['dateTime']);
        $data['xData'][] = $time->getTimestamp() * 1000 + $timezone;

        $data['datasets'][0][0]['data'][] = (float) $row['temp'];
        $data['datasets'][0][1]['data'][] = (float) $row['dew'];
        $data['datasets'][0][2]['data'][] = (float) $row['windChill'];

        $data['datasets'][1][0]['data'][] = (float) $row['wind'];
        $data['datasets'][1][1]['data'][] = (float) $row['gust'];

        $data['datasets'][2][0]['data'][] = (float) $row['windDir'];
        
        $data['datasets'][3][0]['data'][] = (float) $row['hum'];
        $data['datasets'][3][1]['data'][] = (float) $row['baro'];

        

        $data['datasets'][4][0]['data'][] = (float) $row['rFall'];
    }
    /* free result set */
    $result->close();
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}
$mysqli->close();

//Return the data in JSON format
header('Cache-Control: private');
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: text/json  charset=UTF-8');

echo json_encode($data, JSON_PRETTY_PRINT);
