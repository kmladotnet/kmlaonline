<?php
    if(isset($_SESSION['user'])){
        if(isset($_GET['pwd'])) $pwd = $_GET['pwd'];
        else $pwd = '';

        // 아이디 추출 - 학생의 경우 학번임
        $n_student_id = $member->getAdditionalData($me['n_id'], 'n_student_id');
        $login_result = signIntoLibrary($n_student_id, $pwd);

        if($login_result && $login_result != "login error"){

            $ch = $login_result['curl_obj'];
            $book_num = $login_result['book_num'];

            //curl_setopt($ch, CURLOPT_URL, 'http://lib.minjok.hs.kr/usweb/set16/USMN510.asp');
            //$output2 = curl_exec($ch);

            curl_close($ch);
            /*
            mb_convert_encoding($output2, "UTF-8", "EUC-KR");


            $dom2 = new DOMDocument('1.0', 'utf-8');
            @$dom2->loadHTML($output2);
            $table = $dom2->getElementsByTagName('table');
            $rows = $table->item(1)->getElementsByTagName('tr');

            $tmp_arr = array();
            $bar_chr = array("\n\n\n", ' / ');
            $rm_chr_1 = array("\r", "\t");
            $rm_chr_2 = array("\n\n", "\n");
            $name_array = array("number", "info", "borrow_date", "invalid1", "return_date", "status", "institution", "delay_info");
            for($i = 0; $i < (int) $book_num; $i++){
                $tmp = array();
                $row = $rows->item($i + 1);
                $items = $row->getElementsByTagName('td');

                for($j = 0; $j < 8; $j++){
                    if($j == 3) continue;
                    if($j == 7) {
                        $str = $items->item($j)->getElementsByTagName('a')->item(0)->getAttribute("onclick");
                        //funcPmove("511","libno/bookkind/bookno","X/XX/XXXXX"); 반환
                        $str = str_replace("\"", "", substr($str, 10, strlen($str) - 12));
                        // 문자열 511,libno/bookkind/bookno,X/XX/XXXXX 반환
                        $tmp[$name_array[$j]] = $str;
                    } else {
                        $str = str_replace($rm_chr_1, "", $items->item($j)->nodeValue);
                        $str = str_replace($bar_chr, "|", $str);
                        $tmp[$name_array[$j]] = str_replace($rm_chr_2, "", $str);
                    }
                }
                array_push($tmp_arr, $tmp);
            } */

            $final_array = array();
            $final_array['bookNum'] = $book_num;
            $final_array['bookList'] = fetchBorrowedBookList($ch, $book_num);

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

