<?php
redirectLoginIfRequired();
if(isset($_GET['count'])) die($member->getNoticeCount($me['n_id']));
if(isset($_POST['clear'])){
	$member->removeNotice($me['n_id']);
	ajaxOk(array(), "/user/notification");
	die();
}
if(isset($_GET['since_id'])) $since_id=$_GET['since_id']; else $since_id=-1;
$notices=$member->getNotices($me['n_id'], 20, $since_id);
$ret=array();
foreach($notices as $v){
	$pass=false;
	switch(substr($v['s_url'], 0, strpos($v['s_url'], ":"))){
		case "article":
			$aid=substr($v['s_url'], strpos($v['s_url'], ":")+1);
			$a=$board->getArticle($aid);
			if($a===false){
				$member->removeNotice($me['n_id'], $v['s_fnkey']);
				$pass=true;
				break;
			}
			$b=$board->getCategory($a['n_cat']);
			$bid=$b['s_id'];
			$lnk="/board/$bid/view/$aid";
			break;
		default:
			$lnk=$v['s_url'];
	}
    if(preg_match('/\/board\//', $lnk)) {
        preg_match('/\/([0-9]+)$/', $lnk, $articleNum);
        if($board->getArticle($articleNum[1]) === false) {
            $member->removeNotice($me['n_id'], $v['s_fnkey']);
            $pass=true;
        }
    }
	if($pass) continue;
	$lnk=htmlspecialchars($lnk);
	$s="";
	if($v['n_seen'])
		$s.="<li>";
	else
		$s.="<li class='new'>";
	$s.="<a href='$lnk'>";
	$s.=$v['s_desc'];
	$s.=" <small>(" . date("Y-m-d H:i:s", $v['n_time']) . ")</small>";
	$s.="</a></li>";
	$ret[$v['n_id']]=$s;
}
$ret=array_reverse($ret);
die(json_encode($ret));
