<?php
redirectLoginIfRequired();
$errors=array();
if(!isset($_POST['id']))
	$errors['__error']=lang("user","message","id unspecified");
$n=$member->readNote($_POST['id']);
if($n['n_owner']!=$me['n_id'])
	$errors['__error']=lang("user","message","not mine");
if(count($errors)==0){
	$member->checkNoteRead($n['n_id']);
	if($me['n_id']==$n['n_from'])
		$usr=$member->getMember($n['n_to']);
	else
		$usr=$member->getMember($n['n_from']);
	$data="";
	if(isset($usr['s_pic']) && $usr['s_pic'])
		$data.="<img src=\"".htmlspecialchars($usr['s_pic']?$usr['s_pic']:"/images/no-image.png")."\" class=\"sender-pic\" />";
	$data.="<div style='display:block;'>";
	$data.="<div class='view_user'><a href='/user/view/{$usr['n_id']}/".htmlspecialchars($usr['s_id'])."'>".putUserCard($usr,0,false)."</a></div>";
	$data.="<div class='view_time'>";
	$data.=date(lang("generic","full date format"),$n['n_date'])." | ";
	$data.="<a onclick='user_message_removeNote({$n['n_id']})'>".lang("generic","remove")."</a> | ";
	$data.="<a onclick=\"user_message_putQuote({$n['n_id']}); user_message_loadCompose(); user_message_addRecepientUser({$usr['n_id']}, '". addslashes($usr['s_id'] . " (" . $usr['s_name'] . ")") . "');\">".lang("generic","reply")."</a>";
	$data.="</div>";
	$data.="<div class='view_data' id='article_data'>".filterContent($n['s_data'],false)."</div>";
	$data.="<div style='clear:both'></div></div>";
	$short=trim(preg_replace("/\\s{2,10000}/i","",html_entity_decode(strip_tags($n['s_data']), ENT_HTML5)));
	if(mb_strlen($short)>24)
		$short=mb_substr($short,0,20,"UTF-8") . "...";;
}
if(count($errors))
	ajaxDie($errors);
else
	ajaxOk(array("data"=>$data, "shortstr"=>strip_tags($short)));