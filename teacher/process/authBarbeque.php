<?php
    if(isset($_SESSION['teacher_user'])){
        $result = json_decode(file_get_contents("php://input"));
        print_r($result);
        if($barbeque->getBarbequeById((int) $result->id)){
            if($barbeque->getBarbequeById((int) $result->id)['teacher_id'] === $me['n_id']){
                if($result->answer === 'yes') {
                    if($barbeque->acceptBarbeque((int) $result->id)){
                        echo "success!!";
                        http_response_code(200);
                    } else {
                        echo "Failed!!";
                        http_response_code(300);
                    }
                } else if($result->answer === 'no') {
                    if($barbeque->declineBarbeque((int) $result->id)){
                        echo "success!";
                        http_response_code(200);
                    } else {
                        echo "Failed!";
                        http_response_code(300);
                    }
                }
            }
        } else {
            http_response_code(400);
        }
    } else {
        http_response_code(403);
    }
?>