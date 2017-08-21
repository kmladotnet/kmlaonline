<?php
date_default_timezone_set("Asia/Seoul");
include "presenTool/PresenTools.php";
include "presenTool/dbHandler.php";

function createNewArticle($grade, $student_name, $accuse_date="2017-08-21", $accuser=2, $article_kind=2){
    global $member;
    if($accused_id = $member->gradeName2CourtId($grade, $student_name)) echo $accused_id;
    //$article->addCourtArticle(317, "2017-08-21", 75, 3);
}
?>