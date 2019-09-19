<?php require("src/lib.php");
$res = $mysqli->query("SELECT n_id,s_data FROM kmlaonline_board_data WHERE n_editdate<1392946260");
$i++;
while ($row = $res->fetch_array()) {
	$s = $row['s_data'];
	$s = nl2br($s);
	$mysqli->query("UPDATE kmlaonline_board_data SET n_editdate=" . time() . ", s_data='" . $mysqli->real_escape_string($s) . "' WHERE n_id={$row['n_id']}");
}
