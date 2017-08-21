<?php
date_default_timezone_set("Asia/Seoul");
include "presenTool/PresenTools.php";
include "presenTool/dbHandler.php";

function createNewArticle($grade, $student_name, $accuser, $article_kind, $accuse_date="2017-08-21"){
    global $member, $accuser, $article_kind, $article;
    if($accused_id = $member->gradeName2CourtId($grade, $student_name)
        && $accuser_id = $accuser->accuserName2Id($accuser) && $article_kind_id = $article_kind->articleDesc2Id($article_kind))
        $article->addCourtArticle($accused_id, $accuse_date, $accuser, $article_kind);
}
?>