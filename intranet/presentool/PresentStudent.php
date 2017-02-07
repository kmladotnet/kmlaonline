<?php
require("intranet/lib.php");
class presentMember{
    private $mysqli;
    private $table_data;

    private function escape($str){
        return $this->mysqli->real_escape_string($str);
    }

    function createTable(){
        $query=array();
        array_push($query, "CREATE TABLE IF NOT EXISTS dept_justice_student (".
            "n_id BIGINT NOT NULL AUTO_INCREMENT,".
            "s_email char(255) NOT NULL PRIMARY KEY,".
            "n_pwd char(255) NOT NULL,".
            "s_name TINYTEXT NOT NULL,".
            "n_grade INT NOT NULL,".
            "n_student_id INT NOT NULL,".
            "n_room INT NOT NULL,".
            "s_adviser TINYTEXT NOT NULL,".
            "n_council INT NOT NULL DEFAULT 0".
            ")");
        $this->mysqli->autocommit(false);
        foreach($query as $val){
            if($this->mysqli->query($val)===false){
                echo "error when creating database";
                return false;
            }
        }
        $this->mysqli->commit();
        $this->mysqli->autocommit();
        return true;
    }
    function __construct($db) {
        $this->mysqli = $db;
    }
}
