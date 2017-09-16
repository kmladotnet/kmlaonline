<?php
    if(isset($_SESSION['user'])){
        echo json_encode(getMyProcessedBarbequeList((int) $me['n_id'], true));
    } else {
        http_response_code(404);
    }
?>