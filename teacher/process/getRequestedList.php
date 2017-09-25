<?php
    if(isset($_SESSION['teacher_user'])){
        print_r(getMyRequestedList());
    } else {
        http_response_code(403);
    }
?>