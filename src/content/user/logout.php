<?php
session_start();
session_destroy();
setcookie("remember_user", "", time()-3600, "/");
session_start();
foreach($_SESSION as $k=>$v) unset($_SESSION[$k]);
redirectTo((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!="")?$_REQUEST['returnto']:"/");