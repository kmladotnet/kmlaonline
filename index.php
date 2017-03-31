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
if(!file_exists("src/content/$fn.php"))
    $fn = "404";
include "src/lib.php";
if($april_fools && !$is_mobile) {
    if(mt_rand(1, 47) == 5) {
        $april_link = $_SERVER["REQUEST_URI"];
        $fn = "aprilfools";
    } else if(mt_rand(1, 45) == 2) {
        require("april-fools/internet/index.html");
        return;
    }
}
if(!isset($_SESSION['user'])) {
	if(isset($_GET['sub']) && $_GET['action'] == 'user') {
		switch($_GET['sub']) {
			case 'register':
			case 'lost':
            case 'resetpwd':
				break;
			default:
				$_REQUEST['returnto'] = $_SERVER["REQUEST_URI"];
				$_GET['sub'] = "login";
		}
	} else {
		$_REQUEST['returnto'] = $_SERVER["REQUEST_URI"];
		$fn = $_GET['action'] = "user";
		$_GET['sub'] = "login";
	}
}
do {
	$_fn = $fn;
	include "src/content/$fn.php";
} while($_fn != $fn);
session_write_close();
if(isAjax()) {
	require("index.ajax.php");
} else {
	if($is_mobile)
		require("index.mobile.php");
	else
		require("index.direct.php");
}
