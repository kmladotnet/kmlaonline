<?php
$r=$member->editMember($_SESSION['user'], false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, $_POST['s_status_message']);
if(isAjax()){
	if($r)
		ajaxOk();
	else
		ajaxDie(array(),lang("generic","unknown error"));
}else{
	if($r===true)
		redirectAlert($_POST['returnto'],lang("generic","succeed"));
	else
		redirectAlert($_POST['returnto'],lang("generic","unknown error"));
}