<?php
redirectLoginIfRequired();
$title="고스톱 - " . $title;
function printContent(){
	#echo '<object width="1000" height="700" data="/data/game/gostop.swf" align="center"></object>';
	echo '건전하게 삽시다<a href="/data/game/gostop.swf">.</a>';
}
?>