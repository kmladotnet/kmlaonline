<?php
class TotalStudent{
    private $db, $table_prefix;
    private $table_data;

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    public function getTable(){
        return $this->table_data;
    }

    function prepareFirstUse(){
        $query = array();
        array_push($query, "CREATE TABLE IF NOT EXISTS `$this->table_data` (".
                                "n_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, ".

                                /* temporary TODO
                                "email char(255), ".
                                "id char(64) NOT NULL, UNIQUE KEY id (id), ".
                                "pw VARCHAR(1024) NOT NULL,".
                                */

                                "name TINYTEXT NOT NULL, ".
                                "student_id char(64), ".
                                "n_grade INT NOT NULL, ".
                                "n_class INT NOT NULL, ".

                                /* 0 for 일반 유저
                                   1 for 입사행 위원, 위원장
                                   2 for 법무부
                                   3 for 선도부
                                   4 for 식영부*/
                                "n_council INT NOT NULL DEFAULT 0, ".

                                "n_room INT DEFAULT 0, ".

                                /* this column is for checking whether the student has probation or not
                                   0 for none, 1 for broken_probation, 2 for complete probation
                                */
                                "probation INT DEFAULT 0, ".

                                /* these columns are for checking whether the student did special training.
                                   0 for no, 1 for yes
                                */
                                "first_special INT DEFAULT 0, ".
                                "second_special INT DEFAULT 0, ".
                                "third_special INT DEFAULT 0)");

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
        $this->table_data = $this->escape($this->table_prefix . "_data");
    }

    function __destruct(){
    }
}
?>