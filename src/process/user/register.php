<?php
function redirWithBody($failReason){
	?>
	<form method="post" action="<?php echo htmlspecialchars($_POST['prev_url'])?>" id="poster">
		<?php
		foreach($_POST as $key=>$val){
			if($key=="s_pw" || $key=="s_pw_check") continue;
			$val=htmlspecialchars($val);
			echo "<input type='hidden' name='$key' value='$val' />";
		}
		?>
		<input type="hidden" name="error_occured" value="<?php echo htmlspecialchars(json_encode($failReason)) ?>" />
		<input type="submit" id="submitter" value="Click here if the page doesn't continue" />
	</form>
	<script type="text/javascript">$('#poster').submit();$('#submitter').css("visibility", "hidden");</script>
	<?php
}
function checkEmail( $email ){
	return filter_var( $email, FILTER_VALIDATE_EMAIL )!==false;
}
function validateInputString($str, $minlen=false, $maxlen=false, $form=false){
	if($minlen!==false && strlen($str)<$minlen) return false;
	if($maxlen!==false && strlen($str)>$maxlen) return false;
	return ($form===false) || (@preg_match($form, $str)>0);
}
function validateInputNumber($n, $min, $max){
	if(!is_numeric($n)) return false;
	if($n<$min || $n>$max) return false;
	return true;
}
$failReason=array();
if(!isset($_POST['n_tos_agree']) || $_POST['n_tos_agree']!="yes") $failReason["n_tos_agree"]="약관을 읽고 동의해 주세요.";
if(!validateInputString($_POST['s_id'],3,64,"/^[A-Za-z0-9_\\-]+$/")) $failReason["s_id"]="ID는 2자보다 길어야 하고 65자보다 짧은 영문자 및 숫자의 조합이어야 합니다.";
else if($member->getMember($_POST['s_id'],1)!==false) $failReason["s_id"]="ID가 이미 있습니다.";
if(!validateInputString($_POST['s_pw'],6)) $failReason["s_pw"]="패스워드는 6자보다 길어야 합니다.";
if($_POST['s_pw']!=$_POST['s_pw_check']) $failReason["s_pw_check"]="패스워드가 일치하지 않습니다.";
if(!validateInputString($_POST['s_email'],5,255) || !checkEmail($_POST['s_email'])) $failReason["s_email"]="E-Mail이 잘못되었습니다.";
else if($member->getMember($_POST['s_email'],2)!==false) $failReason["s_id"]="E-Mail이 이미 존재합니다.";
if(!validateInputNumber($_POST['n_wave'],1,intval(date("Y"))-1995)) $failReason["n_wave"]="기수를 확인해 주세요.";
if(!validateInputString($_POST['s_kor_name'],6,30)) $failReason["s_kor_name"]="한글 이름을 확인해 주세요.";
if(!validateInputString($_POST['s_eng_name'],6,128)) $failReason["s_eng_name"]="영어 이름을 확인해 주세요.";
if(!validateInputNumber($_POST['n_birth_date_yr'],1970, intval(date("Y")))) $failReason['n_birth_date_yr']="태어난 년도를 확인해 주세요.";
if(!validateInputNumber($_POST['n_birth_date_month'],1, 12)) $failReason['n_birth_date_month']="태어난 달을 확인해 주세요.";
if(!validateInputNumber($_POST['n_birth_date_day'],1, 31)) $failReason['n_birth_date_day']="태어난 날짜를 확인해 주세요.";
else if(cal_days_in_month(CAL_GREGORIAN, $_POST['n_birth_date_month'], $_POST['n_birth_date_yr'])<$_POST['n_birth_date_day']) $failReason['n_birth_date_day']="생일을 확인해 주세요.";
if(!validateInputNumber($_POST['n_gender'],0,3)) $failReason['n_birth_date_yr']="성별 선택을 확인해 주세요.";
if(!json_decode(httpPost("https://www.google.com/recaptcha/api/siteverify",
            array("secret" => "6LemDhUTAAAAAOeAxTrulB03uH1-TOcFmz5SbxDs",
                  "response" => $_POST['g-recaptcha-response'])), true)['success'])
    $failReason['s_captcha']="사람이 아닙니다! 혹시... 로봇이신가요?";
if(count($failReason)>0){
	if(isAjax()){
		ajaxDie($failReason);
	}else
		redirectWith("redirWithBody",$failReason);
}else{
	if(false!==($mid=$member->addMember($_POST['s_id'], $_POST['s_pw'], $_POST['s_kor_name'], $_POST['s_email'], 0,$_POST['n_wave'], $_POST['s_homepage'], $_POST['s_phone'], "", "", "", $_POST['s_eng_name'], $_POST['n_birth_date_yr'], $_POST['n_birth_date_month'], $_POST['n_birth_date_day'], $_POST['n_gender'], $_POST['s_status_message'], $_POST['s_interest']))){
		$pic=$icon="";
		if(isset($_FILES['s_pic']) && $_FILES['s_pic']['name']!=""){
			$fileinfo = pathinfo($_FILES['s_pic']['name']);$ext = $fileinfo['extension'];
			if($ext!="") $ext=".".$ext;
			$pic = "data/user/picture_full/" . $mid . $ext;
			if(!move_uploaded_file($_FILES['s_pic']['tmp_name'], $pic)){
				$pic="";
			}else{
				$pic_thumb="data/user/picture/" . $mid . ".jpg";
				resizeImage($pic, $pic_thumb, 100, 100);
				$pic=$pic_thumb;
			}
		}
		if(isset($_FILES['s_icon']) && $_FILES['s_icon']['name']!=""){
			$fileinfo = pathinfo($_FILES['s_icon']['name']);$ext = $fileinfo['extension'];
			if($ext!="") $ext=".".$ext;
			$icon = "data/user/icon_full/" . $mid . $ext;
			if(!move_uploaded_file($_FILES['s_icon']['tmp_name'], $icon)){
				$icon="";
			}else{
				$icon_thumb="data/user/icon/" . $mid . ".jpg";
				resizeImage($icon, $icon_thumb, 100, 100);
				$icon=$icon_thumb;
			}
		}
		$member->editMember($mid, false, false, false, false, false, false, false, false, false, $pic, $icon);
		$member->setAdditionalData($mid, "n_student_id", $_POST['n_student_id']);
		$member->setAdditionalData($mid, "s_room", $_POST['s_room']);
		$member->setAdditionalData($mid, "n_grade", $_POST['n_grade']);
		$member->setAdditionalData($mid, "s_class", $_POST['s_class']);
		file_put_contents("data/user_pending_list/$mid.txt", json_encode($_POST));
		if(isAjax()){
			ajaxOk(array(), "user/welcome");
		}else{
			redirectTo("user/welcome");
		}
	}else{
		if(isAjax()){
			ajaxDie(array(), "알 수 없는 오류가 발생하였습니다.");
		}else{
			$failReason['__other']="알 수 없는 오류가 발생하였습니다.";
			redirectWith("redirWithBody",$failReason);
		}
	}
}
?>
