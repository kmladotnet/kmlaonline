<?php
    if(isset($_SESSION['user'])){
        global $member;
        echo json_encode($member->getCurrentMembers());
    }
?>