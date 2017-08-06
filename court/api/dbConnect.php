<?php

class dbConnect {
    private $conn;

    function __construct(){

    }

    function connect(){
        include_once 'config.php';

        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to Database: ". mysqli_connect_error();
        }

        return $this->conn;
    }
}

?>