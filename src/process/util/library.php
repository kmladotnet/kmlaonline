<?php
    if(isset($_SESSION['user'])){
        // 일정한 빈도와 시간에 따라서 실행되도록 하는 스크립트
        // 목적 - 데이터베이스에 등록되어 있는 계정들에 자동 로그인 후 정보 업데이트

        // 데이터베이스에 저장된 계정 정보 모두 불러오기
        $user_info = getAllLibraryUserInfo();
        $debug = array();
        // 각 계정별로 로그인 후 정보 추출
        for($i = 0; $i < count($user_info); $i++){
            $user = $user_info[$i];

            if($result = signIntoLibrary($user['id'], $user['pwd'])){
                $debug[$i] = array("status"=>"success");

                // 로그인 성공한 경우 - 대출 정보 확인, 연체 여부 확인
                // TODO - 나중에 사용자가 원하는 날짜 (예를 들어, 반납 데드라인 2일 전 / 3일 전) 지정하는 기능 추가
                // 일단 반납일 하루 전 반납 알림 + 연체 알림 구현



            } else {
                $debug[$i] = array("status"=>"login failed");

            }
        }


        http_response_code(200);
    } else {
        http_response_code(403);
    }