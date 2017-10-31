<?php
    if(isset($_SESSION['user'])){
        if(isset($_GET['pwd'])) $pwd = $_GET['pwd'];
        else $pwd = '';

        // 아이디 추출 - 학생의 경우 학번임
        $n_student_id = $member->getAdditionalData($me['n_id'], 'n_student_id');
        // $login_result - curl object와 빌린 책 수를 array로 받음
        $login_result = signIntoLibrary($n_student_id, $pwd);

        if($login_result && $login_result != "login error"){

            $ch = $login_result['curl_obj'];
            $book_num = $login_result['book_num'];

            $final_array = array();
            $final_array['bookNum'] = $book_num;
            $final_array['bookList'] = fetchBorrowedBookList($ch, $book_num);
            curl_close($ch);

            echo json_encode($final_array);

        } else if($login_result == "login error"){
            echo json_encode(array("error"=>"LOGIN_ERROR", "error_desc"=>"비밀번호가 올바르지 않습니다."));
            http_response_code(400);
        } else {
            echo json_encode(array("error"=>"UNKNOWN_ERROR", "error_desc"=>"예기치 않은 문제가 발생하였습니다. 관리자에게 문의 바랍니다."));
            http_response_code(400);
        }

    } else {
        http_response_code(403);
    }
?>

