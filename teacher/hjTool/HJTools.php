<?php
require_once(dirname(__FILE__)."/HJTeacher.php");
require_once(dirname(__FILE__)."/HJBarbeque.php");
function initializeHJTools($server, $id, $pw, $dbname, $force_renew=false){

    if($force_renew){
        $db = new mysqli($server, $id, $pw);
        $db->query("drop database " . $dbname);
        $db->close();
    }

    global $teacher, $barbeque, $db;
    $db = @new mysqli($server, $id, $pw, $dbname);
    $newdb = false;

    if($db->connect_error){
        echo "<b>Error occured when connecting db</b>";
        return false;
    }

    $teacher = new HJTeacher($db);
    $barbeque = new HJBarbeque($db);

    return $db;
}
?>