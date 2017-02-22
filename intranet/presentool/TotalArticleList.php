<?php
class TotalArticleList{
    private $db, $table_prefix;
    private $table_data;

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    public function getTableData(){
        return $this->table_data;
    }

    function prepareFirstUse(){
        $query = array();
        array_push($query, "CREATE TABLE IF NOT EXISTS `$this->table_data` (".
                                "article_id BIGINT NOT NULL AUTO_INCREMENT, ".
                                "student_id BIGINT NOT NULL, ".
                                "article_kind INT NOT NULL, ".
                                /* TODO accuser would be teachers, 선도부, 법무부, 식품영양부.
                                   Accuser Table을 만들던가 나중에 이들이 직접 이 시스템에 참여할 수 있는 방법을 마련했으면 좋겠음.
                                   어찌됐든 reference로 저장
                                */
                                "accuser CHAR(25) NOT NULL, ".
                                "accuse_date DATE, ".

                                /*This is used for pending article
                                  0 for none, 1 for delayed article, 2 for probation*/
                                "pending INT DEFAULT 0, ".
                                /* TODO
                                    -record registered time
                                    -record final revised time
                                    -record who registered or who revised it
                                */
                                "court_num INT NOT NULL DEFAULT 0, ".
                                "Primary Key (article_id), ".
                                "Foreign Key (student_id) REFERENCES test_student_data (n_id), ".
                                "Foreign Key (article_kind) REFERENCES test_article_kind (ak_id))");

        $this->db->autocommit(false);
        foreach($query as $val){
            if($this->db->query($val)===false){
                echo $val . ": " . $this->db->error;
                $this->db->rollback();
                $this->db->autocommit(true);
                return false;
            }
        }
        $this->db->commit();
        $this->db->autocommit(true);
        return true;
    }

    function __construct($db, $table_prefix){
        $this->table_prefix = $table_prefix;
        $this->db = $db;
        $this->table_data = $this->escape($this->table_prefix . "_list");
    }

    function __destruct(){
    }

    function addArticle($student_id, $article_kind, $accuse_date, $accuser, $pending = 0, $court_num = 0){
        if(!is_int($student_id) || !is_int($article_kind)) return false;
        $query = "INSERT INTO `$this->table_data` (student_id, article_kind, accuse_date, accuser, pending, court_num) VALUES (" .
                $student_id . ", " .
                $article_kind . ", " .
                "\"" . $this->escape($accuse_date) . "\", " .
                "\"" . $this->escape($accuser) . "\", " .
                $pending . ", " .
                $court_num . ")";
        echo $query;
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
}