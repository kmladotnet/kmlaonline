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
                    . "name = " . $this->escape($name) . ";";
        echo $query;
        if($result = $this->db->query($query) === true){
            if($result->num_rows === 1){
                $row = $result->fetch_assoc();
                echo "It's okay! " . $row["n_id"] . " !!! ";
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
}
?>