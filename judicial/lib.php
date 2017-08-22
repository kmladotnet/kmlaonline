<?php
date_default_timezone_set("Asia/Seoul");
include "presenTool/PresenTools.php";
include "presenTool/dbHandler.php";

function createNewArticle($grade, $accused_name, $accuser_name, $article_kind_name, $accuse_date="2017-08-21"){
    global $member, $accuser, $article_kind, $article;
    echo $member->gradeName2CourtId($grade, $accused_name) . "<br />";
    echo $accuser->accuserName2Id($accuser_name) . "<br />";
    echo $article_kind->articleDesc2Id($article_kind_name) . "<br />";
    if(($accused_id = $member->gradeName2CourtId($grade, $accused_name))
        && ($accuser_id = $accuser->accuserName2Id($accuser_name)) && ($article_kind_id = $article_kind->articleDesc2Id($article_kind_name))){
        echo $member->gradeName2CourtId($grade, $accused_name) . "<br />";
        echo " $accused_id $accuser_id $article_kind_id $accuse_date";
        if($article->addCourtArticle((int) $accused_id, $accuse_date, (int) $accuser_id, (int) $article_kind_id)) echo "OKAY";
        else echo "NOT OKAY";
    }
    else{
        echo "ERROR OCCURED - createNewArticle";
    }
}

function getAllArticles(){
    global $member, $accuser, $article_kind, $article;
    if($raw = $article->getAllRawArticles()){
        $result = array();
        while($row = $raw->fetch_assoc()){
            $temp_grade = $member->courtId2GradeName((int) $row['accused_id'])['grade'];
            $temp_name = $member->courtId2GradeName((int) $row['accused_id'])['name'];
            $temp_accuser = $accuser->accuserId2Name((int) $row['accuser_id']);
            $temp_article = $article_kind->articleId2Desc((int) $row['ak_id']);
            $temp_point = $article_kind->articleId2Point((int) $row['ak_id']);

            $temp = array('grade' => $temp_grade,
                    'name' => $temp_name,
                    'accused_date' => $row['accused_date'],
                    'accuser' => $temp_accuser,
                    'article' => $temp_article,
                    'point' => $temp_point);
            array_push($result, $temp);
        }
        return $result;
    } else {
        echo "ERROR OCCURED - getAllArticles";
    }
}

function getAllAccusers(){
    global $accuser;
    if($raw = $accuser->getAllRawAccusers()){
        $result = array();
        $temp = array();
        while($row = $raw->fetch_assoc()){
            $temp['a_id'] = $row['a_id'];
            $temp['name'] = $row['name'];
            array_push($result, $temp);
        }
        return $result;
    } else {
        echo "ERROR OCCURED - getAllAccusers";
    }
}

function getAllArticleKinds(){
    global $article_kind;
    if($raw = $article_kind->getAllRawArticles()){
        $result = array();
        $temp = array();
        while($row = $raw->fetch_assoc()){
            $temp['ak_id'] = $row['a_id'];
            $temp['ak_eng'] = $row['ak_eng'];
            $temp['point'] = $row['point'];
            array_push($result, $temp);
        }
        return $result;
    } else {
        echo "ERROR OCCURED - getAllArticleKinds";
    }
}

function suggestMemberByQuery($query){
    global $member;
    return json_encode($member->searchMember($query));
}
?>