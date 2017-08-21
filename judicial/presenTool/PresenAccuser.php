<?php
class PresenAccuser{
    private $db;
    private $table_data;

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = 'accuser_list';
    }

    function __destruct(){

    }

    function accuserName2Id($name){
        $query = "SELECT a_id FROM " . $this->table_data . " WHERE "
                    . "name = '" . $this->escape($name) . "';";
        if($result = $this->db->query($query)){
            if($result->num_rows === 1){
                $row = $result->fetch_assoc();
                echo $row["n_id"];
                return $row["n_id"];
            } else {
                echo "ERROR[accuserName2Id] : MORE THAN 1 or NO RESULT";
                return false;
            }
        } else {
            echo "ERROR[accuserName2Id] : sql query wrong!!";
            return false;
        }
    }
}
?>