<?php
    if(isset($_SESSION['user'])){
        // 일정한 빈도와 시간에 따라서 실행되도록 하는 스크립트
        // 목적 - 데이터베이스에 등록되어 있는 계정들에 자동 로그인 후 정보 업데이트
        include "src/lib.php";

        $user_info = getAllLibraryUserInfo();
        print_r($user_info);

        http_response_code(200);
    } else {
        http_response_code(403);
    }
