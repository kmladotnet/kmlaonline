<?php
$title="404 ". lang("error pages","404","title") . " - " . $title;
function printHead(){
}
function printContent(){
	?>
	<h1 style="padding:10px;"><?php echo lang("error pages","404","big") ?></h1>
	<div style="padding:10px;"><?php echo lang("error pages","404","detail") ?></div>
	<?php
}
