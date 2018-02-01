<?php
include '../src/lib.php';
include 'test-config.php';

$query = "SELECT * FROM kmlaonline_member_data WHERE n_level = $max_level";
if($res = $mysqli->query($query)) {
    while($row = $res->fetch_array(MYSQLI_ASSOC)) {
        echo $row['n_birth_date_month'] . ", " . $row['n_birth_date_day'];
    }
}
?>
