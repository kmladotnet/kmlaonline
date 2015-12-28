<?php
redirectLoginIfRequired();
$permission_to_edit=isUserPermitted($me['n_id'], "important_article_chooser");
if(!isset($_POST['util_action'])) die();
switch($_POST['util_action']){
	case "add":
		$article_id=$_POST['article_id'];
		$date=time();
		$res=false;
		if(is_numeric($article_id)){
			$reason=$mysqli->real_escape_string($_POST['reason']);
			$res=$mysqli->query("INSERT INTO `kmlaonline_important_notices_table` (n_user, n_article, n_date, s_reason, s_process_reason) VALUES ({$me['n_id']}, $article_id, $date, '$reason', '')");
		}
		if($res===false)
			ajaxDie(array(), "잘못된 글입니다.");
		else
			ajaxOk();
		break;
	case 'accept':
		if(!$permission_to_edit) redirectAlert('/util/important', "권한이 없습니다.");
		$nid=$_POST['item'];
		if(!is_numeric($nid))
			ajaxDie(array(), "잘못된 요청입니다.");
		$s_process_reason=trim($mysqli->real_escape_string($_POST['s_process_reason']));
		if(strlen($s_process_reason)==0)
			ajaxDie(array(), "처리 이유를 입력해야 합니다.");
		if(false===$mysqli->query("UPDATE `kmlaonline_important_notices_table` SET n_state=1, s_process_reason='$s_process_reason' WHERE n_id=$nid"))
			ajaxDie(array(), "오류가 발생하였습니다.");
		else
			ajaxOk(array(), '/util/important', "승인하였습니다.");
		break;
	case 'deny':
		if(!$permission_to_edit) redirectAlert('/util/important', "권한이 없습니다.");
		$nid=$_POST['item'];
		if(!is_numeric($nid))
			ajaxDie(array(), "잘못된 요청입니다.");
		$s_process_reason=trim($mysqli->real_escape_string($_POST['s_process_reason']));
		if(strlen($s_process_reason)==0)
			ajaxDie(array(), "처리 이유를 입력해야 합니다.");
		if(false===$mysqli->query("UPDATE `kmlaonline_important_notices_table` SET n_state=2, s_process_reason='$s_process_reason' WHERE n_id=$nid"))
			ajaxDie(array(), "오류가 발생하였습니다.");
		else
			ajaxOk(array(), '/util/important', "거부하였습니다.");
		break;
	case 'expire':
		if(!$permission_to_edit) redirectAlert('/util/important', "권한이 없습니다.");
		$nid=$_POST['item'];
		if(!is_numeric($nid))
			ajaxDie(array(), "잘못된 요청입니다.");
		$s_process_reason=trim($mysqli->real_escape_string($_POST['s_process_reason']));
		if(strlen($s_process_reason)==0)
			ajaxDie(array(), "처리 이유를 입력해야 합니다.");
		if(false===$mysqli->query("UPDATE `kmlaonline_important_notices_table` SET n_state=3, s_process_reason='$s_process_reason' WHERE n_id=$nid"))
			ajaxDie(array(), "오류가 발생하였습니다.");
		else
			ajaxOk(array(), '/util/important', "만료시켰습니다.");
		break;
	case 'delete':
		if(!$permission_to_edit) redirectAlert('/util/important', "권한이 없습니다.");
		$nid=$_POST['item'];
		if(!is_numeric($nid))
			ajaxDie(array(), "잘못된 요청입니다.");
		if(false===$mysqli->query("DELETE FROM `kmlaonline_important_notices_table` WHERE n_id=$nid"))
			ajaxDie(array(), "오류가 발생하였습니다.");
		else
			ajaxOk(array(), '/util/important', "삭제하였습니다.");
		break;
	default:
		ajaxDie(array(), "잘못된 동작입니다.");
}