<?php
    if(isset($_SESSION['teacher_user'])){
        if(isset($_GET['status'])){
            if($_GET['status'] === '100')
                echo json_encode(getMyRequestedList($me['n_id'], 100));
            else if($_GET['status'] === '200')
                echo json_encode(getMyRequestedList($me['n_id'], 200));
        }
    } else {
        http_response_code(403);
    }
?>