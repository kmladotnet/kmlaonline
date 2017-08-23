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
                return $row["a_id"];
            } else {
                echo "ERROR[accuserName2Id] : MORE THAN 1 or NO RESULT";
                return false;
            }
        } else {
            echo "ERROR[accuserName2Id] : sql query wrong!!";
            return false;
        }
    }

    function accuserId2Name($id){
        if(!is_int($id)) return false;
        $query = "SELECT name FROM " . $this->table_data . " WHERE "
                    . "a_id = " . $id . ";";
        if($result = $this->db->query($query)){
            $row = $result->fetch_assoc();
            if(($id >= 73 && $id <=75) || $id ===77) return $row["name"];
            else return $row["name"] . "tr";
        } else {
            echo "ERROR[accuserId2Name] : sql query wrong!!";
            return false;
        }
    }

    function getAllRawAccusers(){
        $query = "SELECT * FROM " . $this->table_data . ";";
        if($result = $this->db->query($query)) {
            return $result;
        } else {
            echo "ERROR[getAllRawAccusers] : sql query wrong!!";
            return false;
        }
    }
}
?>