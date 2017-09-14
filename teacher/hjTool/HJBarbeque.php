<?php
/* Database Information
create table kmlaonline_bbq_data (
    n_id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (n_id),  식별번호
    date TINYTEXT NOT NULL, 날짜 (YYYY-DD-MM)
    start_time TINYTEXT NOT NULL, 시작 시간 (OO:OO)
    finish_time TINYTEXT NOT NULL, 종료 시간 (OO:OO)
    teacher_id BIGINT NOT NULL, FOREIGN KEY (teacher_id) REFERENCES kmlaonline_teacher_data (n_id) ON DELETE CASCADE, 담당 선생님
    title TEXT NOT NULL, 바베큐 제목(ex. OO동아리, O학년 O반)
    student_list TEXT NOT NULL, 참여 학생 리스트 (ex. 1234|1412|2312|...|1233 )
    rest_req TEXT NOT NULL, 식당 요구 사항
    exeq_req TEXT NOT NULL, 행정실 요구 사항
    rep_student_id BIGINT NOT NULL, FOREIGN KEY (rep_student_id) REFERENCES kmlaonline_member_data (n_id) ON DELETE CASCADE, 대표 학생
    tr_participated TINYTEXT, 선생님 참여 여부
    submitted_time BIGINT 제출 시간 (행정실 기준)
    status INT 바베큐 상태(제출, 선생님 승인, 행정실 승인 등등) - 100 학생 제출, 200 - 담당 선생님 승인, 300 - 행정실 승인, 400 - 급식실 확인(?)

);

*/
class HJBarbeque {

    private $db;
    private $table_data;

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = 'kmlaonline_bbq_data';
    }

    function __destruct(){
    }

    function addBarbeque($date, $s_time, $f_time, $t_id, $title, $student_list, $rep_id, $rest_req="", $exeq_req=""){
        if(!is_numeric($t_id) || !is_numeric($rep_id)) return false;
        $query = "INSERT INTO `$this->table_data` (date, start_time, finish_time, teacher_id, title, student_list, rest_req, exeq_req, rep_student_id, status) " .
        "VALUES ($date, $s_time, $f_time, $t_id, $title, $student_list, $rest_req, $exeq_req, $rep_id, 100)";
        echo $query . "\n";
        if($this->db->query($query)===true){
            $ins_id = $this->db->insert_id;
            $this->db->commit(); $this->db->autocommit(true);
            return $ins_id;
        } else{
            //echo $this->db->error;
            $this->db->rollback(); $this->db->autocommit(true);
            return false;
        }
    }
}
?>