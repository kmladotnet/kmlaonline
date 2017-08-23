<?php
    include('../lib.php');

    $result = getAllProcessingArticles();
    uasort($result, 'article_cmp');
    print_r($result);

    function article_cmp($ar1, $ar2){

        if($ar1['status'] === $ar2['status']){
            return 0;
        } else {
            if($ar1['status'] < $ar2['status']) return -1;
            else return 1;
        }
    }

?>