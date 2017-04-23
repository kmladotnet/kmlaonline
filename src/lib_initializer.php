<?php
function _grant_access($iid, $level=false,$readonly=true){
	global $board;
	foreach(array(
		"list"=>1,
		"search"=>1,
		"view"=>1,
		"write"=>0,
		"edit"=>0,
		"delete"=>0,
		"manage modify"=>2,
		"manage permission"=>2,
		"comment view"=>1,
		"comment write"=>0,
		"comment edit"=>0,
		"comment delete"=>0,
		"attach upload"=>0,
		"attach download"=>1,
		"flag anonymous"=>1,
		"flag bold title"=>1,
		"flag public"=>1,
		"flag no comment"=>1
		) as $action=>$val){
		if($level===false)
			$board->setCategoryPermission($iid, $action, $val==2?0:($readonly?$val:1));
		else
			$board->setLevelPermission($iid, $action, $level, $val==2?0:($readonly?$val:1));
	}
}

$mysqli->query("CREATE TABLE kmlaonline_karaoke_table(n_date INT, n_period INT, s_objective TEXT, n_who BIGINT, KEY n_date (n_date), KEY n_period ( n_period ), FOREIGN KEY (n_who) REFERENCES `kmlaonline_member_data`(n_id) ON DELETE CASCADE)");
/*

$mysqli->query("CREATE TABLE kmlaonline_lectureroom_table(n_date INT, n_period INT, n_floor INT, s_objective TEXT, n_who BIGINT, KEY n_date (n_date), KEY n_period (n_period), KEY n_floor (n_floor), FOREIGN KEY (n_who) REFERENCES `kmlaonline_member_data`(n_id) ON DELETE CASCADE)");
$mysqli->query("CREATE TABLE kmlaonline_important_notices_table(n_id BIGINT NOT NULL AUTO_INCREMENT, n_user BIGINT, n_article BIGINT, n_date BIGINT, s_reason TEXT, s_process_reason TEXT, n_state INT NOT NULL DEFAULT 0, PRIMARY KEY (n_id), KEY (n_date), KEY (n_state), FOREIGN KEY (n_user) REFERENCES `kmlaonline_member_data`(n_id) ON DELETE CASCADE, FOREIGN KEY (n_article) REFERENCES `kmlaonline_board_data`(n_id) ON DELETE CASCADE)");
$mysqli->query("CREATE TABLE kmlaonline_special_permissions_table(n_user BIGINT, s_type VARCHAR(32), n_permission INT, FOREIGN KEY(n_user) REFERENCES `kmlaonline_member_data`(n_id) ON DELETE CASCADE, KEY s_type (s_type))");
$mysqli->query("CREATE TABLE kmlaonline_schedule_table(n_year INT, n_month INT, n_day INT, s_mode VARCHAR(32), s_data TEXT, KEY n_year (n_year), KEY n_month (n_month), KEY n_day (n_day), KEY s_mode (s_mode))");
$iid=$board->addCategory("all_announce", "전체 공지사항", "", 0);
_grant_access($iid); _grant_access($iid,18,false);  _grant_access($iid,17,false);  _grant_access($iid,16,false);
$iid=$board->addCategory("all_gallery", "전체 갤러리", "", 1);
_grant_access($iid); _grant_access($iid,18,false);  _grant_access($iid,17,false);  _grant_access($iid,16,false);
$iid=$board->addCategory("all_pds", "전체 자료실", "", 0);
_grant_access($iid); _grant_access($iid,18,false);  _grant_access($iid,17,false);  _grant_access($iid,16,false);
$iid=$board->addCategory("site_suggestions", "건의사항", "", 0);
_grant_access($iid); _grant_access($iid,18,false);  _grant_access($iid,17,false);  _grant_access($iid,16,false);
$iid=$board->addCategory("forum", "포럼", "", 2);
_grant_access($iid); _grant_access($iid,18,false);  _grant_access($iid,17,false);  _grant_access($iid,16,false);
//*/
for($i=22;$i>=1;$i--){
	$iid=$board->addCategory("wave{$i}_free", "{$i}기 자유게시판", "", 0);
	_grant_access($iid,$i,false);
}