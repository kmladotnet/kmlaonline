<?php
    $final = array();
    $test = array("text" => "김현재");
    array_push($final, $test);
    $test = array("text" => "조성민");
    array_push($final, $test);
    $test = array("text" => "심무경");
    array_push($final, $test);
    $test = array("text" => "김명순");
    array_push($final, $test);
    $test = array("text" => "조성현");
    array_push($final, $test);
    $test = array("text" => "강건");
    array_push($final, $test);
    $test = array("text" => "김ㅅ김");
    array_push($final, $test);
    echo json_encode($final);
?>