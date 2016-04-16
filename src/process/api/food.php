<?php
$y = intval($_GET['year']);
$m = intval($_GET['month']);
$d = intval($_GET['day']);
$query = "SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$y AND n_month=$m AND n_day=$d";
$foodData = array();
if($res = $mysqli->query($query)){
    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
        if(substr($row['s_mode'], 0, 4) === 'food') {
            $foodTime = 'error';
            switch(substr($row['s_mode'], 5)) {
                case 0: $foodTime = 'breakfast';
                    break;
                case 1: $foodTime = 'lunch';
                    break;
                case 2: $foodTime = 'dinner';
                    break;
            }
            $foodData[$foodTime] = $row['s_data'];
        }
    }
    $res->close();
    if($mysqli->more_results()) $mysqli->next_result();
}
echo json_encode($foodData);
