<?php
    if(isset($_SESSION['user'])){
        $courtPost = getLatestCourtPost();
        if($courtPost !== null){
            testGoesToCourt($me['s_name'], $courtPost);
        } else {
           echo "null인데..";
        }

    }