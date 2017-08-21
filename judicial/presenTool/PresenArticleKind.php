<?php
class PresenArticleKind {
    private $db;
    private $table_data;

    private function escape($str) {
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = 'article_list';
    }

    function __destruct(){

    }

    function articleDesc2Id($desc){
        $query = "SELECT ak_id FROM " . $this->table_data . " WHERE "
                    . "ak_eng = '" . $this->escape($desc) . "';";
        if($result = $this->db->query($query)){
            if($result->num_rows === 1){
                $row = $result->fetch_assoc();
                return $row["ak_id"];
            } else {
                echo "ERROR[articleDesc2Id] : MORE THAN 1 or NO RESULT";
                return false;
            }
        } else {
            echo "ERROR[articleDesc2Id] : sql query wrong!!";
            return false;
        }
    }
}

?>