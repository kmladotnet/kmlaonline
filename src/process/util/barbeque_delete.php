<?php
    if(!isset($_SESSION['user'])){
        $bid = $_GET['bid'];
        $rep_id = $_GET['rep_id'];

        if($me['n_id'] === $rep_id){
            if($barbeque->deleteBarbeque($bid)){
                echo "success";
                http_response_code(200);
            } else {
                http_response_code(220);
            }
        } else {
            http_response_code(400);
        }
    } else {
        http_response_code(404);
    }
?>