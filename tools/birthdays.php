<?php
include '../donation/test-config.php';

$query = "SELECT * FROM kmlaonline_member_data WHERE n_level = 23";
if($res = $mysqli->query($query)) {
    while($row = $res->fetch_array(MYSQLI_ASSOC)) {
        echo $row['s_name'] . ", " . $row['n_birth_date_month'] . ", " . $row['n_birth_date_day'] . "<br />";
    }
}
?>
