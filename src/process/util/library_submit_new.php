<?php
if(isset($_SESSION['user'])){
    $result = json_decode(file_get_contents("php://input"), true);
    print_r($result);

    $failed = false;
    $failed_reasons = array();

    // 1. check validity of data
    $client_id = $me['n_id'];
    $book_info = json_encode($result['book']);
    $reason = $result['reason'];

    addBookRequest($client_id, $book_info, $reason);

    /*

    $date = $result->date;
    $start_hr  = $result->start_hour->value;
    $start_min = $result->start_min->value;
    $finish_hr = $result->finish_hour->value;
    $finish_min = $result->finish_min->value;
    $teacher = $result->selectedTeacher->n_id;
    $participant = $result->selectedStudent;
    $exeq_req = $result->exeq_req;
    $rest_req = $result->rest_req;
    // 2. notification

    // 3. save data
    $tmp = array();

    foreach($participant as $std){
        array_push($tmp, $std->n_id);
    }
    $participant_f = implode('|', $tmp);

    //TODO 연도 나중에 바꿔줄 것 (javascript로 받으세요)
    //function addBarbeque($date, $s_time, $f_time, $t_id, $title, $student_list, $rep_id, $rest_req="", $exeq_req="", $status)
    $barbeque->addBarbeque("2017-09-" . $date, $start_hr . ":" . $start_min, $finish_hr . ":" . $finish_min, $teacher, "임시 테스트", $participant_f, $me['n_id'], $rest_req, $exeq_req, 100);
    echo print_r($result); */
    http_response_code(200);
} else {
    http_response_code(404);
}
?>