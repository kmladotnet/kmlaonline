<!doctype html><html><head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />

    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="/icon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/icon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/icon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/icon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/icon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/icon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/icon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/icon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/icon/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="/icon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/icon/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/icon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/icon/manifest.json">
    <link rel="mask-icon" href="/icon/safari-pinned-tab.svg" color="#15466b">
    <link rel="shortcut icon" href="/icon/favicon.ico">
    <meta name="msapplication-TileColor" content="#15466b">
    <meta name="msapplication-TileImage" content="/icon/mstile-144x144.png">
    <meta name="msapplication-config" content="/icon/browserconfig.xml">
    <meta name="theme-color" content="#15466b">

	<base href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/" />
    <!-- gridstack -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.4/gridstack.css" />

    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <!-- velocity -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.3/velocity.ui.min.js"></script>

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- bootstrap-select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- lightbox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.js"></script>

	<script type="text/javascript" src="/js/script.js?v=6.1" charset="utf-8"></script>
	<link rel="stylesheet" href="/css/font.css" charset="utf-8" type="text/css" media="screen" />
	<title><?php echo htmlspecialchars($title); ?></title>
	<?php head_theme();
    if($april_fools) {
        switch(mt_rand(1, 7)) {
            case 1:
                echo '<link rel="stylesheet" href="/css/april-fools/blur.css" type="text/css" media="screen" />';
                break;
            case 2:
                echo '<link rel="stylesheet" href="/css/april-fools/cursor.css" type="text/css" media="screen" />';
                break;
            case 3:
                echo '<link rel="stylesheet" href="/css/april-fools/rotate.css" type="text/css" media="screen" />';
                break;
            case 4:
                echo '<link rel="stylesheet" href="/css/april-fools/rainbow.css" type="text/css" media="screen" />';
                break;
        }
    }
	if(function_exists("printHead")) printHead();
	if(file_exists("css/content/$fn.mobile.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.mobile.css?v=3' />";
	else if(file_exists("css/content/$fn.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.css' />";
	if(file_exists("js/content/$fn.js")) echo "<script src='/js/content/$fn.js?v=2'></script>";
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
    <script>
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
</body></html>
