<?php
date_default_timezone_set("Asia/Seoul");

/**
article status constant
ARTICLE_STATUS_FD 최변: 53788
ARTICLE_STATUS_RT 재판결: 37084
ARTICLE_STATUS_ORD 일반 판결: 26124
ARTICLE_STATUS_CP 법정 진행자: 19997
*/

define('ARTICLE_STATUS_CP', 53788);
define('ARTICLE_STATUS_ORD', 37084);
define('ARTICLE_STATUS_RT', 26124);
define('ARTICLE_STATUS_FD', 19997);

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
            $temp_status = (int) $row['status'];
            $temp = array('grade' => $temp_grade,
                    'name' => $temp_name,
                    'accused_date' => $row['accused_date'],
                    'accuser' => $temp_accuser,
                    'article' => $temp_article,
                    'point' => $temp_point,
                    'status' => $temp_status);
            array_push($result, $temp);
        }
        return $result;
    } else {
        echo "ERROR OCCURED - getAllArticles";
    }
}

function getAllSortedArticles(){
    global $article_kind, $article, $accuser;
    $process = getAllProcessingArticles();
    uasort($process, 'article_cmp');
    $result = array();
    foreach ($process as $key => $value) {
        for($t = 0; $t < count($value['article_array']); $t++){
            $temp_grade = $value['article_array'][$t]['grade'];
            $temp_name = $value['article_array'][$t]['name'];
            $temp_accuse_date = $article->getDateById($value['article_array'][$t]['article']);
            $temp_accuser = $accuser->accuserId2Name((int) $article->getAccuserById((int) $value['article_array'][$t]['article']));
            $temp_article = $article_kind->articleId2Desc((int) $value['article_array'][$t]['article_kind']);
            $temp_point = $value['article_array'][$t]['point'];
            $temp_status = $value['status'];
            $temp = array('grade' => $temp_grade,
                    'name' => $temp_name,
                    'accused_date' => $temp_accuse_date,
                    'accuser' => $temp_accuser,
                    'article' => $temp_article,
                    'point' => $temp_point,
                    'status' => $temp_status);
            array_push($result, $temp);
        }
    }

    echo json_encode($result);
    return $result;


}

function article_cmp($ar1, $ar2){
    if($ar1['status'] === $ar2['status']){
        if(count($ar1['article_array']) === count($ar2['article_array'])) {
            $sum1 = 0;
            $sum2 = 0;
            $points_1 = array();
            $points_2 = array();
            for($i = 0; $i < count($ar1['article_array']); $i++){
                $sum1 += $ar1['article_array'][$i]['point'];
                array_push($points_1, $ar1['article_array'][$i]['article_kind']);
                $sum2 += $ar2['article_array'][$i]['point'];
                array_push($points_2, $ar2['article_array'][$i]['article_kind']);
            }
            if($sum1 === $sum2){
                if(count(array_diff(array_merge($points_1, $points_2), array_intersect($points_1, $points_2))) === 0) {
                    if($ar1['article_array'][0]['grade'] === $ar2['article_array'][0]['grade']) {
                        if($ar1['article_array'][0]['name'] === $ar2['article_array'][0]['name']) {
                            return 0;
                        } else {
                            return strcmp($ar1['article_array'][0]['name'], $ar2['article_array'][0]['name']);
                        }
                    } else {
                        if($ar1['article_array'][0]['grade'] < $ar2['article_array'][0]['grade']) return -1;
                        else return 1;
                    }
                } else {
                    if(array_sum($points_1) < array_sum($points_2)) return -1;
                    return 1;
                }
            } else {
                if($sum1 < $sum2) return 1;
                else return -1;
            }
        } else {
            if(count($ar1['article_array']) < count($ar2['article_array'])) return 1;
            else return -1;
        }
    } else {
        if($ar1['status'] < $ar2['status']) return -1;
        else return 1;
    }
}

function getAllProcessingArticles(){
    global $member, $accuser, $article_kind, $article;
    if($raw = $article->getAllRawArticles()){
        $result = array();
        while($row = $raw->fetch_assoc()){

            $temp_grade = $member->courtId2GradeName((int) $row['accused_id'])['grade'];
            $temp_name = $member->courtId2GradeName((int) $row['accused_id'])['name'];
            $temp_article_id = (int) $row['ar_id'];
            $temp_article_kind = (int) $row['ak_id'];
            $temp_point = $article_kind->articleId2Point((int) $row['ak_id']);

            $temp_article_element = array('grade' => $temp_grade,
                    'name' => $temp_name,
                    'article' => $temp_article_id,
                    'article_kind' => $temp_article_kind,
                    'point' => $temp_point);
            $temp_status = (int) $row['status'];

            $temp_accused_id = (int) $row['accused_id'];

            if(empty($result[$temp_accused_id])){
                $result[$temp_accused_id] = array("article_array" => array(),
                    "status" => $temp_status);
                array_push($result[$temp_accused_id]["article_array"], $temp_article_element);
            } else {
                array_push($result[$temp_accused_id]["article_array"], $temp_article_element);
                if($temp_status !== ARTICLE_STATUS_ORD) $result[$temp_accused_id]["status"] = $temp_status;
            }
        }
        //echo print_r($result);
        return $result;
    } else {
        echo "ERROR OCCURED - getAllProcessingArticles";
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
    if($raw = $article_kind->getAllRawArticleKinds()){
        $result = array();
        $temp = array();
        while($row = $raw->fetch_assoc()){
            $temp['ak_id'] = $row['ak_id'];
            $temp['ak_eng'] = $row['ak_eng'];
            $temp['point'] = $row['point'];
            array_push($result, $temp);
        }
        return $result;
    } else {
        echo "ERROR OCCURED - getAllArticleKinds";
    }
}

function getAllMembers(){
    global $member;
    return $member->searchMember();
}

function suggestMemberByQuery($query){
    global $member;
    return json_encode($member->searchMember($query));
}
?>