<?php

class DbHandler {
    private $conn;

    function __construct() {
        require_once 'dbConnect.php';

        $db = new dbConnect();
        $this->conn = $db->connect();
    }

    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();
    }

    public function insertIntoTable($obj, $column_names, $table_name){
        $c = (array) $obj;
        $key = array_keys($c);
        $columns = '';
        $values = '';

        foreach($column_names as $desired_key){
            if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else {
                $$desired_key = $c[$desired_key]
            }
            $columns = $columns.$desired_key.', ';
            $values = $values."'".$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns, ',').") VALUES (".trim($values, ',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if($r){
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
        } else {
            return NULL;
        }
    }

    public function getSession(){
        if(!isset($_SESSION)) {
            session_start();
        }

        $sess = array();
        if(isset($_SESSION['userid'])){
            $sess['userid'] = $_SESSION['userid'];
            $sess['name'] = $_SESSION['name'];
            $sess['grade'] = $_SESSION['grade'];
            $sess['studentid'] = $_SESSION['studentid'];
        } else {
            $sess['userid'] = '';
            $sess['name'] = 'Guest';
            $sess['grade'] = '';
            $sess['studentid'] = '';
        }
        return $sess;
    }
    public function destroySession(){
        if (!isset($_SESSION)) {
            session_start();
        }
        if(isset($_SESSION['uid'])){
            unset($_SESSION['userid']);
            unset($_SESSION['name']);
            unset($_SESSION['grade']);
            unset($_SESSION['studentid']);
            $info = 'info';
            if(isset($_SESSION[$info])){
                setcookie($info, '', time() - $cookie_time);
            }
            $msg = "성공적으로 로그아웃 되었습니다.";
        } else {
            $msg = "로그인되어 있지 않습니다."
        }

        return $msg;
    }
}

?>