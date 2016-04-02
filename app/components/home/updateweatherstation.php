<?php

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

include ('../../forbidden/UWS_details.php');

// Just list the PHP source?
check_sourceview();

$names = ['time', 'barometer', 'outTemp', 'windSpeed', 'windDir', 'windGust',
    'rainSumDay', 'units'];

$data = [];

if (filter_input(INPUT_GET, "PASSWORD") === $key) {
    foreach ($names as $name) {
        if (filter_input(INPUT_GET, "$name") !== null) {
            $data["$name"] = filter_input(INPUT_GET, "$name");
        } else {
            die("No '$name' parameter supplied");
        }
    }

    $json_data = json_encode($data);

    $fp = fopen('../../data/now.json', 'w');
    fwrite($fp, $json_data);
    fclose($fp);
} else {
    die("No 'key' parameter supplied");
}
?>
