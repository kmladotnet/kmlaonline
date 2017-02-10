<?php
    include "../../../lib_real.php";

    $term = trim(strip_tags($_GET["term"]));
    $a_json = array();
    $a_json_row = array();
    $query = "SELECT * FROM test_student_data WHERE name LIKE '%$term%' ORDER BY n_id";
    echo $query;
    if($data = $db -> query($query)) {
        while($row = mysqli_fetch_array($data)) {
            $grade = intval($row['n_grade']);
            $student_id = htmlentities(stripslashes($row['student_id']));
            $name = htmlentities(stripslashes($row['name']));
            $n_id = intval($row['n_id']);
            $a_json_row["id"] = $n_id;
            $a_json_row["value"] = $name;
            $a_json_row["label"] = $grade . "학년" . $name . "(" . $student_id . ")";
            array_push($a_json, $a_json_row);
        }
    }

    echo $result = json_encode($a_json);
    $result = urldecode($result);
    echo $result;
    flush();
?>