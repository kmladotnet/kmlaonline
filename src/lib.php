<?php
date_default_timezone_set("Asia/Seoul");
include "src/soreeengine/SoreeTools.php"; // DB Manager
include "src/range.php"; // HTTP Range Tools
include "src/zipstream.php"; // ZIP Streaming Tools

$max_level = 22; // 현재 22기까지

/********************** START INITIALIZATION SESSION ************************/
if(isset($_POST["_CUSTOM_PHPSESSID"])) { // For Flash Upload Plugin
	if(strlen($_POST["_CUSTOM_PHPSESSID"]) < 64)
		session_id($_POST["_CUSTOM_PHPSESSID"]);
}
session_save_path('/tmp');
session_start();
$ua_mobile = preg_match('/Mobile|Android|BlackBerry/', $_SERVER['HTTP_USER_AGENT']);
$is_android = preg_match('/Android/', $_SERVER['HTTP_USER_AGENT']);
$is_mobile = isset($_SESSION['forceMode']) ? $_SESSION['forceMode'] : $ua_mobile;
if(isset($_GET['force_mobile'])) {
	if($ua_mobile) unset($_SESSION['forceMode']);
	else $_SESSION['forceMode'] = true;
	$is_mobile = true;
} else if(isset($_GET['force_desktop'])) {
	if(!$ua_mobile) unset($_SESSION['forceMode']);
	else $_SESSION['forceMode'] = false;
	$is_mobile = false;
}
include "theme/theme.php"; // Theme Definition
include "lang/ko-kr.php"; // Language Definition
$title = "KMLA Online"; // Page Title
$bDoInit = false; /* DO NOT INITIALIZE - YOU'VE BEEN WARNED */
require(__DIR__."/db-config.php"); /* LOAD DATABASE - DO NOT REMOVE */

if(isset($_COOKIE['remember_user'])) { // 자동 로그인
	$rem = preg_replace('[^A-Za-z0-9]', '', $_COOKIE['remember_user']);
	if(file_exists($rempath = "data/session/$rem")) {
		$_SESSION['user'] = file_get_contents($rempath);
		$me = $member->getMember($_SESSION['user']);
		if($me === false){
			unlink($rempath);
			unset($_SESSION['user']);
			setcookie("remember_user", "", time() - 3600, "/");
		}
	}
}


if(isset($_SESSION['user'])) {
	$me = $member->getMember($_SESSION['user']);
	if($me === false) {
		session_destroy();
		session_start();
	} else {
		if($me['n_access_date'] + 60 <= time())
			$member->recordMemberAccess($me['n_id']);
	}
} else {
	$me = $member->getMember(1);
}
/*
if(!isset($_SESSION['tmp_password']) || !file_exists('/tmp/passwords/'.$me['s_id'])) {
    $_SESSION['tmp_password'] = base64_encode(mt_rand());
    file_put_contents('/tmp/passwords/'.$me['s_id'], $_SESSION['tmp_password']);
}
*/
session_write_close();
/********************** END INITIALIZATION SESSION ************************/
include 'db-test.php';

setlocale(LC_TIME, 'ko_KR.UTF-8');
$april_main = false; // 4월 1일에 이 변수만 바꾸기 바람
$april_fools = $april_main;
$april_fools_2 = false;
if($april_fools) {
	if(mt_rand(1, 12) == 4) {
		$april_fools_2 = true;
	}
}
if(!function_exists("header_remove")) {
	function header_remove($header){
        header($header.':');
    }
}
$hr = date("G");
$is_morning = $is_afternoon = $is_night = false;
if($hr < 8 || $hr >= 22) $is_morning = true; // 혼정빵은 10시 이후에 끝
elseif($hr >= 8 && $hr < 13) $is_afternoon = true;
else $is_night = true;
$curYear = date("Y");
$curMonth = date("n");
$curDay = date("j");
if($is_morning && date("H") >= 22) {
    $curYear = date("Y", strtotime("+1 day"));
    $curMonth = date("n", strtotime("+1 day"));
    $curDay = date("j", strtotime("+1 day"));
}

function redirectAlert($lnk = false, $alert = false) {
	?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Redirecting...</title>
        <base href="/" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script type="text/javascript" src="/js/script.js" charset="utf-8"></script>
    </head>

    <body>
        <script type="text/javascript">
            <?php
                if($alert !== false) echo "alert('".addslashes($alert)."');";
                if($lnk === false) {
                    echo "history.go(-1);setTimeout(\"location.href='/';\",1000);";
                } else {
                    $lnk = addslashes($lnk);
                    echo "location.href='$lnk';";
                }
            ?>
        </script>
    </body>
    </html>
    <?php
	die();
}
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
function redirectWith($str,$dat=""){
	?>
            <!doctype html>
            <html>
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <title>Redirecting...</title>
                <base href="/" />
                <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
                <script type="text/javascript" src="/js/script.js" charset="utf-8"></script>
            </head>

            <body>
                <?php
                    call_user_func($str,$dat);
                ?>
            </body>
            </html>
            <?php
	die();
}
function redirectLoginIfRequired(){
	if(isset($_SESSION['user'])) return;
	if(isAjax()){
		die("redirect:/user/login/required?returnto=".urlencode($_SERVER['REQUEST_URI']));
	}
	?>
    <!doctype html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Redirecting...</title>
        <base href="/" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script type="text/javascript" src="/js/script.js" charset="utf-8"></script>
    </head>

    <body>
        <form method="post" action="user/login/required" id="poster">
            <input type="hidden" name="returnto" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" />
            <input type="submit" id="submitter" value="Click here if the page doesn't continue" />
        </form>
        <script type="text/javascript">
            $('#poster').submit();
            $('#submitter').css("visibility", "hidden");
        </script>
    </body>

    </html>
    <?php
	die();
}

function isAjax(){
	return isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_CONTENT_ONLY']) && $_SERVER['HTTP_X_CONTENT_ONLY']);
}
function ajaxDie($arr=array(), $message=false){
	global $overriden;
	if($message!==false)
		$arr['__other']=$message;
	$arr['error']=1;
	if(isset($overriden)) $arr['__overriden']=$overriden;
	die(json_encode($arr));
}
function ajaxOk($arr=array(), $redir_to=false, $alert_message=false, $confirm_message=false){
	global $overriden;
	if($redir_to!==false) $arr['redirect_to']=$redir_to;
	if($alert_message!==false) $arr["alert_message"]=$alert_message;
	if($confirm_message!==false) $arr["confirm_message"]=$confirm_message;
	$arr['error']=0;
	if(isset($overriden)) $arr['__overriden']=$overriden;
	die(json_encode($arr));
}
function putUserCard($m,$mode=0, $putNow=true){
	$str="";
	if($m['s_icon']) $str.="<img src='".htmlspecialchars($m['s_icon'])."' style='width:12pt;height:12pt;vertical-align:middle;' />";
	// else $str.="<img src='data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' style='width:12pt;height:12pt;vertical-align:middle;' />";
	if($m['n_level']>0 && $m['n_level']<10000){
		switch($mode){
			case 0: $str.= $m['n_level'] . "기 " . htmlspecialchars($m['s_name']); break;
			case 1: $str.= htmlspecialchars($m['s_name']) . " (" . $m['n_level'] . "기)"; break;
		}
	}else
		$str.=htmlspecialchars($m['s_name']);
	if($putNow) echo $str;
	return $str;
}
/**
* function getMyBasicInfo - 외출 외박 신청시 유저의 기본 정보를 받아오기 위한 함수임
*/
function getMyBasicInfo(){
    global $me;
    $res = array();
    $res['name'] = $me['s_name'];
    $res['grade'] = $me['grade'];
}
function die404(){
	global $fn; $fn="404"; return;
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	die("<h1>404 Not Found</h1>");
}
function die403(){
	global $fn; $fn="403"; return;
	header("HTTP/1.1 403 Access Denied");
	header("Status: 403 Access Denied");
	die("<h1>403 Access Denied</h1>");
}
function filterContent($s, $print=true){
	global $member;
	$s = preg_replace(
			array(
				'~(?![^"\']|^)(www\.[^<]+?)(\s|\r|\n|$)~im',
				'%(?<=[^="\'])(https?://)([^<]+?)(?=[\s"\'><]|$)%im',
				'/(\s)on([a-zA-Z0-9_]+)([^a-zA-Z0-9_])/sim',
			),
			array(
				'http://$1$2',
				'<a href="$1$2" target="_blank">$2</a>',
				'$1on<span></span>$2$3',
			),
			$s
		);
	if(preg_match_all('/@(((찾기)\\{[^}]*\\}|[^\\s`~!@#$%^&*\\(\\)\\-=_+\\[\\]\\\\{}\\|;\':\",.\\/\\<\\>\\?]*))/i',strip_tags($s),$tagged)>0){
		$triggered=array();
		foreach($tagged[1] as $val){
			if(substr($val,0,6)==="찾기"){
				$search=substr($val,7); $search=substr($search,0,strlen($search)-1); $search=html_entity_decode($search);
				$triggered["@".$val]="@<a href=\"/searchall?search=".urlencode($search)."\">$val</a>";
			}else{
				if(preg_match("/([0-9]+)(.*)/i",$val,$tmp)>0){
					if($tmp[2]==="기"){
						$triggered["@".$val]="@<a href=\"/contacts?wave={$tmp[1]}\">{$tmp[1]}기</a>";
					}else{
						$trigger_temp=array();
						foreach($member->listMembers(0,0,false, $tmp[2],true,true, false, true) as $usr){
							if($usr['s_name']==$tmp[2] && $usr['n_level']==$tmp[1]){
								$trigger_temp[]=$usr;
							}
						}
						if(count($trigger_temp)==1){
							$usr=$trigger_temp[0];
							if($usr['s_icon'])
								$triggered["@".$val]="<a href=\"/user/view/{$usr['n_id']}/{$usr['s_id']}\"><img src='".htmlspecialchars($usr['s_icon'])."' style='width:12pt;height:12pt;' alt='@' />{$usr['n_level']}{$usr['s_name']}</a>";
							else
								$triggered["@".$val]="@<a href=\"/user/view/{$usr['n_id']}/{$usr['s_id']}\">{$usr['n_level']}{$usr['s_name']}</a>";
						}else if(count($trigger_temp)>1){
							foreach($trigger_temp as $key=>$usr){
								$trigger_temp[$key]="<a href=\"/user/view/{$usr['n_id']}/{$usr['s_id']}\">{$usr['s_id']}</a>";
							}
							$triggered["@".$val]="@$val <span style='font-size:9pt;'>(".implode(", ",$trigger_temp).")</span>";
						}
					}
				}
			}
		}
		foreach($triggered as $from=>$to){
			$s=str_replace($from, $to, $s);
		}
	}
	//*
	$dom = new DOMDocument('1.0', 'utf-8');
	$dom->recover=true;
	$dom->substituteEntities=false;
	$dom->preserveWhiteSpace=false;
	@$dom->loadHTML('<?xml encoding="UTF-8">'.$s);
	$s=$dom->saveHTML();
	//*/
	$s=strip_tags($s,"<input><form><button><u><span><iframe><embed><object><param><audio><video><div><hr><strong><br><h1><h2><h3><h4><h5><h6><p><blockquote><pre><a><abbr><acronym><address><big><cite><code><del><dfn><font><img><ins><kbd><q><s><samp><small><strike><sub><sup><tt><dl><dt><dd><ol><ul><li><fieldset><form><label><legend><table><caption><tbody><tfoot><thead><tr><th><td>");
	if($print)echo $s;
	return $s;
}
function doesAdminBypassEverythingAndIsAdmin($prev=false){
	global $me;
	if(!isset($_SESSION['admin_override'])) return $prev; // NO
	if(!$prev && $me['n_admin']!=0) $overriden[]=array("?", "?");
	return $prev || ($me['n_admin']!=0); // YES
}
function checkCategoryAccess($category, $action, $redirectIfDenied=false){
	global $board, $me, $overriden;
	if(!isset($_SESSION['user'])){
		switch(str_replace(" ","",$action)){
			case "write":
			case "edit":
			case "delete":
			case "commentwrite":
			case "commentedit":
			case "commentdelete":
			case "attachupload":
			case "managemodify":
			case "managepermission":
				return false;
		}
	}
	$res=$board->isUserAllowed($category, isset($_SESSION['user'])?$me:false, $action, isset($_SESSION['admin_override']));
	if($res==-1)
		$overriden[]=array($category, $action);
	if(!$res && $redirectIfDenied) redirectAlert(false, lang("user","permission","error","lack"));
	return $res?true:false;
}
function getLinkDescription($lnk){
	global $board;
	if(strstr($lnk,"?")) $lnk=substr($lnk,0,strpos($lnk,"?"));
	$fnc=substr($lnk,1); if(strpos($fnc, "/")!==false) $fnc=substr($fnc,0,strpos($fnc, "/"));
	$sub=substr($lnk,strlen($fnc)+2); if(strpos($sub, "/")!==false) $sub=substr($sub,0,strpos($sub, "/"));
	switch($fnc){
		case "board":
			$b=$board->getCategory(false, $sub);
			if($b===false) return false;
			return str_replace("%1%",$b['s_name'],lang("link titles","board","main"));
		case "util":
			return lang("link titles","util",$sub);
		case "user":
			return lang("link titles","user",$sub);
		case "":
			return lang("link titles","");
	}
	return $lnk;
}
function getUserMenuBar($user){
	global $board;
	$file="data/user/menu_bar/{$user['n_id']}.txt";
	if(file_exists($file)){
		$current_setting=unserialize(file_get_contents($file));
	}else{
		$current_setting=array(
			lang("layout","menu","personalization","")=>array(
				array("url","/user/settings?display=2",lang("layout","menu","personalization","settings"))
			)
		);
	}
	return $current_setting;
}
function arrayToCategories($array) {
	global $board;

    $multi_id = array();

    foreach($array as $i) {
        if(checkCategoryAccess($i, "list")) {
            $multi_id[$i] = $i;
        }
    }

    return $multi_id;
}

function defaultUserMainBoards($user) {
	global $board;
    $current_setting=array();
    foreach($board->getCategoryList(0,0) as $val){
        if(checkCategoryAccess($val['n_id'], "list")){
            if(strpos($val['s_id'],"announce")!==false)
                $current_setting[$val['n_id']]=$val['n_id'];
            if(strpos($val['s_id'],"forum")!==false)
                $current_setting[$val['n_id']]=$val['n_id'];
            if(strpos($val['s_id'],"all_")!==false)
                $current_setting[$val['n_id']]=$val['n_id'];
            if(strpos($val['s_id'],"wave".$user['n_level']."_")!==false)
                $current_setting[$val['n_id']]=$val['n_id'];
        }
    }
    return $current_setting;
}

function getUserMainBoards($user){
	global $board;
	$file="data/user/board_on_main/{$user['n_id']}.txt";
	if(file_exists($file)) {
		$current_setting=unserialize(file_get_contents($file));
		foreach($current_setting as $key=>$val){
			if(!checkCategoryAccess($key, "list"))
				unset($current_setting[$key]);
		}
        if(count($current_setting) === 0) {
            return defaultUserMainBoards($user);
        }
	} else {
		return defaultUserMainBoards($user);
	}
	return $current_setting;
}
function getCategoriesWithFixes($prefix="", $postfix=""){
	global $me, $board, $member;
	$accessible_categories=array();
	foreach($board->getCategoryList(0,0) as $val){
		if(checkCategoryAccess($val['n_id'], "list")){
			if($prefix==="" || strcmp(substr($val['s_id'],0,strlen($prefix)),$prefix)==0){
				if($postfix==="" || strcmp(substr($val['s_id'],-strlen($postfix)),$postfix)==0)
					$accessible_categories[$val['n_id']]=$val['n_id'];
			}
		}
	}
	return $accessible_categories;
}
function putAlert($s){
	?><script type="text/javascript">alert("<?php echo str_replace("<", "\"+\"<", addslashes($s)) ?>");</script><?php
}
function insertOnLoadScript($sc){
	global $_scripts;
	$_scripts.=$sc;
}
function isUserPermitted($user, $actName){
	global $mysqli;
	$actName=$mysqli->real_escape_string($actName);
	if(!is_numeric($user)) return false;
	if(doesAdminBypassEverythingAndIsAdmin()) return true;
	if(false!==$res=$mysqli->query("SELECT n_permission FROM kmlaonline_special_permissions_table WHERE n_user=$user AND s_type='$actName'"))
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			return $row['n_permission'];
		}
	return false;
}
function isUserJudicialMember($user){
    if(!is_numeric($user)) return false;
    if(doesAdminBypassEverythingAndIsAdmin()) return true;
    if(isUserPermitted($user, "judicial_council") || isUserPermitted($user, "justice_department") || isUserPermitted($user, "student_guide_department") || isUserPermitted($user, "food_and_nutrition_department")) return true;
    return false;
}
function isUserDotnetApplicant($user){
    if(!is_numeric($user)) return false;
    if(doesAdminBypassEverythingAndIsAdmin()) return true;
    if(isUserPermitted($user, "dotnet_applicant")) return true;
    return false;
}
function permitUser($user, $actName, $access){
	global $mysqli;
	$actName=$mysqli->real_escape_string($actName);
	if(!is_numeric($user)) return false;
	if(!is_numeric($access)) return false;
	$mysqli->query("DELETE FROM kmlaonline_special_permissions_table WHERE n_user=$user AND s_type='$actName'");
	if($access==0) return false;
	return (false!==$res=$mysqli->query("INSERT INTO kmlaonline_special_permissions_table (n_user, s_type, n_permission) VALUES ($user, '$actName', $access)"));
}
function listDotnetApplicants(){
    global $mysqli, $member;
    if(false!==$res=$mysqli->query("SELECT * FROM kmlaonline_special_permissions_table WHERE s_type='dotnet_applicant'")){
        $arr=array();
        while ($row = $res->fetch_array(MYSQLI_BOTH)){
            $m=$member->getMember($row['n_user']);
            $arr[$m['n_id']]=$m;
        }
        $res->close();
        return $arr;
    }
    return false;
}
function linkLibraryAccount($id, $password) {
    global $mysqli;
    $id = $mysqli->real_escape_string($id);
    $password = $mysqli->real_escape_string($password);
    if($res = $mysqli->query("SELECT * FROM kmlaonline_library_user_data WHERE library_id = '$id'")){
        if(mysqli_num_rows($res) === 0) {
            $result = $mysqli->query("INSERT INTO kmlaonline_library_user_data (library_id, password) VALUES ('$id', '$password')");
        } else {
            $result = $mysqli->query("UPDATE kmlaonline_library_user_data SET password = '$password' WHERE library_id = '$id'");
        }
    } else {
        return false;
    }
    return $result;
}

function getLibraryUserInfo($id) {
    global $mysqli;
    $id = $mysqli->real_escape_string($id);
    $res = $mysqli->query("SELECT * FROM kmlaonline_library_user_data WHERE library_id = '$id'");
    if($res) {
        $row = $res->fetch_array(MYSQLI_ASSOC);
        return $row['password'];
    } else {
        return false;
    }
}

function signIntoLibrary($id, $password) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');

    $url = 'http://lib.minjok.hs.kr/usweb/set16/USMN012.asp?mnid=' . $id . "&mnpw=" . $password;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/library/$id");
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/library');
    $headers = array(
        "Access-Control-Allow-Origin: *",
        "Content-Length: 0",
        "Connection: Keep-Alive",
        "Content-type: application/x-www-form-urlencoded;charset=EUC-kr"
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $output_ = curl_exec($ch);
    mb_convert_encoding($output_, "UTF-8", "EUC-KR");
    curl_setopt($ch, CURLOPT_URL, 'http://lib.minjok.hs.kr/usweb/set16/USMN000_16.asp');
    curl_setopt($ch, CURLOPT_POST, false);
    $output = curl_exec($ch);
    $encoded_output = mb_convert_encoding($output, "UTF-8", "EUC-KR");
    $dom = new DOMDocument('1.0', 'utf-8');
    @$dom->loadHTML($output);
    $login_box = $dom->getElementById('mbody32');

    if($login_box){
        return $ch;
    } else {
        return false;
    }
}

function convertFromBytes($value){
	if($value<1024) return $value . " B";
	if($value/1024<1024) return round($value/1024,2) . " KB";
	if($value/1048576<1024) return round($value/1048576,2) . " MB";
	return round($value/1048576/1024,2) . " GB";
}
function sanitizeFileName($fn){
	$fn=preg_replace("%([\\\/\\:\\*\\?\\\"\\<\\>\\|]+)%i", "-", $fn);
	return $fn;
}
function convertToBytes( $value ) {
	if ( is_numeric( $value ) ) {
		return $value;
	} else {
		$value_length = strlen( $value );
		$qty = substr( $value, 0, $value_length - 1 );
		$unit = strtolower( substr( $value, $value_length - 1 ) );
		switch ( $unit ) {
			case 'k': $qty *= 1024; break;
			case 'm': $qty *= 1048576; break;
			case 'g': $qty *= 1073741824; break;
		}
		return $qty;
	}
}
function resizeImage($path, $thumb_name, $sizex, $sizey){
	if(($source_image=@imagecreatefromstring(@file_get_contents($path)))!==false){
		$angle=0;
		if(($exif = exif_read_data($path))!==FALSE){
			switch ($exif['Orientation']) {
				case 3: $angle = 180; break;
				case 6: $angle = -90; break;
				case 8: $angle = 90; break;
			}
		}
		$width = imagesx($source_image);
		$height = imagesy($source_image);

		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * (($sizex==0?$sizey:$sizex) / $width));
		$desired_width = floor($width * (($sizey==0?$sizex:$sizey) / $height));

		/* create a new, "virtual" image */
        if($width > $sizex || $height > $sizey) {
            if($sizex!=0 && $sizey!=0) {
                $virtual_image = imagecreatetruecolor($sizex, $sizey);
                if($desired_height>$sizey) // Cut vertically
                    imagecopyresampled($virtual_image, $source_image, 0, -(($desired_height-$sizey)/2), 0, 0, $sizex, $desired_height, $width, $height);
                else // Cut horizontally
                    imagecopyresampled($virtual_image, $source_image, -(($desired_width-$sizex)/2), 0, 0, 0, $desired_width, $sizey, $width, $height);
            } elseif($sizey==0) {
                if($desired_height>$height){
                    $virtual_image = imagecreatetruecolor($width, $height);
                    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $width, $height, $width, $height);
                } else {
                    $virtual_image = imagecreatetruecolor($sizex, $desired_height);
                    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $sizex, $desired_height, $width, $height);
                }
            }
        } else {
            $virtual_image = $source_image;
        }
		if($angle==180)
			imageflip($virtual_image,IMG_FLIP_BOTH);
		elseif($angle==90 || $angle==-90){
			imagesetinterpolation($virtual_image, IMG_HERMITE);
			$virtual_image = imagerotate($virtual_image, $angle, 0);
		}
        $image_type = exif_imagetype($path);
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($virtual_image, $thumb_name, 100);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($virtual_image, $thumb_name);
        } elseif ($image_type == IMAGETYPE_PNG) {
            // need this for transparent png to work
            imagesavealpha($virtual_image, true);
            imagepng($virtual_image, $thumb_name);
        }
		return $thumb_name;
	}
	return;
}
function clamp($val, $min, $max) {
    return $val > $min ? ($val < $max ? $val : $max) : $min;
}
function getOrDefault(&$var, $default=null) {
    return isset($var) ? $var : $default;
}

function httpPost($url, $data) {
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($data));
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}

function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (is_dir($dir."/".$object))
           rrmdir($dir."/".$object);
         else
           unlink($dir."/".$object);
       }
     }
     rmdir($dir);
   }
 }

//날씨

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\AbstractCache;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
function __autoload($className) {
    $className = substr($className, strrpos($className, '\\') + 1);
    if (file_exists('src/lib/OpenWeatherMap/'.$className.'.php')) {
        require_once('src/lib/OpenWeatherMap/'.$className.'.php');
        return true;
    }
    if (file_exists('src/lib/OpenWeatherMap/Fetcher/'.$className.'.php')) {
        require_once('src/lib/OpenWeatherMap/Fetcher/'.$className.'.php');
        return true;
    }
    if (file_exists('src/lib/OpenWeatherMap/Util/'.$className.'.php')) {
        require_once('src/lib/OpenWeatherMap/Util/'.$className.'.php');
        return true;
    }
    return false;
}

class WeatherCache extends AbstractCache {
    private function urlToPath($url) {
        //$tmp = sys_get_temp_dir();
        $tmp = "/tmp";
        $dir = $tmp . DIRECTORY_SEPARATOR . "OpenWeatherMapPHPAPI";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $path = $dir . DIRECTORY_SEPARATOR . md5($url);
        return $path;
    }
    public function isCached($url) {
        $path = $this->urlToPath($url);
        if (!file_exists($path) || strlen(file_get_contents($path)) == 0 || filectime($path) + $this->seconds < time()) {
            return false;
        }
        return true;
    }
    public function getCached($url) {
        return file_get_contents($this->urlToPath($url));
    }
    public function setCached($url, $content) {
        file_put_contents($this->urlToPath($url), $content);
    }
}

require_once("src/lib/OpenWeatherMap.php");
$owm = new OpenWeatherMap(null, new WeatherCache(), 120);

function getWeather() {
    global $owm;
    $lang = 'en';
    $units = 'metric';
    try {
        $weather = $owm->getWeather(1833105, $units, $lang, '713e90471b96dbd9c11826031ee66031');
    } catch ( \Exception $e) {
        echo '날씨를 불러올 수 없습니다.';
    }
    return $weather;
}

function getTheme($user) {
    global $april_fools;
    if(!isset($_SESSION['theme']) || $user['n_id'] == 1) {
        if(!file_exists("data/user/theme")) {
            mkdir("data/user/theme");
        }
        $file = "data/user/theme/{$user['n_id']}.txt";
        $items = array('beta', 'voteright', 'nojam', 'pinmenu', 'hidedasan', 'notitlesymbols', 'noanim');
        $on_items = array('pinmenu');
        if(file_exists($file) && ($_SESSION['theme'] = json_decode(file_get_contents($file), true)) != null) {
            foreach($items as $item) {
                if(!array_key_exists($item, $_SESSION['theme'])) {
                    $_SESSION['theme'][$item] = in_array($item, $on_items);
                }
            }
            if(!$april_fools) {
                $_SESSION['theme']['nojam'] = false;
            }
        } else {
            $_SESSION['theme'] = array();
            $_SESSION['theme']['dark'] = false;
            $_SESSION['theme']['square'] = false;
            $_SESSION['theme']['gradients'] = false;
            foreach($items as $item) {
                $_SESSION['theme'][$item] = in_array($item, $on_items);
            }
        }
    }
    return $_SESSION['theme'];
}

function setTheme($theme, $user) {
    $file = "data/user/theme/{$user['n_id']}.txt";
    file_put_contents($file, json_encode($theme));
    $_SESSION['theme'] = $theme;
}

if(getTheme($me)['beta']) {
    $title .= ' beta!';
}
if(getTheme($me)['nojam']) {
    $april_fools = false;
}

function getVotes($id) {
    $tmp = sys_get_temp_dir();
    if(file_exists($tmp.'/v.'.$id)) {
        return file_get_contents($tmp.'/v.'.$id);
    }
    $sum = 0;
    if(file_exists('data/board/votes/up'.$id)) {
        $sum += count(json_decode(file_get_contents('data/board/votes/up'.$id), true));
    }
    if(file_exists('data/board/votes/down'.$id)) {
        $sum -= count(json_decode(file_get_contents('data/board/votes/down'.$id), true));
    }
    return $sum;
}

function upvotes($id) {
    $tmp = sys_get_temp_dir();
    if(file_exists($tmp.'/uv.'.$id)) {
        return file_get_contents($tmp.'/uv.'.$id);
    }
    return file_exists('data/board/votes/up'.$id) ? count(json_decode(file_get_contents('data/board/votes/up'.$id), true)) : 0;
}

function downvotes($id) {
    $tmp = sys_get_temp_dir();
    if(file_exists($tmp.'/dv.'.$id)) {
        return file_get_contents($tmp.'/dv.'.$id);
    }
    return file_exists('data/board/votes/down'.$id) ? count(json_decode(file_get_contents('data/board/votes/down'.$id), true)) : 0;
}

function upvoted($id, $user) {
    return file_exists('data/board/votes/up'.$id) &&
        array_key_exists($user, json_decode(file_get_contents('data/board/votes/up'.$id), true));
}

function downvoted($id, $user) {
    return file_exists('data/board/votes/down'.$id) &&
        array_key_exists($user, json_decode(file_get_contents('data/board/votes/down'.$id), true));
}

function upvote($id, $user, $down = false) {
    $votes = 0;
    if($down && file_exists('data/board/votes/down'.$id)) {
        $downvotes = json_decode(file_get_contents('data/board/votes/down'.$id), true);
        if(array_key_exists($user, $downvotes)) {
            unset($downvotes[$user]);
            file_put_contents('data/board/votes/down'.$id, json_encode($downvotes));
        }
        $dv = count($downvotes);
        $votes -= $dv;
        file_put_contents(sys_get_temp_dir().'/dv.'.$id, $dv);
    }
    if(file_exists('data/board/votes/up'.$id)) {
        $upvotes = json_decode(file_get_contents('data/board/votes/up'.$id), true);
    } else {
        $upvotes = array();
    }
    $upvotes[$user] = 1;
    $uv = count($upvotes);
    $votes += $uv;
    file_put_contents('data/board/votes/up'.$id, json_encode($upvotes));
    file_put_contents(sys_get_temp_dir().'/uv.'.$id, $uv);
    file_put_contents(sys_get_temp_dir().'/v.'.$id, $votes);
}

function downvote($id, $user) {
    $votes = 0;
    if(file_exists('data/board/votes/up'.$id)) {
        $upvotes = json_decode(file_get_contents('data/board/votes/up'.$id), true);
        if(array_key_exists($user, $upvotes)) {
            unset($upvotes[$user]);
            file_put_contents('data/board/votes/up'.$id, json_encode($upvotes));
        }
        $uv = count($upvotes);
        $votes += $uv;
    }
    if(file_exists('data/board/votes/down'.$id)) {
        $downvotes = json_decode(file_get_contents('data/board/votes/down'.$id), true);
    } else {
        $downvotes = array();
    }
    $downvotes[$user] = 0;
    $dv = count($downvotes);
    $votes -= $dv;
    file_put_contents('data/board/votes/down'.$id, json_encode($downvotes));
    file_put_contents(sys_get_temp_dir().'/v.'.$id, $votes);
    file_put_contents(sys_get_temp_dir().'/uv.'.$id, $uv);
    file_put_contents(sys_get_temp_dir().'/dv.'.$id, $dv);
}

function unvote($id, $user, $down = false) {
    $uv = 0;
    if(file_exists('data/board/votes/up'.$id)) {
        $upvotes = json_decode(file_get_contents('data/board/votes/up'.$id), true);
        if(array_key_exists($user, $upvotes)) {
            unset($upvotes[$user]);
            file_put_contents('data/board/votes/up'.$id, json_encode($upvotes));
        }
        $uv = count($upvotes);
        file_put_contents(sys_get_temp_dir().'/uv.'.$id, $uv);
        if(!$down) file_put_contents(sys_get_temp_dir().'/v.'.$id, $uv);
    }
    if($down && file_exists('data/board/votes/down'.$id)) {
        $downvotes = json_decode(file_get_contents('data/board/votes/down'.$id), true);
        if(array_key_exists($user, $downvotes)) {
            unset($downvotes[$user]);
            file_put_contents('data/board/votes/down'.$id, json_encode($downvotes));
        }
        $dv = count($downvotes);
        file_put_contents(sys_get_temp_dir().'/dv.'.$id, $dv);
        file_put_contents(sys_get_temp_dir().'/v.'.$id, $uv - $dv);
    }
}

function upvoters($id) {
	return file_exists('data/board/votes/up'.$id) ? json_decode(file_get_contents('data/board/votes/up'.$id), true) : array();
}

function downvoters($id) {
	return file_exists('data/board/votes/down'.$id) ? json_decode(file_get_contents('data/board/votes/down'.$id), true) : array();
}

function cleanSymbols($str) {
    return trim(preg_replace('/([^a-zA-Z\d\s:])(\\1+)/u', '$1$1', preg_replace('/[\\p{S}]+/u', '', $str)));
}

function formatTitle($title) {
    global $me;
    return htmlspecialchars(getTheme($me)['notitlesymbols'] ? cleanSymbols($title) : $title);
}

function getFoodVotes($y, $m, $d, $t) {
    $fName = "data/food/votes/{$y}.{$m}.{$d}.{$t}-total";
    if(!file_exists($fName)) {
        return 0;
    }
    $dat = json_decode(file_get_contents($fName), true);
    return $dat["sum"] / $dat["count"];
}

function getFoodVoteData($y, $m, $d, $t) {
    $fName = "data/food/votes/{$y}.{$m}.{$d}.{$t}-total";
    if(file_exists($fName)) {
        return json_decode(file_get_contents($fName), true);
    } else {
        return array("sum" => 0, "count" => 0);
    }
}

function getFoodVoteCount($y, $m, $d, $t) {
    $fName = "data/food/votes/{$y}.{$m}.{$d}.{$t}";
	$votecount = array(0, 0, 0, 0, 0, 0);
    if(file_exists($fName)) {
        $data = json_decode(file_get_contents($fName), true);
    } else {
		return $votecount;
    }
	foreach($data as $name => $val) {
		$votecount[$val]++;
	}
	return $votecount;
}

function foodVote($y, $m, $d, $t, $stars, $user) {
    global $me;
    $fName = "data/food/votes/{$y}.{$m}.{$d}.{$t}";
    if(file_exists($fName)) {
        $data = json_decode(file_get_contents($fName), true);
    } else {
        $data = array();
    }
    if(file_exists($fName."-total")) {
        $tdata = json_decode(file_get_contents($fName."-total"), true);
    } else {
        $tdata = array("sum" => 0, "count" => 0);
    }
    $tdata["sum"] += $stars;
    $tdata["count"]++;
    if(array_key_exists($user, $data)) {
        $tdata["sum"] -= $data[$user];
        $tdata["count"]--;
    }
    $data[$user] = $stars;
    file_put_contents($fName."-total", json_encode($tdata));
    file_put_contents($fName, json_encode($data));
}

function getMyFoodVote($y, $m, $d, $t, $user) {
    $fName = "data/food/votes/{$y}.{$m}.{$d}.{$t}";
    if(file_exists($fName)) {
        $data = json_decode(file_get_contents($fName), true);
    } else {
        $data = array();
    }
    if(!array_key_exists($user, $data)) {
        return 0;
    } else {
        return $data[$user];
    }
}

function getLatestCourtPost() {
    global $board;
    $postList = $board->getArticleList(array(67), false, false, 0, 10, "n_id", true, 0, "법정 리스트", true, true, true, false, false, false, true);
    foreach($postList as $post) {
        if($post['n_parent'] || strtotime('next Thursday', $post['n_writedate'] - 60 * 60 * 20) < strtotime('next Thursday', time() - 60 * 60 * 20)) {
            continue;
        }
        return $post;
    }
    return null;
}

function goesToCourt($name, $courtPost) {
    global $board;
	$attaches = $board->getAttachments(false, $courtPost['n_id']);
    foreach($attaches as $file) {
        if(preg_match("/리스트.*\.xls/", $file['s_name'])) {
            $excel = file_get_contents($file['s_path']);
            return mb_strpos($excel, mb_convert_encoding($name, "UTF-16LE"), 0, "8bit") !== false;
        }
    }
    return false;
}

function testGoesToCourt($name, $courtPost) {
    global $board;
    $attaches = $board->getAttachments(false, $courtPost['n_id']);
    foreach($attaches as $file) {
        //echo $file['s_name'];
        if(preg_match("/리스트.*\.xls/", $file['s_name'])) {
            //echo "매치된 건 - {$file['s_name']}";
            $excel = file_get_contents($file['s_path']);
            //echo mb_strpos($excel, mb_convert_encoding($name, "UTF-16LE"), 0, "8bit");
            //echo $excel;
            echo utf8_encode($excel);
            echo iconv('UTF-16LE', 'UTF-8', $excel);
            return mb_strpos($excel, mb_convert_encoding($name, "UTF-16LE"), 0, "8bit") !== false;
        } else {
            //echo "매치되지 않음..";
        }
    }
    return false;
}

function isCourtDasan($courtPost) {
    $pos = strpos($courtPost['s_data'], '소강당');
    return $pos !== false && $pos < 50;
}

function report($post) {
	global $me;
	if($me === null || $me['n_id'] <= 1) { //prevent bogus users
		return;
	}
	$uv = upvotes($post);
	$dv = downvotes($post);
	if(!reportable($uv, $dv)) return;
	$arr = array();
	if(file_exists("data/board/report/{$post}")) {
		$arr = json_decode(file_get_contents("data/board/report/{$post}"), true);
	}
	if(isset($arr['s_name'])) return;
	$arr[$me['s_name']] = time();
	file_put_contents("data/board/report/{$post}", json_encode($arr));
}

function reporters($post) {
	$arr = array();
	if(file_exists("data/board/report/{$post}")) {
		$arr = json_decode(file_get_contents("data/board/report/{$post}"), true);
	} ?>
	<table class='table table-hover'> <?php
		foreach($arr as $n => $t) { ?>
			<tr> <?php echo "<td>{$n}</td><td>".date("Y-m-d H:i:s", $t).'</td>'; ?> </tr>
		<?php } ?>
	</table> <?php
}

function reportNum($post) {
	$arr = array();
	if(file_exists("data/board/report/{$post}")) {
		$arr = json_decode(file_get_contents("data/board/report/{$post}"), true);
	}
	return count($arr);
}

function reportable($upvotes, $downvotes) {
	return ($downvotes * 2 >= $upvotes) && ($downvotes >= 5);
}

$maxUploadFileSize = convertToBytes( ini_get( 'upload_max_filesize' ) );
