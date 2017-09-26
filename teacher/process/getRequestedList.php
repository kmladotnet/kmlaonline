<?php
    if(isset($_SESSION['teacher_user'])){
        if(isset($_GET['status']) && $_GET['status'] === '100'){
            echo getMyRequestedList($me['n_id'], 100);
        } else {
            echo getMyRequestedList($me['n_id'], 200);
        }
    } else {
        http_response_code(403);
    }
?>