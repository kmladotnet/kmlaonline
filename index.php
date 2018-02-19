<?php
$includes = array();
switch(isset($_GET['action']) ? $_GET['action'] : "main"){
	case "main":
	    $fn = "main";
        if(isset($_SESSION['teacher_user'])) $type = "teacher";
        break;
    case "judicial":
        $type = "judicial";
        if(isset($_GET['action_type'])) $fn = $_GET['action_type'];
        else $fn = "main";
        break;
    case "teacher":
        $type = "teacher";
        if(isset($_GET['action_type'])) $fn = $_GET['action_type'];
        else $fn = "main";
        break;
	default:
        $fn = basename($_GET['action']);
        break;
}
if(isset($type) && $type === "judicial"){
    if(!file_exists("judicial/content/$fn.php"))
        $fn = "404";
} else if(isset($type) && $type === "teacher"){
    if(!file_exists("teacher/content/$fn.php"))
        $fn = "404";
} else if(!file_exists("src/content/$fn.php")) {
    $fn = "404";
}

include "src/lib.php";

if(!isset($type) && $april_fools && !$is_mobile) {
    if(mt_rand(1, 47) == 5) {
        $april_link = $_SERVER["REQUEST_URI"];
        $fn = "aprilfools";
    } else if(mt_rand(1, 45) == 2) {
        require("april-fools/internet/index.html");
        return;
    }
}

if(!isset($_SESSION['user']) && !isset($_SESSION['teacher_user'])) {
/** 학생 교직원 둘 다 아닌 경우. 로그인을 안한 상태!
 * 	'user'은 학생, 'teacher_user'은 선생님
 * 	이 경우에 해당하지 않는다면, $_SESSION['user'] $_SESSION['teacher_user']
 * 	둘 중 적어도 하나는 initialize되어 있다는 뜻이다.
 */
	if(isset($_GET['sub']) && $_GET['action'] == 'user') {
	// 참고: $_GET['sub']가 있을 수 있는 경우: user, util, intranet일 때
		switch($_GET['sub']) {
        // $_GET['sub']가 register, lost, resetpwd가 아닌 경우에는 모두 $_GET['sub']를 login으로 만들어 준다.
			case 'register':
			case 'lost':
            case 'resetpwd':
				break;
			default:
				$_REQUEST['returnto'] = $_SERVER["REQUEST_URI"];
				$_GET['sub'] = "login";
		}
	} else if(isset($type) && $type === "judicial"){
        redirectLoginIfRequired();
    } else if(isset($type) && $type === "teacher"){
        redirectLoginIfRequired();
    } else {
    // Action을 모두 user로 바꾸어 준다.
		$_REQUEST['returnto'] = $_SERVER["REQUEST_URI"];
		$fn = $_GET['action'] = "user";
		$_GET['sub'] = "login";
	}
} else if(!isset($_SESSION['teacher_user']) && isset($_GET['action']) && $_GET['action'] === "teacher") {
// 학생 유저가 교직원 페이지에 접근하려고 하는 경우
    ?>
    <script type="text/javascript">
        alert("학생 유저는 교직원 페이지에 접근할 수 없습니다.");
        location.href = "/";
    </script>
    <?php
} else if(!isset($_SESSION['user']) && (!isset($_GET['action']) || isset($_GET['action']) && $_GET['action'] !== "teacher")){
// 교직원 유저가 학생 페이지에 접근하려고 하는 경우
    if(isset($_GET['sub']) && $_GET['action'] == 'user') {
        if($_GET['sub'] !== 'logout') {
        ?>
            <script type="text/javascript">
                alert("교직원 유저는 학생 페이지에 접근할 수 없습니다.");
                location.href = "/teacher/main";
            </script>
        <?php
        }
    } else {
        echo $_GET['action'];
        ?>
            <script type="text/javascript">
                alert("교직원 유저는 학생 페이지에 접근할 수 없습니다.");
                location.href = "/teacher/main";
            </script>
        <?php
    }
}

if(isset($type) && $type === "judicial" && !(isUserPermitted($me['n_id'], "judicial_council") || isUserPermitted($me['n_id'], "justice_department") || isUserPermitted($me['n_id'], "student_guide_department") || isUserPermitted($me['n_id'], "food_and_nutrition_department"))) {?>
	<script type="text/javascript">
		alert("현재 개발 중으로 허가 받은 사람만 접근 가능합니다.");
		location.href="/";
	</script>
<?php
}

do {
	$_fn = $fn;
	if(!isset($type)) include "src/content/$fn.php";
    else if($type === "judicial") include "judicial/content/$fn.php";
    else if($type === "teacher") include "teacher/content/$fn.php";
} while($_fn != $fn);
session_write_close();
if(!isset($type)){
    if(isAjax()) {
    	require("index.ajax.php");
    } else {
    	if($is_mobile)
    		require("index.mobile.php");
    	else
    		require("index.direct.php");
    }
}
