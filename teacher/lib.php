<?php
date_default_timezone_set("Asia/Seoul");
include "hjTool/HJTools.php";
require(__DIR__ . "/hjTool/dbHandler.php");

function getMyProcessedBarbequeList($id){
    global $barbeque, $teacher;

    $my_bbq_list = $barbeque->getMyRawBarbequeList($id);
    $arr = array();
    while($row = $my_bbq_list->fetch_assoc()){
        $row['teacher_name'] = $teacher->getTeacherNameById((int) $row['teacher_id']);
        unset($row['teacher_id']);
        array_push($arr, $row);
    }

    return $arr;
}
//$teacher->addTeacher("hyeonjae", "guswo1", "김현재", "guswodkssud@naver.com", "010-3511-2376", "바베큐 마스터");
//echo $teacher->authTeacher("hyeonjae", "guswo2") + "\n";
//echo $teacher->authTeacher("hyeonjae", "guswo1") + "\n";
//echo $teacher->authTeacher("hyeonjae2", "guswo1") + "\n";
//echo "<p>test</p>";
//echo $barbeque->addBarbeque("2017-09-23", "17:30", "18:40", 1, "test bbq", "1234|1923|2212", 2010);
//echo print_r($barbeque->getBarbequeList());
//echo print_r($barbeque->getBarbequeList(1));
//echo print_r($barbeque->getBarbequeList(0 , "2017-09-23"));
?>