<?php
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
    $y = date('Y', strtotime('+1 day'));
    $m = date('n', strtotime('+1 day'));
    $d = date('j', strtotime('+1 day'));
}
$res = array();
$res['year'] = $y;
$res['month'] = $m;
$res['day'] = $d;
$res['food'] = $foodTime;
echo json_encode($res);
