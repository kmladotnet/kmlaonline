<?php
date_default_timezone_set("Asia/Seoul");
include "src/soreeengine/SoreeTools.php"; // DB Manager
include "src/range.php"; // HTTP Range Tools
include "src/zipstream.php"; // ZIP Streaming Tools

$max_level=21; // 현재 21기까지

/********************** START INITIALIZATION SESSION ************************/
if (isset($_POST["_CUSTOM_PHPSESSID"])) { // For Flash Upload Plugin
	if(strlen($_POST["_CUSTOM_PHPSESSID"])<64)
		session_id($_POST["_CUSTOM_PHPSESSID"]);
}
session_start();
$ua_mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4));
$is_mobile = isset($_SESSION['forceMode'])?$_SESSION['forceMode']:$ua_mobile;
if(isset($_GET['force_mobile'])){
	if($ua_mobile) unset($_SESSION['forceMode']);
	else $_SESSION['forceMode']=true;
	$is_mobile = true;
}else if(isset($_GET['force_desktop'])){
	if(!$ua_mobile) unset($_SESSION['forceMode']);
	else $_SESSION['forceMode']=false;
	$is_mobile = false;
}
include "theme/theme.php"; // Theme Definition
include "lang/ko-kr.php"; // Language Definition
$title="KMLA Online"; // Page Title
$bDoInit=false; /* DO NOT INITIALIZE - YOU'VE BEEN WARNED */
require(__DIR__."/db-config.php"); /* LOAD DATABASE - DO NOT REMOVE */

if(isset($_COOKIE['remember_user'])){ // 자동 로그인
	$rem=preg_replace('[^A-Za-z0-9]','',$_COOKIE['remember_user']);
	if(file_exists($rempath="data/session/$rem")){
		$_SESSION['user']=file_get_contents($rempath);
		$me=$member->getMember($_SESSION['user']);
		if($me===false){
			unlink($rempath);
			unset($_SESSION['user']);
			setcookie("remember_user", "", time()-3600, "/");
		}
	}
}
session_write_close();
/********************** END INITIALIZATION SESSION ************************/

if(isset($_SESSION['user'])){
	$me=$member->getMember($_SESSION['user']);
	if($me===false){
		session_destroy();
		session_start();
	}else{
		if($me['n_access_date']+60<=time())
			$member->recordMemberAccess($me['n_id']);
	}
}else{
	$me=$member->getMember(1);
}
if(!function_exists("header_remove")){
	function header_remove($header){ header($header.':'); }
}
$hr=date("G");
$is_morning = $is_afternoon = $is_night = false;
if($hr < 8 || $hr >= 22) $is_morning = true; // 혼정빵은 10시 이후에 끝
elseif($hr >= 8 && $hr < 13) $is_afternoon = true;
else $is_night = true;
$curYear=date("Y"); $curMonth=date("n"); $curDay=date("j");
if($is_morning && date("H") >= 22) {
    $curYear = date("Y", strtotime("+1 day"));
    $curMonth = date("m", strtotime("+1 day"));
    $curDay = date("d", strtotime("+1 day"));
}
function redirectAlert($lnk=false,$alert=false){
	?>
    <!doctype html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Redirecting...</title>
        <base href="/" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script type="text/javascript" src="/js/script.js" charset="utf-8"></script>
    </head>

    <body>
        <script type="text/javascript">
            <?php
	if($alert!==false) echo "alert('".addslashes($alert)."');";
	if($lnk===false){
		echo "history.go(-1);setTimeout(\"location.href='/';\",1000);";
	}else{
		$lnk=addslashes($lnk);
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
                <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
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
                    <script src="//cdn.jsdelivr.net/g/jquery@2.1.4"></script>
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
function permitUser($user, $actName, $access){
	global $mysqli;
	$actName=$mysqli->real_escape_string($actName);
	if(!is_numeric($user)) return false;
	if(!is_numeric($access)) return false;
	$mysqli->query("DELETE FROM kmlaonline_special_permissions_table WHERE n_user=$user AND s_type='$actName'");
	if($access==0) return false;
	return (false!==$res=$mysqli->query("INSERT INTO kmlaonline_special_permissions_table (n_user, s_type, n_permission) VALUES ($user, '$actName', $access)"));
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
function hsvToRgb($iH, $iS, $iV) {
    $dV = clamp($iV, 0, 100) / 100.0;
    $dC = $dV * clamp($iS, 0, 100) / 100.0;
    $dH = clamp($iH, 0, 360) / 60.0;
    $dT = fmod($dH, 2.0);
    $dX = $dC * (1 - abs($dT - 1));
    switch(floor($dH)) {
        case 0:
            $dR = $dC; $dG = $dX; $dB = 0.0; break;
        case 1:
            $dR = $dX; $dG = $dC; $dB = 0.0; break;
        case 2:
            $dR = 0.0; $dG = $dC; $dB = $dX; break;
        case 3:
            $dR = 0.0; $dG = $dX; $dB = $dC; break;
        case 4:
            $dR = $dX; $dG = 0.0; $dB = $dC; break;
        case 5:
            $dR = $dC; $dG = 0.0; $dB = $dX; break;
        default:
            $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
    }
    $dM = $dV - $dC;
    $dR += $dM; $dG += $dM; $dB += $dM;
    $dR *= 255; $dG *= 255; $dB *= 255;
    return round($dR).",".round($dG).",".round($dB);
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
        $tmp = sys_get_temp_dir();
        $dir = $tmp . DIRECTORY_SEPARATOR . "OpenWeatherMapPHPAPI";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $path = $dir . DIRECTORY_SEPARATOR . md5($url);
        return $path;
    }
    public function isCached($url) {
        $path = $this->urlToPath($url);
        if (!file_exists($path) || filectime($path) + $this->seconds < time()) {
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
    return $owm->getWeather('Hoengsong', $units, $lang, '713e90471b96dbd9c11826031ee66031');
}

function getTheme($user) {
    if(!isset($_SESSION['theme'])) {
        if(!file_exists("data/user/theme")) {
            mkdir("data/user/theme");
        }
        $file = "data/user/theme/{$user['n_id']}.txt";
        if(file_exists($file)) {
            $_SESSION['theme'] = json_decode(file_get_contents($file), true);
            if(!array_key_exists('beta', $_SESSION['theme'])) {
                $_SESSION['theme']['beta'] = false;
            }
        } else {
            $_SESSION['theme'] = array();
            $_SESSION['theme']['dark'] = false;
            $_SESSION['theme']['square'] = false;
            $_SESSION['theme']['gradients'] = false;
            $_SESSION['theme']['beta'] = false;
        }
    }
    return $_SESSION['theme'];
}

function setTheme($theme, $user) {
    $file = "data/user/theme/{$user['n_id']}.txt";
    file_put_contents($file, json_encode($theme));
    $_SESSION['theme'] = $theme;
}

function getVotes($id) {
    if(!file_exists('data/board/votes')) {
        mkdir('data/board/votes');
        mkdir('data/boards/votes/up');
        mkdir('data/boards/votes/down');
    }
    $sum = 0;
    if(file_exists('data/boards/votes/up'.$id)) {
        $sum += count(json_decode(file_get_contents('data/boards/votes/up'.$id), true));
    }
    if(file_exists('data/boards/votes/down'.$id)) {
        $sum -= count(json_decode(file_get_contents('data/boards/votes/down'.$id), true));
    }
    return $sum;
}

function upvote($id, $user) {
    if(file_exists('data/boards/votes/down'.$id)) {
        $downvotes = json_decode(file_get_contents('data/boards/votes/down'.$id), true);
        if(array_key_exists($user, $downvotes)) {
            unset($downvotes[$user]);
            file_put_contents('data/boards/votes/down'.$id, $downvotes);
        }
    }
    if(file_exists('data/boards/votes/up'.$id)) {
        $upvotes = json_decode(file_get_contents('data/boards/votes/up'.$id), true);
    } else {
        $upvotes = array();
    }
    $upvotes[$user] = null;
    file_put_contents('data/boards/votes/up'.$id, $upvotes);
}

function downvote($id, $user) {
    if(file_exists('data/boards/votes/up'.$id)) {
        $upvotes = json_decode(file_get_contents('data/boards/votes/up'.$id), true);
        if(array_key_exists($user, $upvotes)) {
            unset($upvotes[$user]);
            file_put_contents('data/boards/votes/up'.$id, $upvotes);
        }
    }
    if(file_exists('data/boards/votes/down'.$id)) {
        $downvotes = json_decode(file_get_contents('data/boards/votes/down'.$id), true);
    } else {
        $downvotes = array();
    }
    $downvotes[$user] = $id;
    file_put_contents('data/boards/votes/down'.$id, $downvotes);
}

$maxUploadFileSize = convertToBytes( ini_get( 'upload_max_filesize' ) );
