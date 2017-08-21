<?php
    $data = json_decode(file_get_contents("php://input"));
    echo $data;
?>