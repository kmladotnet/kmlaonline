<?php
    include "intranet/db-config.php"
    $term = trim(strip_tags($_GET["term"]));
    $a_json = array();
    $a_json_row = array();
    if($data = $db -> query("SELECT * FROM test_student_data WHERE name LIKE '%$term%' ORDER BY n_id")) {
        while($row = mysqli_fetch_array($data)) {
            $grade = intval($row['grade']);
            $student_id = htmlentities(stripcslashes($row['student_id']));
            $name = htmlentities(stripcslashes($row['name']));
            $n_id = intval($row['n_id']);
            $a_json_row["id"] = $n_id;
            $a_json_row["value"] = $name;
            $a_json_row["label"] = $grade . "학년" . $name . "($student_id)";
            array_push($a_json, $a_json_row);
        }
        echo "<p>나름 success..</p>";
    } else {
        echo "<p>fail</p>";
    }

    echo json_encode($a_json);
    flush();
?>