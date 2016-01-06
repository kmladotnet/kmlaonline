<!doctype html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <base href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/" />
    <!--[if lte IE 7]><script type="text/javascript" src="/js/json2.js" charset="utf-8"></script><![endif]-->
    <!--[if gte IE 9]><style type="text/css">.gradient{filter: none;}</style><![endif]-->
    <script src="//cdn.jsdelivr.net/g/jquery@2.1.4,jquery.ui@1.11.4,jquery.smooth-scroll@1.6.1,jquery.autosize@3.0.14,jquery.shadow-animation@1.11.0,lodash@3.10.1"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.3/gridstack.min.js"></script>
    <script src="//cdn.ckeditor.com/4.5.6/full/ckeditor.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js"></script>
    <script type="text/javascript" src="/js/lightbox.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/ba-debug.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/swfupload/swfupload.js" charset="utf-8"></script>
    <script type="text/javascript" src="/swfupload/swfupload.queue.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/script.js?v=1.1" charset="utf-8"></script>
    <script type="text/javascript" src="/js/content/combined.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/snowstorm.js"></script>
    <script type="text/javascript">
        snowStorm.snowColor = '#99ccff';
        snowStorm.freezeOnBlur = true;
    </script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.3/gridstack.min.css" />
    <link rel="stylesheet" href="/css/lightbox.css" charset="utf-8" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/font.css" charset="utf-8" type="text/css" media="screen" />
    <title>
        <?php echo htmlspecialchars($title); ?>
    </title>
    <meta name="kmlaonline-changeable-start" />
    <?php head_theme();
	if(function_exists("printHead")) printHead();
	if(file_exists("css/content/$fn.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.css' />";
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
                <a href="http://kmlaonline.net" style="border-"><img id="menu-logo-image" src="/images/logo.png" alt="KMLAONLINE" style="width:20px;height:20px;padding-top:10px"></a>
            </div>
            <div id="menu-logo-2" style="width:40px;padding-left:10px;float:left;position:absolute;opacity:0;z-index:9999999">
                <a href="http://kmlaonline.net" style="border-"><img id="menu-logo-image-2" src="/images/logo-inverse.png" alt="KMLAONLINE" style="width:20px;height:20px;padding-top:10px"></a>
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
			if(isset($overriden)) echo "debug.info(".json_encode($overriden).");";
			if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
                /*-->*/
            </script>
            <script id="onload-scripts" type="text/text">
                <?php
		if(isset($_scripts)) echo $_scripts;
		if(isset($overriden)) echo "debug.info(".json_encode($overriden).");";
		if(isset($_POST['error_occured'])) echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
		?>
            </script>
</body>

</html>
