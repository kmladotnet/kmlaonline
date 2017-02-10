<?php
date_default_timezone_set("Asia/Seoul");
include "presentool/PresentTool.php";

session_start();

include "lang/ko-kr.php";
$title = "KMLA Court";

$dbInit = false;
require "db-config.php";
session_write_close();

setlocale(LC_TIME, 'ko_KR.UTF-8');

function redirectTo($link){
    if(isAjax()){
        die("redirect:$link");
    }/*
    header("Location: $link");
    header("Status: 302 Moved");
    header("HTTP/1.1 302 Moved");
    */
    header("HTTP/1.1 200 OK");
    header("Status: 200 OK");
    ?>
        <!doctype html>
        <html>
        <head>
            <meta http-equiv="refresh" content="0; url=<?php echo htmlspecialchars($link)?>" />
            <script type="text/javascript">
                location.href = '<?php echo addslashes($link)?>';
            </script>
        </head>
        </html>
        <?php
    die();
}

function isAjax(){
    return isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_CONTENT_ONLY']) && $_SERVER['HTTP_X_CONTENT_ONLY']);
}
?>