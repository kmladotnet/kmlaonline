<?php
if(isset($_GET['year']) && isset($_GET['month']) && isset($_GET['day'])) {
    $y = intval($_GET['year']);
    $m = intval($_GET['month']);
    $d = intval($_GET['day']);
} elseif(isset($_GET['time'])) {
    $t = intval($_GET['time']);
    $y = intval(date('Y', $t));
    $m = intval(date('n', $t));
    $d = intval(date('j', $t));
    $h = intval(date('G', $t));
    $foodTime = 'error';
    if($h < 8 || $h >= 22) $foodTime = 'breakfast';
    elseif($h >= 8 && $h < 13) $foodTime = 'lunch';
    else $foodTime = 'dinner';
    if($h >= 22) {
        $y = date('Y', $t + 60 * 60 * 24);
        $m = date('n', $t + 60 * 60 * 24);
        $d = date('j', $t + 60 * 60 * 24);
    }
}

$query = "SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$y AND n_month=$m AND n_day=$d";
$foodData = array();
$foodData['year'] = $y;
$foodData['month'] = $m;
$foodData['date'] = $d;
if(isset($foodTime)) {
    $foodData['food'] = $foodTime;
}
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
