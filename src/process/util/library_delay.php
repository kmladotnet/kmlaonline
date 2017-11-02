<?php
    if(isset($_SESSION['user'])){

        $n_student_id = $member->getAdditionalData($me['n_id'], 'n_student_id');

        if(getLibraryUserPwd($n_student_id) && isset($_GET['request'])) {
            $pwd = getLibraryUserPwd($n_student_id);
            $ch = signIntoLibrary($n_student_id, $pwd);
            if($ch) {
                $ch = $ch['curl_obj'];
                $tmp = explode(",", $_GET['request']);
                $attr = explode("/", $tmp[1]);
                $val = explode("/", $tmp[2]);
                $str = "";
                for($i = 0; $i < sizeof($attr); $i++){
                    $str .= $attr[$i] . "=" . $val[$i];
                    if($i != sizeof($attr) - 1) $str .= "&";
                }

                $url = 'http://lib.minjok.hs.kr/usweb/set16/USMN' . $tmp[0] . '.asp?' . $str;
                curl_setopt($ch, CURLOPT_URL, $url);

                $output = curl_exec($ch);
                $output = mb_convert_encoding($output, "UTF-8", "EUC-KR");

                $error_code = 0;
                $success = false;
                if(strpos($output, '연장횟수를')){
                    //메시지 내용 - 연장횟수를 초과하여 연장할 수 없습니다.
                    $error_code = 1;
                    //echo "연장 횟수 초과";
                } else if(strpos($output, '로그인 후')){
                    //메시지 내용 - 로그인 후 이용하시기 바랍니다.
                    $error_code = 2;
                    //echo "로그인 되어 있지 않음";
                } else if(strpos($output, '대출연장이')){
                    //메시지 내용 - 대출연장이 처리되었습니다.
                    $success = true;
                    //echo "성공";
                } else {
                    //로그인 되어 있는 상태에서 엉뚱한 요청을 보낸 경우
                    $error_code = 3;
                    //echo "알 수 없는 오류가 발생";
                }

                if($success) {
                    echo json_encode(array("status"=>"success"));
                    http_response_code(200);
                } else {
                    echo json_encode(array("status"=>"fail", "error_code"=>$error_code));
                    http_response_code(423);
                }
            } else {
                echo json_encode(array("error"=>"LOGIN_ERROR", "error_desc"=>"도서관 로그인 실패"));
                http_response_code(400);
            }
        } else {
            echo json_encode(array("error"=>"LOGIN_ERROR", "error_desc"=>"도서관 로그인 후 이용바랍니다."));
            http_response_code(400);
        }

    } else {
        http_response_code(403);
    }