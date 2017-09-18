<?php
function redirWithBody($failReason){
	?>
	<form method="post" action="/user/settings" id="poster">
		<?php
		foreach($_POST as $key=>$val){
			if($key=="s_pw" || $key=="s_pw_check" || $key=="s_captcha") continue;
			if(is_array($val)){
				foreach($val as $val2){
					if(!is_array($val2)){
						$val2=htmlspecialchars($val2);
						echo "<input type='hidden' name='{$key}[]' value='$val2' />";
					}
				}
			}else{
				$val=htmlspecialchars($val);
				echo "<input type='hidden' name='$key' value='$val' />";
			}
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

function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
	// do this or they'll all go to jpeg
	$image_type=$this->image_type;

	if ($image_type == IMAGETYPE_JPEG) {
    	imagejpeg($this->image, $filename,$compression);
  	} elseif ($image_type == IMAGETYPE_GIF) {
     	imagegif($this->image, $filename);
  	} elseif ($image_type == IMAGETYPE_PNG) {
        // need this for transparent png to work
    	imagesavealpha($this->image,true);
    	imagepng($this->image, $filename);
  	}
  	if ($permissions != null) {
    	chmod($filename, $permissions);
  	}
}

function resize($width,$height,$forcesize='n') {

  	/* optional. if file is smaller, do not resize. */
  	if ($forcesize == 'n') {
      	if ($width > $this->getWidth() && $height > $this->getHeight()){
          	return;
      	}
  	}

  	$new_image = imagecreatetruecolor($width, $height);
  	/* Check if this image is PNG or GIF, then set if Transparent*/
  	if(($this->image_type == IMAGETYPE_GIF) || ($this->image_type==IMAGETYPE_PNG)){
      	imagealphablending($new_image, false);
      	imagesavealpha($new_image,true);
  	}
  	imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());

  	$this->image = $new_image;
}

$failReason=array();
if(!isset($_SESSION["setting_change_no_pw_needed"]) && $member->authMember($me['s_id'], $_POST['s_pw_prev'])!=0)
	$failReason["s_pw_prev"]="이전 패스워드가 잘못되었습니다.";
if(!validateInputString($_POST['s_id'],3,64,"/^[A-Za-z0-9_\\-]+$/")) $failReason["s_id"]="The length of ID must be longer than 2 chars and shorter than 65 chars.";
else if(strtolower($_POST['s_id'])!=strtolower($me['s_id']) && $member->getMember($_POST['s_id'],1)!==false) $failReason["s_id"]="ID already exists.";
if($_POST['s_pw']!="" || isset($_SESSION["setting_change_no_pw_needed"])){
	if(!validateInputString($_POST['s_pw'],4)) $failReason["s_pw"]="패스워드는 3자보다 길어야 합니다.";
	if($_POST['s_pw']!=$_POST['s_pw_check']) $failReason["s_pw_check"]="패스워드가 일치하지 않습니다.";
}
if(!validateInputString($_POST['s_email'],5,255) || !checkEmail($_POST['s_email'])) $failReason["s_email"]="E-Mail이 잘못되었습니다.";
else if($me['s_email']!=$_POST['s_email'] && $member->getMember($_POST['s_email'],2)!==false) $failReason["s_id"]="E-Mail이 이미 존재합니다.";
if(!validateInputString($_POST['s_kor_name'],6,30)) $failReason["s_kor_name"]="한글 이름을 확인해 주세요.";
if(!validateInputString($_POST['s_eng_name'],6,128)) $failReason["s_eng_name"]="영어 이름을 확인해 주세요.";
if(!validateInputNumber($_POST['n_birth_date_yr'],1970, intval(date("Y")))) $failReason['n_birth_date_yr']="태어난 년도를 확인해 주세요.";
if(!validateInputNumber($_POST['n_birth_date_month'],1, 12)) $failReason['n_birth_date_month']="태어난 달을 확인해 주세요.";
if(!validateInputNumber($_POST['n_birth_date_day'],1, 31)) $failReason['n_birth_date_day']="태어난 날짜를 확인해 주세요.";
else if(cal_days_in_month(CAL_GREGORIAN, $_POST['n_birth_date_month'], $_POST['n_birth_date_yr'])<$_POST['n_birth_date_day']) $failReason['n_birth_date_day']="생일을 확인해 주세요.";

if(count($failReason)>0){
	if(isAjax()){
		ajaxDie($failReason);
	}else
		redirectWith("redirWithBody",$failReason);
}else{
	/*
	addMember(					$id,					$pw,				$name,				$email,				$point=0,		$level=0,		$homepage="",		$phone="", $selfintro="", $pic="", $icon="", $s_real_name="", $n_birth_date_yr=0, $n_birth_date_month=0, $n_birth_date_day=0, $n_gender=0, $s_status_message="", $s_interest=""){
	editMember($member, 	$new_id=false,	$pw=false,	$name=false,	$email=false,		$point=false,	$level=false,	$homepage=false,	$phone=false, $selfintro=false, $pic=false, $icon=false, $s_real_name=false, $n_birth_date_yr=false, $n_birth_date_month=false, $n_birth_date_day=false, $n_gender=false, $s_status_message=false, $s_interest=false){
	*/
	if(false!==($member->editMember($me['n_id'], $_POST['s_id'], ($_POST['s_pw']=="")?false:$_POST['s_pw'], $_POST['s_kor_name'], $_POST['s_email'], 0, false, $_POST['s_homepage'], $_POST['s_phone'], false, false, false, $_POST['s_eng_name'], $_POST['n_birth_date_yr'], $_POST['n_birth_date_month'], $_POST['n_birth_date_day'], false, false, false))){
		$pic=$icon=false;

		if(isset($_FILES['s_pic']) && $_FILES['s_pic']['name']!=""){
			$pic = "data/user/picture_full/" . $me['n_id'] . ".png";
			@unlink($me['s_pic']);
			if(!move_uploaded_file($_FILES['s_pic']['tmp_name'], $pic)){
				$pic="";
			}else{
				$pic_thumb="data/user/picture/" . $me['n_id'] . ".png";
				resizeImage($pic, $pic_thumb, 90, 90);
			//	$pic_thumb=($_FILES['s_pic'].resize(90,90));
				$pic=$pic_thumb;
			}
		}else if(isset($_POST['b_remove_pic']) && $_POST['b_remove_pic']=="yes"){
			@unlink($me['s_pic']);
			@unlink(str_replace("picture/","picture_full/",$me['s_pic']));
			$pic="";
		}
		if(isset($_FILES['s_icon']) && $_FILES['s_icon']['name']!=""){
			$icon = "data/user/icon_full/" . $me['n_id'] . ".png";
			@unlink($me['s_icon']);
			if(!move_uploaded_file($_FILES['s_icon']['tmp_name'], $icon)){
				$icon="";
			}else{
				$icon_thumb="data/user/icon/" . $me['n_id'] . ".png";
				resizeImage($icon, $icon_thumb, 32, 32);
				$icon=$icon_thumb;
			}
		}else if(isset($_POST['b_remove_icon']) && $_POST['b_remove_icon']=="yes"){
			@unlink($me['s_icon']);
			@unlink(str_replace("icon/","icon_full/",$me['s_icon']));
			$icon="";
		}
		$member->editMember($me['n_id'], false, false, false, false, false, false, false, false, false, $pic, $icon);
		//$member->setAdditionalData($me['n_id'], "s_room", $_POST['s_room']);
		//$member->setAdditionalData($me['n_id'], "n_grade", $_POST['n_grade']);
		//$member->setAdditionalData($me['n_id'], "s_class", $_POST['s_class']);
		//$member->setAdditionalData($me['n_id'], "n_student_id", $_POST['n_student_id']);
		$menu_data_out=array();
        if(isset($_POST['menu_data']))
			$menu_data=$_POST['menu_data'];
		if(isset($menu_data)){
			$menu_title=$_POST['menu_titles'];
			for($i=0;$i<count($menu_title);$i++){
				if($menu_data[$i]=="divider"){
					if(isset($curr)){
						$menu_data_out[$curr['title']]=$curr;
						unset($menu_data_out[$curr['title']]['title']);
					}
					$curr=array('title'=>$menu_title[$i]);
				}else{
					$act=$menu_data[$i];
					$act2=substr($act,strpos($act,":")+1);
					$act=substr($act,0,strpos($act,":"));
					$curr[]=array($act,$act2,$menu_title[$i]);
				}
			}
		}
		if(isset($curr)){
			$menu_data_out[$curr['title']]=$curr;
			unset($menu_data_out[$curr['title']]['title']);
		}
		file_put_contents("data/user/menu_bar/{$me['n_id']}.txt",serialize($menu_data_out));
		session_start();
		unset($_SESSION["setting_change_no_pw_needed"]);
		session_write_close();
		if(isAjax()){
			ajaxOk(array(), "/user/settings", "사용자 정보를 변경하였습니다.");
		}else{
			redirectAlert("/user/settings", "사용자 정보를 변경하였습니다.");
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
