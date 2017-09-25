<?php
    if(isset($_SESSION['teacher_user'])){
        print_r($barbeque->getBarbequeList_Teacher(4, 100));
    } else {
        http_response_code(403);
    }
?>