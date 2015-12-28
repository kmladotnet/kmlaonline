<?php
$title="403 " . lang("error pages","403","title") . " - " . $title;
function printHead(){
	header("HTTP/1.1 403 Not Found");
	header("Status: 403 Not Found");
}
function printContent(){
	?>
	<h1 style="padding:10px;"><?php echo lang("error pages","403","big") ?></h1>
	<div style="padding:10px;"><?php echo lang("error pages","403","detail") ?></div>
	<?php
}