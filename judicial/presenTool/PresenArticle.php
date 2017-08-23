<?php
class PresenArticle{
    private $db;
    private $table_data;

    private function escape($str) {
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = '2017_total_list';
    }

    function __destruct(){

    }

    /**
    $status variable
    ARTICLE_STATUS_FD 최변: 53788
    ARTICLE_STATUS_RT 재판결: 37084
    ARTICLE_STATUS_ORD 일반 판결: 26124
    ARTICLE_STATUS_CP 법정 진행자: 19997
    */
    function addCourtArticle($accused, $accused_date, $accuser, $article_kind, $status=ARTICLE_STATUS_ORD, $manager=317, $court_num=1){
        if(!is_int($accused) || !is_int($accuser) || !is_int($article_kind) || !is_int($status) || !is_int($manager) || !is_int($court_num)) return false;
        $query = "INSERT INTO `$this->table_data` (accused_id, accused_date, accuser_id, ak_id, status, manager_id, court_num) VALUES (" .
                $accused . ", " .
                "\"" . $this->escape($accused_date) . "\", " .
                $accuser . ", " .
                $article_kind . ", " .
                $status . ", " .
                $manager . ", " .
                $court_num . ")";

        if($this->db->query($query) === true){
            echo "<p> I guess it works properly..?!! </p>";
            $ins_id = $this -> db -> insert_id;
            $this -> db -> commit();
            $this -> db -> autocommit(true);
            return $ins_id;
        } else {
            echo "<p> I guess it works NOT properly..?!!";
            $this -> db -> rollback();
            $this -> db -> autocommit(true);
            return false;
        }
    }

    function getAllRawArticles(){
        $query = "SELECT * FROM " . $this->table_data . ";";
        if($result = $this->db->query($query)) {
            return $result;
        } else {
            echo "ERROR[getAllRawArticles] : sql query wrong!!";
            return false;
        }
    }

    function getDateById($id){
        $id = $this->escape($id);
        $query = "SELECT accused_date FROM `$this->table_data` WHERE ar_id = $id;";
        if($result = $this->db->query($query)) {
            $value;
            while($t = $result->fetch_assoc()){
                $value = $t['accused_date'];
            }
            return $value;
        } else {
            echo "ERROR[getDateById] : sql query wrong!!";
            return false;
        }
    }

    function getAccuserById($id){
        $id = $this->escape($id);
        $query = "SELECT accuser_id FROM `$this->table_data` WHERE ar_id = $id;";
        if($result = $this->db->query($query)) {
            $value;
            while($t = $result->fetch_assoc()){
                $value = $t['accuser_id'];
            }
            return $value;
        } else {
            echo "ERROR[getDateById] : sql query wrong!!";
            return false;
        }
    }



    /*
    function searchArticle($term){
        $json = array();
        $json_row = array();
        $query = "SELECT * FROM `$this->table_data` WHERE ak_eng LIKE '%$term%' ORDER BY point";
        if($data = $this->db->query($query)) {
            while($row = mysqli_fetch_array($data)) {
                $ak_kor = htmlentities(stripslashes($row['ak_kor']));
                $ak_eng = htmlentities(stripslashes($row['ak_eng']));
                $ak_id = intval($row['ak_id']);
                $point = intval($row['point']);
                $a_json_row['text'] = $row['ak_kor'];
                array_push($a_json, $a_json_row);
            }
        }
    }*/

}
?>