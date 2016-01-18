<!doctype html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- fontawesome -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- pnotify -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.brighttheme.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.confirm.min.js"></script>

    <!-- datatables -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/css/dataTables.bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/dataTables.bootstrap.min.js"></script>

    <!-- bootstrap-select -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>

    <!-- other -->
    <script src="/js/lightbox.js" charset="utf-8"></script>
    <script src="/swfupload/swfupload.js" charset="utf-8"></script>
    <script src="/swfupload/swfupload.queue.js" charset="utf-8"></script>
    <script src="/js/script.js?v=2.0" charset="utf-8"></script>
    <link rel="stylesheet" href="/css/lightbox.css" charset="utf-8" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/font.css" charset="utf-8" type="text/css" media="screen" />
    <title>
        <?php echo htmlspecialchars($title); ?>
    </title>
    <meta name="kmlaonline-changeable-start" />
    <?php head_theme();
	if(function_exists("printHead")) printHead();
    echo $fn;
	if(file_exists("css/content/$fn.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.css' />";
	if(file_exists("js/content/$fn.js")) echo "<script src='/js/content/$fn.js'></script>";
    if(isset($includes)){
		foreach($includes as $val){
			if(substr($val,-4,4)==".css") echo "<link class='page-specific-css' rel='stylesheet' href='$val' />";
		}
	}
	?>
        <style type="text/css">
            <?php if($me['n_level']==0) echo ".login-only{display:none}";
            if(TRUE!==$is_morning) echo ".morning{display:none}";
            if(TRUE!==$is_afternoon) echo ".afternoon{display:none}";
            if(TRUE!==$is_night) echo ".night{display:none}";
            ?>
        </style>
        <meta name="kmlaonline-changeable-end" />
</head>

<body>
    <img id="no-login-bg" style="position:fixed;left:0;top:0;display:none;z-index:-50;" />
    <div class="hide-on-upper-panel menu-shadow" style="left:0;width:100%;right:0;display:block;height:40px;box-shadow:1px 3px 100px rgba(255, 255, 255, 0.42);">
        <div class="total-header-menu-extend" style="position:absolute;left:0;width:100%;right:0;display:block;height:40px;"></div>
    </div>
    <div class="hide-on-upper-panel" style="position:absolute;background:rgb(21,70,107);left:0;width:100%;right:0;display:block;height:40px;z-index:-5;">
        <div class="total-header-extend-1" style="position:absolute;background:rgb(21,70,107);left:0;width:100%;right:0;display:block;height:40px;"></div>
    </div>
    <div class="hide-on-upper-panel" style="position:absolute;left:0;width:100%;right:0;display:block;height:160px;top:40px;z-index:-5;">
        <div class="total-header-extend-1" style="position:absolute;left:0;width:100%;right:0;display:block;height:160px;"></div>
        <div class="total-header-extend-2" style="position:absolute;left:0;width:100%;right:0;display:block;height:160px;"></div>
    </div>
    <div id="divSavingIndicatorFiller"></div>
    <div style="" id="divSavingIndicator">
        <div id="divSavingIndicatorInformation">
            <img src="/images/loading.gif" /> <span id="spnWhatAmIDoing">작업 중...</span>
            <br />
            <button id="cancelAjax" onclick="return cancelAjaxSave();" style="width:120px;height:32px;">취소</button>
        </div>
        <div id="debug" style="background:white;margin:0 auto;width:800px;display:none;margin-top:100px;"></div>
    </div>
    <div id="behind-total-wrap" style="position:fixed;left:0;top:0;height:100%;width:100%;display:none;background:#333"></div>
    <div id="total-wrap">
        <div id="upper-header-menu">
            <?php include "src/header/upper-header.php"; ?>
        </div>
        <div id="total-header-menu-outer">
            <div id="menu-logo" style="width:40px;padding-left:10px;float:left;position:absolute;z-index:9999999">
                <a href="http://kmlaonline.net" style="border-"><img id="menu-logo-image" src="/images/logo.png" alt="KMLAONLINE" style="width:20px;height:20px;margin-top:10px"></a>
            </div>
            <div id="menu-logo-2" style="width:40px;padding-left:10px;float:left;position:absolute;opacity:0;z-index:9999999">
                <a href="http://kmlaonline.net" style="border-"><img id="menu-logo-image-2" src="/images/logo-inverse.png" alt="KMLAONLINE" style="width:20px;height:20px;margin-top:10px"></a>
            </div>
            <div id="total-header-menu" ondragstart="return false" onselectstart="return false">
                <?php include "src/header/menubar.php";?>
            </div>
        </div>
        <div style="clear:both"></div>
        <div id="below-header-menu">
            <?php include "src/header/below-header.php"; ?>
        </div>
    </div>
    <div id="total-footer" class="hide-on-upper-panel">
        <?php echo langraw("layout","footer"); ?>
    </div>
    <?php if($detect->isMobile() || $detect->isTablet()){
		$puri=strtok($_SERVER["REQUEST_URI"],'?');
		unset($_GET['force_mobile'], $_GET['force_desktop']);
		$_GET['force_mobile']=1;
		$i=0;
		foreach($_GET as $k=>$v) $puri.=($i++==0?"?":"&").urlencode($k)."=".urlencode($v);
		?>
        <a style="display:block;width:100%;font-size:64pt;text-align:center;padding: 64px 0;background:#EEE" href="<?php echo htmlspecialchars($puri) ?>">모바일으로 보기</a>
        <?php
	} ?>
            <script type="text/javascript">
                /*<!--*/
                <?php
			if(isset($_scripts)) echo $_scripts;
			if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
                /*-->*/
            </script>
            <script id="onload-scripts" type="text/text">
                <?php
		if(isset($_scripts)) echo $_scripts;
		if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
            </script>
</body>

</html>
