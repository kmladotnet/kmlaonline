<?php
    if(isset($_SESSION['user'])){

        $n_student_id = $member->getAdditionalData($me['n_id'], 'n_student_id');

        if(getLibraryUserInfo($n_student_id) && isset($_GET['request'])) {
            $pwd = getLibraryUserInfo($n_student_id);
            $ch = signIntoLibrary($n_student_id, $pwd);
            if($ch) {
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
                    echo "연장 횟수 초과";
                } else if(strpos($output, '로그인 후')){
                    //메시지 내용 - 로그인 후 이용하시기 바랍니다.
                    $error_code = 2;
                    echo "로그인 되어 있지 않음";
                } else if(strpos($output, '대출연장이')){
                    //메시지 내용 - 대출연장이 처리되었습니다.
                    $success = true;
                    echo "성공";
                } else {
                    //로그인 되어 있는 상태에서 엉뚱한 요청을 보낸 경우
                    $error_code = 3;
                    echo "알 수 없는 오류가 발생";
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


        /*
        $ch = curl_init();
        $url = 'http://lib.minjok.hs.kr/usweb/set16/USMN012.asp?mnid=' . $n_student_id . "&mnpw=" . $pwd;
        echo $url;
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/library/$n_student_id");
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/library');
        $headers = array(
            "Access-Control-Allow-Origin: *",
            "Content-Length: 0",
            "Connection: Keep-Alive",
            "Content-type: application/x-www-form-urlencoded;charset=EUC-kr"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output_ = curl_exec($ch);
        mb_convert_encoding($output_, "UTF-8", "EUC-KR");

        curl_setopt($ch, CURLOPT_URL, 'http://lib.minjok.hs.kr/usweb/set16/USMN000_16.asp');
        curl_setopt($ch, CURLOPT_POST, false);
        $output = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_setopt($ch, CURLOPT_URL, 'http://lib.minjok.hs.kr/usweb/set16/USMN510.asp');
        $output2 = curl_exec($ch);

        //var_dump(curl_error($ch));
        curl_close($ch);
        $encoded_output = mb_convert_encoding($output, "UTF-8", "EUC-KR");
        mb_convert_encoding($output2, "UTF-8", "EUC-KR");

        $dom = new DOMDocument('1.0', 'utf-8');
        @$dom->loadHTML($output);
        //echo $output;
        $login_box = $dom->getElementById('mbody32');
        $rm_chr = array("\n", "\r", "\t");
        if($login_box){
            echo $_GET["request"];
            if(isset($_GET["request"])) {
                echo "success - test";
            } else {
                echo json_encode(array("error"=>"UNKNOWN_ERROR", "error_desc"=>"올바른 요청이 아닙니다."));
                http_response_code(400);
            }
        } else if(strpos($output_, 'USMN610')){
            echo json_encode(array("error"=>"LOGIN_ERROR", "error_desc"=>"비밀번호가 올바르지 않습니다."));
            http_response_code(400);
        } else {
            echo json_encode(array("error"=>"UNKNOWN_ERROR", "error_desc"=>"예기치 않은 문제가 발생하였습니다. 관리자에게 문의 바랍니다."));
            http_response_code(400);
        } */
        //var_dump($login_box);
        //echo $encoded_output;
        /* for debug
        $_h = curl_init();
        curl_setopt($_h, CURLOPT_HEADER, 1);
        curl_setopt($_h, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($_h, CURLOPT_HTTPGET, 1);
        curl_setopt($_h, CURLOPT_URL, 'http://www.minjok.hs.kr' );
        curl_setopt($_h, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($_h, CURLOPT_DNS_CACHE_TIMEOUT, 2 );

        var_dump(curl_exec($_h));
        var_dump(curl_getinfo($_h));
        var_dump(curl_error($_h));
        */
    } else {
        http_response_code(403);
    }