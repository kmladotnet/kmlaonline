<?php
require_once(dirname(__FILE__)."/TotalStudent.php");
require_once(dirname(__FILE__)."/TotalArticleList.php");
require_once(dirname(__FILE__)."/ArticleKindList.php");
function initializeCourtTools($server, $id, $pw, $dbname, $force_renew=false){

    if($force_renew){
        $mysqli = new mysqli($server, $id, $pw);
        $mysqli -> query("drop database " . $dbname);
        $mysqli -> close();
    }

    //global $board, $member, $mysqli;
    $mysqli = @new mysqli($server, $id, $pw, $dbname);
    $newdb = false;

    if($mysqli->connect_error){
        $newdb = true;
        $mysqli = new mysqli($server, $id, $pw);
        if($mysqli->connect_error){
            return false;
        }
        $mysqli->query("create database " . $dbname);
        $mysqli->query("use " . $dbname);
        $mysqli->set_charset("utf8");
    }

    $student = new TotalStudent($mysqli, "{$dbname}_student");
    $article_kind = new ArticleKindList($mysqli, "{$dbname}_article");
    $article = new TotalArticleList($mysqli, "{$dbname}_total_article");


    if($newdb || $force_renew){
        $student->prepareFirstUse();
        $article_kind->prepareFirstUse();
        $article->prepareFirstUse();
    }

    return $mysqli;
    /*$member = new Soreemember($mysqli, "{$dbname}_member");
    $board = new Soreeboard($mysqli,"{$dbname}_board", $member);
    if($newdb || $force_renew){
        $member->prepareFirstUse();
        $board->prepareFirstUse();
    }
    return $mysqli; */
}
/*class presentMember{
    private $mysqli;
    private $table_data;

    private function escape($str){
        return $this->mysqli->real_escape_string($str);
    }

    function createTable(){
        $query=array();
        array_push($query, "CREATE TABLE IF NOT EXISTS dept_justice_student (".
            "n_id BIGINT NOT NULL AUTO_INCREMENT,".
            "s_email char(255) NOT NULL PRIMARY KEY,".
            "n_pwd char(255) NOT NULL,".
            "s_name TINYTEXT NOT NULL,".
            "n_grade INT NOT NULL,".
            "n_student_id INT NOT NULL,".
            "n_room INT NOT NULL,".
            "s_adviser TINYTEXT NOT NULL,".
            "n_council INT NOT NULL DEFAULT 0".
            ")");
        $this->mysqli->autocommit(false);
        foreach($query as $val){
            if($this->mysqli->query($val)===false){
                echo "error when creating database";
                return false;
            }
        }
        $this->mysqli->commit();
        $this->mysqli->autocommit();
        return true;
    }
    function __construct($db) {
        $this->mysqli = $db;
    }
}*/
?>
