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
                                "accuse_date DATE, ".
                                "Primary Key (article_id), ".
                                "Foreign Key (student_id) REFERENCES test_student_data (n_id))");

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
}