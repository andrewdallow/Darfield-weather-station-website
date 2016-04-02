<?php

$files = array();

foreach (glob("../../data/webcam_img/webcamimage0.jpg") as $filename) {
    
    $fixedName = str_replace('../', '',$filename);
    
    $files[] = array(
        "name"=>$fixedName,
        "time"=>filemtime($filename)            
    );    
}

header('Content-type: application/json');
echo json_encode($files);
?>
