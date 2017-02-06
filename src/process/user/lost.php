<?php
if($_POST['findwhat']!="id" && $_POST['findwhat']!="password") die();
function redirWithBody($failReason){
	?>
	<form method="post" action="/user/lost/<?php echo $_POST['findwhat'] ?>" id="poster">
		<?php
		foreach($_POST as $key=>$val){
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
$errors=array();
$captcha=new Soreecaptcha();
//print_r($_POST);
if(!$captcha->checkCaptcha($_POST['s_captcha'])) $errors['s_captcha']="확인용 단어를 올바르게 입력해 주세요!";
if(count($errors)==0){
	$m=$member->getMember($_POST['s_email'],2);
	if($m===false) $errors['s_email']="사용자가 존재하지 않습니다.";
	if($_POST['findwhat']=="password"){
		if($m['s_id']!=$_POST['s_id'])
			$errors['s_id']="사용자가 존재하지 않습니다.";
	}
}
if(count($errors)==0){
	$showActualData=false;
	$captcha->renewCaptcha();
	if(isset($_POST['s_key']))
		$_POST['s_key']=trim($_POST['s_key']);
	if(isset($_POST['s_key']) && $_POST['s_key']==$_SESSION['s_reset_key'] && $_SESSION['s_reset_what']==$_POST['findwhat']){
		if(isset($_SESSION["n_reset_key_expire"]) && $_SESSION["n_reset_key_expire"]>time()){
			$showActualData=true;
			session_start();
			$_SESSION["b_reset_key_valid"]=true;
			$_SESSION["n_reset_key_expire"]=time()+300; //5 min
			$url="/user/lost/{$_POST['findwhat']}/found?usr={$m['n_id']}&s_key=".$_POST['s_key'];
			if($_POST['findwhat']=="id"){
				ajaxOk(array(),"/user/login?returnto=/","ID는 {$m['s_id']}입니다.");
			}else{
				$_SESSION["user"]=$m['n_id'];
				$_SESSION["setting_change_no_pw_needed"]=true;
				ajaxOk(array(),"/user/settings","사용자 설정에서 패스워드를 바꿔 주세요.");
			}
		}
	}
	if(!$showActualData){
		session_start();
		$resetKey=$captcha->genCaptchaString(64, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
		$_SESSION["s_reset_key"]=$resetKey;
		$_SESSION["n_reset_key_expire"]=time()+300; //5 min
		$_SESSION["s_reset_what"]=$_POST['findwhat'];
		session_write_close();
		$url="/user/lost/{$_POST['findwhat']}/ok"; //$resetKey";
		mb_language("uni");
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'Content-Transfer-Encoding: 8bit' . "\r\n";

		mail($_POST['s_email'], "=?UTF-8?B?".base64_encode("kmlaonline 사용자 계정 복구")."?=", "<p>Key는</p><p><blockquote>{$resetKey}</blockquote></p><p>입니다.</p>", $headers);
		
		if(isAjax()){
			ajaxOk(array(), $url, "이메일을 보냈습니다. 다음 페이지에서 이메일로 받은 Key를 입력해 주세요.");
		}else{
			redirectTo($url);
		}
	}
}else{
	if(isAjax()){
		ajaxDie($errors);
	}else{
		redirectWith("redirWithBody",$errors);
	}
}