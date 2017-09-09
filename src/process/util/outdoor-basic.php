<?php
    if(isset($_SESSION['user'])){
        global $member;
        echo $member->getMember(1576)['s_name'];

    }
?>