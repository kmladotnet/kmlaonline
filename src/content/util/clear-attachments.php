<?php
$title="첨부파일 정리 - " . $title;
function printContent(){
	global $mysqli,$board, $member;
	$d=array();
	exec("find ./data/bbs/ -type f", $d);
	$k=array();
	foreach($d as $v){
		if(strstr($v,".htaccess")) continue;
		$k[$v]=true;
	}
	unset($d);
	//*
	foreach($board->getAttachments() as $a){
		unset($k[$a['s_path']]);
	}
	foreach($k as $fn=>$v){
		echo $fn."<br />";
		//unlink($fn);
	}
	//*/
}