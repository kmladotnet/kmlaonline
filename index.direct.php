<!doctype html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- favicons -->
    <?php //수정할 때 http://realfavicongenerator.net/ 이용할 것. ?>
    <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png?v=XBrA5kwEdx">
    <link rel="icon" type="image/png" href="/icon/favicon-32x32.png?v=XBrA5kwEdx" sizes="32x32">
    <link rel="icon" type="image/png" href="/icon/favicon-16x16.png?v=XBrA5kwEdx" sizes="16x16">
    <link rel="manifest" href="/icon/manifest.json?v=XBrA5kwEdx">
    <link rel="mask-icon" href="/icon/safari-pinned-tab.svg?v=XBrA5kwEdx" color="#074275">
    <link rel="shortcut icon" href="/icon/favicon.ico?v=XBrA5kwEdx">
    <meta name="msapplication-config" content="/icon/browserconfig.xml?v=XBrA5kwEdx">
    <meta name="theme-color" content="#074275">

    <base href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/" />

    <!-- gridstack -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.css" />

    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- velocity -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.4.1/velocity.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.4.1/velocity.ui.min.js"></script>

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" />

    <!-- pnotify -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.confirm.min.js"></script>
    <script type="text/javascript"> PNotify.prototype.options.styling = "fontawesome"; </script>

    <!-- bootstrap-select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

    <!-- lightbox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.js"></script>

    <!-- rateYo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.1.1/jquery.rateyo.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.1.1/jquery.rateyo.min.js"></script>

    <!-- converse.js-->
    <!--link rel="stylesheet" type="text/css" media = "screen" href="css/converse/converse.css"/>
    <script data-main="main" src="js/converse/requirejs/require.js"></script-->

    <!-- other -->
    <script src="/swfupload/swfupload.js"></script>
    <script src="/swfupload/swfupload.queue.js"></script>
    <script src="/js/script.js?v=7.3"></script>
    <?php if(getTheme($me)['pinmenu']) { ?>
        <script>
            keepMenuShown = true;
        </script>
    <?php } ?>

    <link rel="stylesheet" href="/css/font.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/sass-compiled/screen.css?v=6" type="text/css" media="screen" />

    <?php
    if($april_fools) {
        switch(mt_rand(1, 16)) {
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
    if(!!$me) {
        $theme = getTheme($me);
        if($theme['square']) { ?>
            <link rel="stylesheet" href="/sass-compiled/square.css" type="text/css" media="screen" />
        <?php }
        if($theme['gradients']) { ?>
            <link rel="stylesheet" href="/sass-compiled/gradients.css" type="text/css" media="screen" />
        <?php }
        if($theme['noanim']) { ?>
            <script>
                $(function() {
                    $.Velocity.mock = true;
                });
            </script>
        <?php }
    }?>
    <title>
        <?php echo formatTitle($title); ?>
    </title>

    <?php
	if(function_exists("printHead")) printHead();
	if(file_exists("css/content/$fn.css")) echo "<link class='page-specific-css' rel='stylesheet' href='/css/content/$fn.css?v=4' />";
	if(file_exists("js/content/$fn.js")) echo "<script src='/js/content/$fn.js?v=2'></script>";
    if(isset($includes)) {
		foreach($includes as $val) {
			if(substr($val,-4,4)==".css") echo "<link class='page-specific-css' rel='stylesheet' href='$val' />";
		}
	}
	?>
    <style type="text/css">
        <?php
        if($me['n_level']==0) echo ".login-only{display:none}";
        if(!$is_morning) echo ".morning{display:none}";
        if(!$is_afternoon) echo ".afternoon{display:none}";
        if(!$is_night) echo ".night{display:none}";
        ?>
    </style>



</head>

<body>
    <img id="no-login-bg" style="position:fixed;left:0;top:0;display:none;z-index:99999999;" />
    <div class="hide-on-upper-panel menu-shadow" style="left:0;width:100%;right:0;display:block;height:40px;    box-shadow: 0 0px 20px rgba(0, 0, 0, 0.5);">
        <div class="total-header-menu-extend" style="position:absolute;left:0;width:100%;right:0;display:block;height:40px;"></div>
    </div>
    <?php if(!(!!$me && getTheme($me)['hidedasan'])) { ?>
        <div class="hide-on-upper-panel" style="position:absolute;background:#074275;left:0;width:100%;right:0;display:block;height:40px;z-index:-5;">
            <div class="total-header-extend-1" style="position:absolute;background:#074275;left:0;width:100%;right:0;display:block;height:40px;"></div>
        </div>
        <div class="hide-on-upper-panel" style="position:absolute;left:0;width:100%;right:0;display:block;height:160px;top:40px;z-index:-5;">
            <div class="total-header-extend-1" style="position:absolute;left:0;width:100%;right:0;display:block;height:160px; <?php if($april_fools_2) echo 'height:331px; background: url(/images/bamboozle-bg.png) repeat-x center bottom;'; ?>"></div>
            <div class="total-header-extend-2" style="position:absolute;left:0;width:100%;right:0;display:block;height:160px;"></div>
        </div>
    <?php } ?>
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
            <?php //TODO: 스타일 밖으로 뺄 것 ?>
            <div id="menu-logo" style="width:40px;padding-left:10px;left:-40px;float:left;position:absolute;z-index:9999999">
                <a href="/" style="border-"><img id="menu-logo-image" src="/images/logo-s.png" alt="KMLAONLINE" style="width:20px;height:20px;margin-top:10px"></a>
            </div>
            <div id="menu-logo-2" style="width:40px;padding-left:10px;left:-40px;float:left;position:absolute;opacity:0;z-index:9999999">
                <a href="/" style="border-"><img id="menu-logo-image-2" src="/images/logo-inverse-s.png" alt="KMLAONLINE" style="width:20px;height:20px;margin-top:10px"></a>
            </div>
            <div id="total-header-menu" ondragstart="return false">
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
    <?php if($is_mobile) {
		$puri = strtok($_SERVER["REQUEST_URI"],'?');
		unset($_GET['force_mobile'], $_GET['force_desktop']);
		$_GET['force_mobile'] = 1;
		$i = 0;
		foreach($_GET as $k=>$v)
            $puri .= ($i++ == 0 ? "?" : "&").urlencode($k)."=".urlencode($v);
        ?><a style="display:block;width:100%;font-size:64pt;text-align:center;padding: 64px 0;background:#EEE" href="<?php echo htmlspecialchars($puri) ?>">
            모바일으로 보기
        </a>
    <?php } ?>
    <script type="text/javascript">
        <?php
            if(isset($_scripts)) echo $_scripts;
            if(isset($_POST['error_occured']))
                echo "checkAjaxReturnedData(JSON.parse(\"".str_replace("</", "<\" + \"/",addslashes($_POST['error_occured']))."\"));";
        ?>
    </script>
    <script>
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
</body>

</html>
