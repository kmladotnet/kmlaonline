<?php
class HJBarbeque {
    private $db;
    private $table_data;

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = 'kmlaonlinee_bbq_data';
    }

    function __destruct(){
    }
}
?>