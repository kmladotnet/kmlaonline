<?php
redirectLoginIfRequired();
if(!isset($_POST['util_action'])) die();
if(time()<1486998000) ajaxDie(array(), "2017년 기부 물품 신청은 2월 13일에서 14일로 넘어가는 자정부터 신청가능합니다. :D");
switch($_POST['util_action']){
	case "add":
		$category=$_POST['category'];
		$num=$_POST['num'];
		if(!is_numeric($category) || !is_numeric($num))
			ajaxDie(array(), "무언가가 잘못되었습니다.");
		$who=$me['n_id'];

		//$mysqli->query("UPDATE kmlaonline_donation_table SET n_who=$who  WHERE n_category=$category AND n_num=$num");
        $mysqli->query("UPDATE donation_test SET n_who=$who  WHERE n_category=$category AND n_num=$num");
		ajaxOk(array(), "/util/donation","신청하였습니다.");
		break;
	case "remove":
		$category=$_POST['category'];
		$num=$_POST['num'];
		if(!is_numeric($category) || !is_numeric($num))
			ajaxDie(array(), "무언가가 잘못되었습니다.");
		//$mysqli->query("UPDATE kmlaonline_donation_table SET n_who=0 WHERE n_category=$category AND n_num=$num");
        $mysqli->query("UPDATE donation_test SET n_who=0 WHERE n_category=$category AND n_num=$num");
		ajaxOk(array(), "/util/donation","삭제하였습니다.");
		break;
	default:
		ajaxDie(array(), "잘못된 동작입니다.");
}
?>