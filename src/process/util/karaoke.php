<?php
redirectLoginIfRequired();
if(!isset($_POST['util_action'])) die();
switch($_POST['util_action']){
    case "clear_week":
        if(isUserPermitted($me['n_id'], "karaoke_manager")){
            $mysqli->query("truncate table kmlaonline_karaoke_table");
            ajaxOk(array(), "/util/karaoke","초기화하였습니다.");
        }else{
            ajaxDie(array(), "권한이 없습니다.");
        }
        break;
    case "add":
        $day = $_POST['day'];
        $period = $_POST['period'];
        if(!is_numeric($day) || !is_numeric($period))
            ajaxDie(array(), "무언가가 잘못되었습니다.");
        $res = $mysqli->query("SELECT count(*) FROM kmlaonline_karaoke_table WHERE n_date=$day AND n_period=$period");
        $res = $res->fetch_array(MYSQLI_BOTH); $res = $res[0];
        if($res > 0) {
            ajaxDie(array(), "이미 예약되어 있습니다.");
        }
        $objective = $mysqli->real_escape_string($_POST['s_objective']);
        $who = $me['n_id'];
        $mysqli->query("INSERT INTO kmlaonline_karaoke_table (n_date, n_period, s_objective, n_who) VALUES ($day, $period, '$objective', $who)");
        if($mysqli->affected_rows>0) ajaxOk(array(), "/util/karaoke","예약하였습니다.");
        else ajaxDie(array(), "오류가 발생하였습니다.");
        break;
    case "remove":
        $day = $_POST['day'];
        $period = $_POST['period'];
        if(!is_numeric($day) || !is_numeric($period))
            ajaxDie(array(), "무언가가 잘못되었습니다.");
        $mysqli->query("DELETE FROM kmlaonline_karaoke_table WHERE n_date=$day AND n_period=$period");
        ajaxOk(array(), "/util/karaoke","삭제하였습니다.");
        break;
    default:
        ajaxDie(array(), "잘못된 동작입니다.");
}
