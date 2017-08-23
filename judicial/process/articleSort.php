<?php
    include('../lib.php');

    $result = getAllProcessingArticles();
    uasort($result, 'article_cmp');
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
                    array_push($points_1, $ar1['article_array'][$i]['article_kind']);
                    $sum2 += $ar2['article_array'][$i]['point'];
                    array_push($points_2, $ar2['article_array'][$i]['article_kind']);
                }
                if($sum1 === $sum2){
                    if(count(array_diff(array_merge($points_1, $points_2), array_intersect($points_1, $points_2))) === 0) {
                        if($ar1['article_array'][0]['grade'] === $ar2['article_array'][0]['grade']) {
                            if($ar1['article_array'][0]['name'] === $ar2['article_array'][0]['name']) {
                                return 0;
                            } else {
                                return strcmp($ar1['article_array'][0]['name'], $ar2['article_array'][0]['name']);
                            }
                        } else {
                            if($ar1['article_array'][0]['grade'] < $ar2['article_array'][0]['grade']) return -1;
                            else return 1;
                        }
                    } else {
                        if(array_sum($points_1) < array_sum($points_2)) return -1;
                        return 1;
                    }
                } else {
                    if($sum1 < $sum2) return 1;
                    else return -1;
                }
            } else {
                if(count($ar1['article_array']) < count($ar2['article_array'])) return 1;
                else return -1;
            }
        } else {
            if($ar1['status'] < $ar2['status']) return -1;
            else return 1;
        }
    }
?>