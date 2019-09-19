<?php //die();
require("src/lib.php");
set_time_limit(0);
header("Content-Type: text/plain");
$res = $mysqli->query("SELECT * FROM import_matches");
$total = 0;
$current = 0;
while ($row = $res->fetch_array()) {
	if (substr($row['s_dat'], 0, 1) !== "/") continue;
	echo "\n[$current/$total] " . $row['n_id'] . ": " . $row['s_dat'];
	$total++;
	$d = "/srv/http/kmla/data/old_data/www.kmlaonline.net{$row['s_dat']}";
	$dir = dirname($d);
	while (!file_exists($dir)) {
		$dir = substr($dir, 0, strrpos($dir, "/", -1));
	}
	$c = iconv("utf-8", "euc-kr", $d);
	if (preg_match("%^/srv/http/kmla/data/old_data/www.kmlaonline.net/data/member/bbs/([0-9]+).*%", $dir)) {
		$fnd = exec("find $dir | grep -F " . escapeshellarg(iconv("utf-8", "euc-kr//IGNORE", basename($d))));
	}
	if (!file_exists($d)) {
		if (!file_exists($c)) {
			if (!file_exists($fnd)) {
				continue;
			} else $d = $fnd;
		} else $d = $c;
	}
	$current++;
	echo " FOUND @ " . iconv("euc-kr", "utf-8", $d);;
	$atta = $board->addAttachment($d, $row['n_id'], basename($row['s_dat']));
	if ($atta !== false) {
		unlink($d);
		$attach = array($atta['n_id']);
		$board->editArticle($row['n_id'], false, false, $attach);
		$mysqli->query("DELETE FROM import_matches WHERE n_id={$row['n_id']}");
	}
}
