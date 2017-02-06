<?php
redirectLoginIfRequired();
if(!isset($_POST['util_action'])) die();
switch($_POST['util_action']){
	case "editDate":
		$s_data=$mysqli->real_escape_string($_POST['s_data']);
		$s_mode=$mysqli->real_escape_string($_POST['s_mode']);
		$n_year=$_POST['n_year'];
		$n_month=$_POST['n_month'];
		$n_day=$_POST['n_day'];
		if($s_mode!=="food:0" && $s_mode!=="food:1" && $s_mode!=="food:2" && $s_mode!=="normal"){
			ajaxDie(array(), "잘못된 동작입니다.");
		}else if(@checkdate($n_month,$n_day,$n_year)===true){
			if(substr($s_mode,0,5)=="food:" && !isUserPermitted($me['n_id'], "edit_food_table")){
				ajaxDie(array(), "권한이 없습니다.");
			}
			$mysqli->autocommit(false);
			$mysqli->query("DELETE FROM kmlaonline_schedule_table WHERE n_year=$n_year AND n_month=$n_month AND n_day=$n_day AND s_mode='$s_mode'");
			if(strlen($s_data)>0)
				$mysqli->query("INSERT INTO kmlaonline_schedule_table (s_data, s_mode, n_year, n_month, n_day) VALUES ('$s_data', '$s_mode', $n_year, $n_month, $n_day)");
			if($mysqli->errno){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				ajaxDie(array(), "수정에 오류가 발생하였습니다: ".$mysqli->error);
			}else{
				$mysqli->commit();
				$mysqli->autocommit(true);
				ajaxOk(array(), "/util/schedule?mode=$s_mode&year=$n_year&month=$n_month");
			}
		}else{
			ajaxDie(array(), "날짜가 잘못되었습니다.");
		}
		break;
	default:
		ajaxDie(array(), "잘못된 동작입니다.");
}