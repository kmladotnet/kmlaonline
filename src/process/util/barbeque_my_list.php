<?php
    if(isset($_SESSION['user'])){
        echo json_encode(getMyProcessedBarbequeList((int) $me['n_id']));
    } else {
        http_response_code(404);
    }
?>