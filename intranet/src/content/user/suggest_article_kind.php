<?php
    include "../../../lib_real.php";
    echo "<p>WOWWWOWOOWOWOWOOWOWOWOOWOWOWOWOW</p>";
    $term = trim(strip_tags($_GET["term"]));
    $a_json = array();
    $a_json_row = array();
    $query = "SELECT * FROM test_article_kind WHERE ak_kor LIKE '%$term%' ORDER BY point";
    //echo $query . "<br /> " . phpversion();
    if($data = $db -> query($query)) {
        while($row = mysqli_fetch_array($data)) {
            $ak_kor = htmlentities(stripslashes($row['ak_kor']));
            $ak_eng = htmlentities(stripslashes($row['ak_eng']));
            $ak_id = intval($row['ak_id']);
            $point = intval($row['point']);
            $a_json_row["id"] = $ak_id;
            $a_json_row["value"] = $ak_kor;
            $a_json_row["label"] = $ak_kor;
            array_push($a_json, $a_json_row);
        }
    }

    echo json_encode($a_json, JSON_UNESCAPED_UNICODE);
    flush();
?>