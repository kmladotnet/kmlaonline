<?php
$includes = array();
switch(isset($_GET['action']) ? $_GET['action'] : "main"){
    case "main":
        $fn = "main";
        break;
    default:
        $fn = basename($_GET['action']);
        break;
}
if(!file_exists("intranet/src/content/$fn.php"))
    $fn = "404";
include "intranet/src/lib_real.php";

do {
    $_fn = $fn;
    include "intranet/src/content/$fn.php";
} while($_fn != $fn);
session_write_close();

require("index.direct.php");
?>


<!--!DOCTYPE html>
<html>
<head>
    <title>Test Page - Department of Justice</title>
</head>
<body>
    <p>This is just a test page.</p>
    <?php
        //include 'lib_real.php';

        //if($db) echo "WOW <br />";
        //else echo "There is a problem <br />";
    ?>
    <a href="https://kmlaonline.net/intranet/user/accuse">기소하기(테스트)</a>
</body>
</html-->