<?php
    if(isset($_SESSION['teacher_user'])){
        echo "success";
    } else {
        http_response_code(403);
    }
?>