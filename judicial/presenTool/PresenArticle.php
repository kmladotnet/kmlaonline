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

    function addCourtArticle($accused, $accused_date, $accuser, $article_kind, $status=0, $manager=317, $court_num=1){
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
        if($result = $this->db->query($query) === true) {
            return $result;
        } else {
            echo "ERROR[getAllRawArticles] : sql query wrong!!";
            return false;
        }
    }

}
?>