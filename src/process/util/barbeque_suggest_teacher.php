<?php
    if(isset($_SESSION['user'])){
        global $teacher;
        $result = $teacher->getAllRawTeachers();
        echo json_encode($result);
    }
?>