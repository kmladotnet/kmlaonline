<?php
date_default_timezone_set("Asia/Seoul");
include "presenTool/PresenTools.php";
include "presenTool/dbHandler.php";

function createNewArticle($grade, $accused_name, $accuser_name, $article_kind_name, $accuse_date="2017-08-21"){
    global $member, $accuser, $article_kind, $article;
    if($accused_id = $member->gradeName2CourtId($grade, $accused_name)
        && $accuser_id = $accuser->accuserName2Id($accuser_name) && $article_kind_id = $article_kind->articleDesc2Id($article_kind_name)){
        echo $member->gradeName2CourtId($grade, $accused_name) . "<br />";
        echo " $accused_id $accuser_id $article_kind_id $accuse_date";
        if($article->addCourtArticle($accused_id, $accuse_date, $accuser_id, $article_kind_id)) echo "OKAY";
        else echo "NOT OKAY";
    }
    else echo "ERROR OCCURED";
}
?>