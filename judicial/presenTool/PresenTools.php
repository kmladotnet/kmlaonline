<?php
require_once(dirname(__FILE__)."/PresenArticle.php");
function initializePresenTools($server, $id, $pw, $dbname, $force_renew=false){

    if($force_renew){
        $db=new mysqli($server, $id, $pw);
        $db->query("drop database " . $dbname);
        $db->close();
    }

    global $board, $member, $db;
    $db=@new mysqli($server, $id, $pw, $dbname);
    /*$newdb=false;
    if($db->connect_error){
        $newdb=true;
        $db=new mysqli($server, $id, $pw);
        if($db->connect_error){
            return false;
        }
        $db->query("create database " . $dbname);
        $db->query("use " . $dbname);
        $db->set_charset("utf8");
    }
    $member=new Soreemember($db, "{$dbname}_member");
    $board=new Soreeboard($db,"{$dbname}_board", $member);
    if($newdb || $force_renew){
        $member->prepareFirstUse();
        $board->prepareFirstUse();
    } */
    $article=new PresenArticle($db);
    return $db;
};
?>