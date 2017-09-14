<?php
class HJTeacher{
    private $db;
    private $table_data;
    private $teacher_cache=array();

    private function escape($str){
        return $this->db->real_escape_string($str);
    }

    function __construct($db){
        $this->db = $db;
        $this->table_data = 'kmlaonline_teacher_data';
    }

    function __destruct(){
    }

    function addTeacher($id, $pw, $name, $email, $phone="", $work="", $type=0){
        $pw_hash="sha512"; // ripe320 is better
        $pw_salt="";
        $avail='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,./;\'[]\\`-=~!@#$%^&*()_+{}|:"<>?';
        for($i=0;$i<768;$i++) $pw_salt .= substr($avail, rand(0, strlen($avail) - 1), 1);
        $pw_encoded=hash($pw_hash, $pw_salt . "|" . hash($pw_hash, $pw) . "|" . $pw_salt);

        $this->db->autocommit(false);
        $query="INSERT INTO `$this->table_data` (s_id, s_pw, s_pw_salt, s_pw_hash, s_name, s_email, n_reg_date, n_access_date, s_phone, s_work, n_type) VALUES (" .
                    "'" . $this->escape($id) . "', ".
                    "'" . $this->escape($pw_encoded) . "', ".
                    "'" . $this->escape($pw_salt) . "', ".
                    "'" . $this->escape($pw_hash) . "', ".
                    "'" . $this->escape($name) . "', ".
                    "'" . $this->escape($email) . "', ".
                    time() . ", " .
                    time() . ", " .
                    "'" . $this->escape($phone) . "', ".
                    "'" . $this->escape($work) . "', ".
                    $type . ")";
        //echo nl2br($query) . "<br />";//return false;
        if($this->db->query($query)===true){
            $ins_id = $this->db->insert_id;
            $this->db->commit(); $this->db->autocommit(true);
            return $ins_id;
        }else{
            //echo $this->db->error;
            $this->db->rollback(); $this->db->autocommit(true);
            return false;
        }
    }

    function authTeacher($id, $pw=false, $pw_enc=false){
        if($id=="") return -20;
        $id=$this->escape($id);
        $query="SELECT * FROM `$this->table_data` WHERE LCASE(s_id)=LCASE('$id') OR LCASE(s_email)=LCASE('$id')";
        if($res=$this->db->query($query)){
            while ($row = $res->fetch_array(MYSQLI_BOTH)){
                $res->close();
                if($this->db->more_results())$this->db->next_result();
                if($pw_enc !== false && $row['s_pw'] == hash($row['s_pw_hash'],$row['s_pw_salt']."|".$pw_enc."|".$row['s_pw_salt']))
                    return 0; // Succeed
                if($pw!==false && $row['s_pw']==hash($row['s_pw_hash'],$row['s_pw_salt']."|".hash($row["s_pw_hash"],$pw)."|".$row['s_pw_salt']))
                    return 0; // Succeed
                return -30; // Password error
            }
            return -20; // ID Error
        }
        return -10; // Something went wrong
    }

    function getTeacher($teacher, $by=0, $withpw=false) {
        if($by==0 && !is_numeric($teacher)) return false;
        else if($by==1 && strlen($teacher) == 0) return false;
        else if($by > 2) return false;

        $query = "SELECT * FROM `$this->table_data` WHERE ";
        $teacher = $this->escape($teacher);

        switch($by){
            case 0:
                if(isset($this->teacher_cache["n_id:" . $teacher])){
                    return $this->teacher_cache["n_id:".$teacher];
                }
                $query .= "n_id = $teacher";
                break;
            case 1:
                if(isset($this->teacher_cache["s_id:".$teacher])){
                    return $this->teacher_cache["s_id:".$teacher];
                }
                $query .= "s_id='$teacher'";
                break;
            case 2:
                if(isset($this->teacher_cache["s_email:" . $teacher])){
                    return $this->teacher_cache["s_email:".$teacher];
                }
                $query.="s_email='$teacher'";
                break;
        }

        if($res=$this->db->query($query)){
            while ($row = $res->fetch_array(MYSQLI_ASSOC)){
                $res->close();
                if($this->db->more_results()) $this->db->next_result();
                if($withpw === false){
                    unset($row['s_pw'], $row['s_pw_salt'], $row['s_pw_hash']);
                }
                $this->teacher_cache["n_id:".$row['n_id']] = $this->teacher_cache["s_id:".$row['s_id']]=$this->teacher_cache["s_email:".$row['s_email']]=$row;
                return $row;
            }
        }
        return false;
    }
}
?>