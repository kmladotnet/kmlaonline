<?php
redirectLoginIfRequired();
if(!isset($_POST['util_action'])) die();
if(time()<1487116800) ajaxDie(array(), "12시 이후에 신청 바랍니다.");
switch($_POST['util_action']){
	case "add":
		$category=$_POST['category'];
		$num=$_POST['num'];
		if(!is_numeric($category) || !is_numeric($num))
			ajaxDie(array(), "무언가가 잘못되었습니다.");
		$who=$me['n_id'];
		$mysqli->query("UPDATE kmlaonline_donation_table SET n_who=$who  WHERE n_category=$category AND n_num=$num");
		ajaxOk(array(), "/util/donation","신청하였습니다.");
		break;
	case "remove":
		$category=$_POST['category'];
		$num=$_POST['num'];
		if(!is_numeric($category) || !is_numeric($num))
			ajaxDie(array(), "무언가가 잘못되었습니다.");
		$mysqli->query("UPDATE kmlaonline_donation_table SET n_who=0 WHERE n_category=$category AND n_num=$num");
		ajaxOk(array(), "/util/donation","삭제하였습니다.");
		break;
	default:
		ajaxDie(array(), "잘못된 동작입니다.");
}
?>