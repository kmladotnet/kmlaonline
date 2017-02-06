<?php
redirectLoginIfRequired();
$articleperpage=20;
$pagenumber=isset($_POST['page'])?$_POST['page']:0;
if(!isset($_POST['search'])) die();
$search=$_POST['search'];
if(!is_numeric($pagenumber)) $pagenumber=0;
if($pagenumber<0) $pagenumber=0;
$ret=array();
foreach($member->listMembers($pagenumber,$articleperpage,false,$search, false,false,true,true,true,true,true,true,true,true) as $v){
	if($v['n_id']==1) continue;
	$ret[]=array(
						"n_id"=>$v['n_id'], 
						"n_level"=>$v['n_level'],
						"s_id"=>htmlspecialchars($v['s_id']),
						"s_name"=>htmlspecialchars($v['s_name']),
						"s_email"=>htmlspecialchars($v['s_email'])
					);
}
die(json_encode($ret));