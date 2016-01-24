<!doctype html><html><head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
	<base href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/" />
    <!-- gridstack -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.3/gridstack.css" />

    <!-- jquery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <!-- velocity -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.ui.min.js"></script>

    <!-- bootstrap -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />

	<script type="text/javascript" src="/js/lightbox.js" charset="utf-8"></script>
	<script type="text/javascript" src="/js/script.js" charset="utf-8"></script>
	<script type="text/javascript" src="/js/content/combined.js" charset="utf-8"></script>
	<link rel="stylesheet" href="/css/lightbox.css" charset="utf-8" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/font.css" charset="utf-8" type="text/css" media="screen" />
	<title><?php echo htmlspecialchars($title); ?></title>
	<?php head_theme();
	if(function_exists("printHead")) printHead();
	if(file_exists("css/content/$fn.mobile.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.mobile.css' />";
	else if(file_exists("css/content/$fn.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.css' />";
	if(isset($includes)){
		foreach($includes as $val){
			if(substr($val,-4,4)==".css") echo "<link class='page-specific-css' rel='stylesheet' href='$val' />";
		}
	}
	?>
	<style type="text/css"><?php
		if($me['n_level']==0) echo ".login-only{display:none}";
		if(!$is_morning) echo ".morning{display:none}";
		if(!$is_afternoon) echo ".afternoon{display:none}";
		if(!$is_night) echo ".night{display:none}";
	?></style>
</head><body>
	<img id="no-login-bg" style="position:fixed;left:0;top:0;display:none;z-index:-50;" />
	<div id="divSavingIndicatorFiller"></div>
	<div style="" id="divSavingIndicator">
		<div id="divSavingIndicatorInformation">
			<img src="/images/loading.gif" /> <span id="spnWhatAmIDoing">작업 중...</span><br />
			<button id="cancelAjax" onclick="return cancelAjaxSave();" style="width:120px;height:32px;">취소</button>
		</div>
		<div id="debug" style="background:white;margin:0 auto;width:800px;display:none;margin-top:100px;"></div>
	</div>
	<div id="total-wrap">
		<div id="upper-header-menu"><?php include "src/header/upper-header.php"; ?></div>
		<div id="total-header-menu"></div>
		<div style="clear:both"></div>
		<div id="below-header-menu"><?php include "src/header/below-header.php"; ?></div>
	</div>
	<div id="total-footer" class="hide-on-upper-panel">
		<?php
		$puri=strtok($_SERVER["REQUEST_URI"],'?');
		unset($_GET['force_mobile'], $_GET['force_desktop']);
		$_GET['force_desktop']=1;
		$i=0;
		foreach($_GET as $k=>$v) $puri.=($i++==0?"?":"&").urlencode($k)."=".urlencode($v);
		?>
		<a href="<?php echo htmlspecialchars($puri) ?>">PC판으로 보기</a><br />
		<?php
		echo langraw("layout","footer"); ?>
	</div>
	<script type="text/javascript">/*<!--*/
		<?php
			if(isset($_scripts)) echo $_scripts;
			if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
	/*-->*/</script>
	<script id="onload-scripts" type="text/text">
		<?php
		if(isset($_scripts)) echo $_scripts;
		if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
	</script>
</body></html>
