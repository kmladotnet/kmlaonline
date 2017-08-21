<?php
class PresenMember{
    private $db;
    private $table_data;

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = 'member_list';
    }

    function __destruct(){

    }

    function gradeName2CourtId($grade, $name){
        if(!is_int($grade)) return false;
        $query = "SELECT n_id FROM " . $this->table_data . " WHERE "
                    . "grade = " . $grade . " and "
                    . "name = '" . $this->escape($name) . "';";
        if($result = $this->db->query($query)){
            if($result->num_rows === 1){
                $row = $result->fetch_assoc();
                return $row["n_id"];
            } else {
                echo "ERROR : MORE THAN 1 or NO RESULT";
                return false;
            }
        } else {
            echo "ERROR : sql query wrong!!";
            return false;
        }
    }

    function courtId2GradeName($id){
        if(!is_int($id)) return false;
        $query = "SELECT grade, name FROM " . $this->table_data . " WHERE "
                    ."n_id = " . $id . ";";
        if($result = $this->db->query($query)){
            $row = $result->fetch_assoc();
            return $row["n_id"];
        } else {
            echo "ERROR[courtId2GradeName] : 1";
            return false;
        }
    }
}
?>