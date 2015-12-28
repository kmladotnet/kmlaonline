<div>
	<div id="html-title"><?php echo htmlspecialchars($title)?></div>
	<div id="html-head">
		<?php head_theme();
		if(function_exists("printHead")) printHead();
		if(file_exists("css/content/$fn.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.css' />";
		if(isset($includes)){
			foreach($includes as $val){
				if(substr($val,-4,4)==".css") echo "<link class='page-specific-css' rel='stylesheet' href='$val' />";
			}
		}
		?>
		<style type="text/css"><?php
			if($me['n_level']==0) echo ".login-only{display:none}";
			echo $is_morning?".morning{display:block}":".morning{display:none}";
			echo $is_afternoon?".afternoon{display:block}":".afternoon{display:none}";
			echo $is_night?".night{display:block;}":".night{display:none}";
		?></style>
	</div>
	<div id="upper-header-menu"><?php include "src/header/upper-header.php"; ?></div>
	<div id="below-header-menu"><?php include "src/header/below-header.php"; ?></div>
	<script id="onload-scripts" type="text/text">
		<?php
		if(isset($_scripts)) echo $_scripts;
		if(isset($overriden)) echo "debug.info(".json_encode($overriden).");";
		if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
	</script>
</div>