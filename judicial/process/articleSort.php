<?php
    include('../lib.php');

    $result = getAllProcessingArticles();
    /*uasort($result, 'article_cmp');
    print_r($result);

    function article_cmp($ar1, $ar2){

        if($ar1['status'] === $ar2['status']){
            if(count($ar1['article_array']) === count($ar2['article_array'])) {
                $sum1 = 0;
                $sum2 = 0;
                $points_1 = array();
                $points_2 = array();
                for($i = 0; $i < count($ar1['article_array']); $i++){
                    $sum1 += $ar1['article_array'][$i]['point'];
                    $points_1.push($ar1['article_array'][$i]['article_kind']);
                    $sum2 += $ar2['article_array'][$i]['point'];
                    $points_2.push($ar1['article_array'][$i]['article_kind']);
                }
                if($sum1 === $sum2){
                    $points_1
                } else {
                    if($sum1 < $sum2) return -1;
                    else return 1;
                }
            } else {
                if(count($ar1['article_array']) < count($ar2['article_array'])) return 1;
                else return -1;
            }
        } else {
            if($ar1['status'] < $ar2['status']) return -1;
            else return 1;
        }
    }*/
    $test1 = [30, 12, 13];
    $test2 = [30, 13, 12];
    echo $test1 == $test2;
    echo $test1 === $test2;
?>