<?php
date_default_timezone_set("Asia/Seoul");
include "hjTool/HJTools.php";
require __DIR__ . "/hjTool/dbHandler.php";

if (isset($_SESSION['teacher_user'])) {
    $me = $teacher->getTeacher($_SESSION['teacher_user']);
    if ($me === false) {
        session_destroy();
        session_start();
    } else {
        if ($me['n_access_date'] + 60 <= time()) {
            $teacher->recordTeacherAccess($me['n_id']);
        }

    }
}

//학생 전용
function getMyProcessedBarbequeList($id, $rep = false)
{
    global $barbeque, $teacher, $member;

    $my_bbq_list = $barbeque->getMyRawBarbequeList($id, $rep);
    $arr = array();
    while ($row = $my_bbq_list->fetch_assoc()) {
        $row['teacher_name'] = $teacher->getTeacherNameById((int) $row['teacher_id']);

        $temp = explode("|", $row['student_list']);
        $st_name_arr = array();
        for ($i = 0; $i < count($temp); $i++) {
            array_push($st_name_arr, $member->getMemberNameById((int) $temp[$i]));
        }
        $row['student_name_list'] = implode("|", $st_name_arr);

        unset($row['teacher_id']);
        array_push($arr, $row);
    }

    return $arr;
}

function getMyRequestedList($id, $type)
{
    global $barbeque, $teacher, $member;

    $list = $barbeque->getBarbequeList_Teacher($id, $type);

    for ($i = 0; $i < count($list); $i++) {
        $temp = explode("|", $list[$i]['student_list']);
        $name_arr = array();
        for ($j = 0; $j < count($temp); $j++) {
            array_push($name_arr, $member->getMemberNameById((int) $temp[$j]));
        }
        $list[$i]['student_list'] = implode("|", $name_arr);

        $list[$i]['rep_student'] = $member->getMemberNameById((int) $list[$i]['rep_student_id']);
        unset($list[$i]['rep_student_id']);
    }

    return $list;
}
