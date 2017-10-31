<?php
    if(isset($_SESSION['user'])){
        $courtPost = testGetSecondLatestCourtPost();
        if($courtPost !== null){
            testGoesToCourt($me['s_name'], $courtPost);
        } else {
           echo "null인데..";
        }

    }