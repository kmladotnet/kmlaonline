<?php
//TODO - submit 했을 때 이미 4테이블이 다 찬 경우, 이미 선생님이 한 달에 두 번 이상 바베큐를 한 경우
    if(isset($_SESSION['user'])){
        $result = json_decode(file_get_contents("php://input"));
        print_r($result);

        $failed = false;

        // 1. check validity of data
        $date = $data->date;
        $start_hr  = $data->start_hour;
        $start_min = $data->start_min;
        $finish_hr = $data->finish_hour;
        $finish_min = $data->finish_min;
        $teacher = $data->selectedTeacher;
        $participant = $data->selectedStudent;

        // 2. notification

        // 3. save data
        $tmp = array();

        foreach($participant as $std){
            array_push($tmp, $std->n_id);
        }
        $participant_f = implode('|', $tmp);

        //TODO 연도 나중에 바꿔줄 것 (javascript로 받으세요)
        //function addBarbeque($date, $s_time, $f_time, $t_id, $title, $student_list, $rep_id, $rest_req="", $exeq_req="", $status)
        $barbeque->addBarbeque("2017" . $date, $start_hr . ":" . $start_min, $finish_hr . ":" . $finish_min, $teacher->n_id, "임시 테스트", $participant_f, $me['n_id'], "", "", 100);
        http_response_code(200);
    } else {
        http_response_code(404);
    }


?>