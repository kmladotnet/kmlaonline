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
                // 테스트 - 일단 반납일 하루 전 반납 알림 + 연체 알림 구현
                if($result['book_num'] > 0){
                    $temp_ch = $result['curl_obj'];
                    $user_bookList = fetchBorrowedBookList($temp_ch, $result['book_num']);

                    $temp_note_arr = array();

                    // 알림 내용 [도서관] 내일은 ---(책 제목) 외 --권 반납일입니다.
                    $urgent_count = 0;
                    $urgent_bookname = '';

                    // 알림 내용 [도서관] ---(책 제목) 외 --권이 --일 연체되셨습니다.
                    $late_count = 0;
                    $late_max_bookname  = '';
                    $late_max_date = 0;

                    for($j = 0; $j < count($user_bookList); $j++){
                        $date = new DateTime("20" . $user_bookList[$j]["return_date"]);
                        $today = new DateTime("today");
                        $diff = $today->diff($date);

                        if($diff->invert == 0 && $diff->d == 14) {
                            $urgent_count++;
                            $urgent_bookname = explode("|", $user_bookList[$j]["info"])[0];

                        }

                        if($diff->invert == 1) {
                            $late_count++;

                            if($diff->d > $late_max_date) {
                                $late_max_date = $diff->d;
                                $late_max_bookname = explode("|", $user_bookList[$j]["info"])[0];
                            }
                        }
                    }

                    if ($urgent_count > 1) {
                        $urgent_count--;
                        echo "[도서관] 내일은 {$urgent_bookname} 외 {$urgent_count}권 반납일입니다.";
                    } else if ($urgent_count == 1) {
                        echo "[도서관] 내일은 {$urgent_bookname} 도서 반납일입니다.";
                    }

                    if ($late_count > 1) {
                        $late_count--;
                        echo "[도서관] {$late_max_bookname} 외 {$late_count}권이 {$late_max_date}일 연체되셨습니다.";
                    } else if ($late_count == 1) {
                        echo "[도서관] {$late_max_bookname} - {$late_max_date}일 연체되셨습니다.";
                    }

                } else {
                    // 대출한 책의 권수가 0권이면 가볍게 스킵!
                    continue;
                }
            } else {
                $debug[$i] = array("status"=>"login failed");

            }
        }


        http_response_code(200);
    } else {
        http_response_code(403);
    }