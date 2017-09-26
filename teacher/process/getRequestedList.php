<?php
    if(isset($_SESSION['teacher_user'])){
        print_r(getMyRequestedList($me['n_id'], 100));
    } else {
        http_response_code(403);
    }
?>