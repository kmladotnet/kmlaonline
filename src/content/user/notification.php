<?php
redirectLoginIfRequired();
$title="알림 - $title";
$notices=$member->getNotices($me['n_id'], 20);
function printContent(){
	global $member, $me, $board, $notices;
	//, $since_id=-1, $max_id=-1);
	?>
	<form method="post" action="/proc/user/getnotifications" onsubmit="if(confirm('정말로 비우시겠습니까?')) return saveAjax(this,'비우는 중...'); return false;">
		<input type="hidden" name="clear" value="yes" />
		<input type="submit" value="비우기" style="float:right;margin-top:12px;margin-left:-96px;width:80px;height:32px;" /></td>
	</form>
	<h1 style="padding:9px;text-align:center;">알림</h1>
	<ul class="big_notification">
		<?php
		$cnt=0;
		foreach($notices as $v){
			$cnt++;
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
			if($pass) continue;
			$lnk=htmlspecialchars($lnk);
			if($v['n_seen'])
				echo "<li>";
			else
				echo "<li class='new'>";
			echo "<a href='$lnk'>";
			echo $v['s_desc'];
			echo " <small>(" . date("Y-m-d H:i:s", $v['n_time']) . ")</small>";
			echo "</a></li>";
		}
		?>
		<li style="background:white;padding:30px;cursor:auto;text-align:center;font-weight:bold;font-size:12pt;"><?php if($cnt==0) echo "알림이 없습니다."; else echo "이하 {$cnt}개"; ?></li>
	</ul>
	<?php
}