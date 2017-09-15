<?php
    if(isset($_SESSION['user'])){
        global $teacher;
        $result = array();

        $temp = $teacher->getAllRawTeachers();
        $result['name'] = $me['s_name'];
        $result['grade'] = $temp['n_grade'];
        $result['class'] = $temp['s_class'];
        $result['room'] = $temp['s_room'];
        $result['phone_number'] = $me['s_phone'];

        echo json_encode($result);
    }
?>