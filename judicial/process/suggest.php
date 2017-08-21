<?php
    $final = array();
    $test = array("name" => "김현재");
    array_push($final, $test);
    $test = array("name" => "조성민");
    array_push($final, $test);
    $test = array("name" => "심무경");
    array_push($final, $test);
    $test = array("name" => "김명순");
    array_push($final, $test);
    $test = array("name" => "조성현");
    array_push($final, $test);
    $test = array("name" => "강건");
    array_push($final, $test);
    echo json_encode($final);
?>