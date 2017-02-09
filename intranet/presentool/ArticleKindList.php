<?php
class ArticleKindList{
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
        array_push($query, "LOAD DATA INFILE 'article_kind.txt' into table test_article_kind(".
                            "ak_id, ak_kor, ak_eng, point);");

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
        $this->table_data = $this->escape($this->table_prefix . "_kind");
    }

    function __destruct(){
    }

    function addArticle($kor_descrption, $eng_description, $point){
        if(!is_numeric($point)) return false;
        $this->db->autocommit(false);
        $query = "INSERT INTO `$this->table_data` (ak_kor, ak_eng, point) VALUES (" .
                    "'" . $this->escape($kor_descrption) . "', ".
                    "'" . $this->escape($eng_description) . "', ".
                    $point . ")";
        if($this->db->query($query) === true){
            $ins_id = $this->db->insert_id;
            $this->db->commit();
            $this->db->autocommit(true);
            return $ins_id;
        } else {
            $this->db->rollback();
            $this->db->autocommit(true);
            return false;
        }
    }
}