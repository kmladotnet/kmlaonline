<?php
    if(isset($_SESSION['user'])){
        global $member, $me;
        $result = array();

        $temp = $member->getAdditionalData($me['n_id']);
        $result['name'] = $me['s_name'];
        $result['grade'] = $temp['n_grade'];
        $result['class'] = $temp['s_class'];
        $result['room'] = $temp['s_room'];
        $result['phone_number'] = $temp['s_phone'];

        echo json_encode($temp);
    }
?>