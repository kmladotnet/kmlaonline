<?php
    if(isset($_SESSION['user'])){
        global $member, $me;
        $temp = $member->getAdditionalData($me['n_id']);
        echo $member->getMember($me['n_id'])['s_name'];
        echo $temp['grade'];

    }
?>