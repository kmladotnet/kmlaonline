<?php
    if(isset($_SESSION['user'])){
        $result = file_get_contents("php://input");

        $mysqli->real_escape_string($result);
        $query = "INSERT INTO kmlaonline_outdoor_stat_data (s_content) VALUE ('$result')";

        if($mysqli->query($query)){
            echo "stat success - from server";
            http_response_code(200);
        } else {
            echo "stat failed";
            http_response_code(400);
        }

    } else {
        http_response_code(404);
    }
?>