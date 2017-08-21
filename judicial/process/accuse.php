<?php
    include("../lib.php");
    $data = json_decode(file_get_contents("php://input"));
    echo $data->grade;
    echo $data->name;
?>