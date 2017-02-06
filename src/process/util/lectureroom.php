<?php
redirectLoginIfRequired();
if(!isset($_POST['util_action'])) die();
switch($_POST['util_action']){
	case "clear_week":
		if(isUserPermitted($me['n_id'], "lectureroom_manager")){
			if(isset($_POST['clear_everything']))
				$mysqli->query("truncate table kmlaonline_lectureroom_table");
			else
				$mysqli->query("delete from kmlaonline_lectureroom_table where n_long_period=0");
			ajaxOk(array(), "/util/lectureroom","초기화하였습니다.");
		}else{
			ajaxDie(array(), "권한이 없습니다.");
		}
		break;
	case "add":
		$day=$_POST['day'];
		$period=$_POST['period'];
		$floor=$_POST['floor'];
		if(!is_numeric($day) || !is_numeric($period) || !is_numeric($floor))
			ajaxDie(array(), "무언가가 잘못되었습니다.");
		$res=$mysqli->query("SELECT count(*) FROM kmlaonline_lectureroom_table WHERE n_date=$day AND n_period=$period AND n_floor=$floor");
		$res=$res->fetch_array(MYSQLI_BOTH); $res=$res[0];
		if($res>0){
			ajaxDie(array(), "이미 예약되어 있습니다.");
		}
		$objective=$mysqli->real_escape_string($_POST['s_objective']);
		$who=$me['n_id'];
		$n_long_period=isset($_POST['n_long_period'])?1:0;
		$mysqli->query("INSERT INTO kmlaonline_lectureroom_table (n_date, n_period, n_floor, s_objective, n_who, n_long_period) VALUES ($day, $period, $floor, '$objective', $who, $n_long_period)");
		if($mysqli->affected_rows>0) ajaxOk(array(), "/util/lectureroom","예약하였습니다.");
		else ajaxDie(array(), "오류가 발생하였습니다.");
		break;
	case "remove":
		$day=$_POST['day'];
		$period=$_POST['period'];
		$floor=$_POST['floor'];
		if(!is_numeric($day) || !is_numeric($period) || !is_numeric($floor))
			ajaxDie(array(), "무언가가 잘못되었습니다.");
		$mysqli->query("DELETE FROM kmlaonline_lectureroom_table WHERE n_date=$day AND n_period=$period AND n_floor=$floor");
		ajaxOk(array(), "/util/lectureroom","삭제하였습니다.");
		break;
	default:
		ajaxDie(array(), "잘못된 동작입니다.");
}