<?php
    if(isset($_SESSION['user'])){
        echo $me['n_id'];
        http_response_code(200);
    } else {
        // TODO
    }