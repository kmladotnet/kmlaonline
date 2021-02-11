<?php
$includes = array();
switch (isset($_GET['action']) ? $_GET['action'] : "main") {
    case "main":
        $fn = "main";
        break;
    default:
        $fn = basename($_GET['action']);
        break;
}

if (!file_exists("src/content/$fn.php")) {
    $fn = "404";
}

include "src/lib.php";

if (!isset($_SESSION['user'])) {
/** 세션이 없는 경우. 로그인을 안한 상태!
 *     'user'은 학생
 *     이 경우에 해당하지 않는다면, $_SESSION['user']는 initialize되어 있다는 뜻이다.
 */
    if (isset($_GET['sub']) && $_GET['action'] == 'user') {
        // 참고: $_GET['sub']가 있을 수 있는 경우: user, util, intranet일 때
        switch ($_GET['sub']) {
            // $_GET['sub']가 register, lost, resetpwd가 아닌 경우에는 모두 $_GET['sub']를 login으로 만들어 준다.
            case 'register':
            case 'lost':
            case 'resetpwd':
                break;
            default:
                $_REQUEST['returnto'] = $_SERVER["REQUEST_URI"];
                $_GET['sub'] = "login";
        }
    } else {
        // Action을 모두 user로 바꾸어 준다.
        $_REQUEST['returnto'] = $_SERVER["REQUEST_URI"];
        $fn = $_GET['action'] = "user";
        $_GET['sub'] = "login";
    }
}

do {
    $_fn = $fn;
    if (!isset($type)) {
        include "src/content/$fn.php";
    }

} while ($_fn != $fn);

session_write_close();

if (!isset($type)) {
    if (isAjax()) {
        require "index.ajax.php";
    } else {
        if ($is_mobile) {
            require "index.mobile.php";
        } else {
            require "index.direct.php";
        }
    }
}
