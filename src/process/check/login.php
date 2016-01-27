<?php
function loginRedirection($a){
	if($a==0){
		?><script type="text/javascript">alert("<?php echo addslashes(lang("generic","unknown error")." ".lang("generic","try later")) ?>");location.href="/";</script><?php
	}else if($a==2){
		?><script type="text/javascript">alert("인증이 아직 되지 않은 사용자입니다.");location.href="/";</script><?php
	}else if($a==1){
		?>
		<form method="post" action="user/login/bad" id="poster">
			<input type="hidden" name="returnto" value="<?php echo ((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!="")?$_REQUEST['returnto']:"/")?>" />
			<input type="submit" id="submitter" value="Click here if the page doesn't continue" />
		</form>
		<script type="text/javascript">$('#poster').submit();$('#submitter').css("visibility", "hidden");</script>
		<?php
	}
}
function generateRandomString($length = 32) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, strlen($characters) - 1)];
	return $randomString;
}
switch($member->authMember($_POST['id'],$_POST['pwd'])){
	case 0: // Okay
		$m=$member->getMember($_POST['id'],1);
/*		if($m['n_level']==21){
			redirectWith("loginRedirection",-1);
			break;
		}*/
		/* if(file_exists("data/user_pending_list/{$m['n_id']}.txt")){
			redirectWith("loginRedirection",2);
			break;
		}*/
		if($m['n_level']==18||$m['n_level']==19){
			redirectWith("loginRedirection",-1);
			break;
		}
		/*if($m['n_id']==1426){
			redirectWith("loginRedirection",-1);	
		}*/
		if(isset($_POST['remember_me'])){
			do{
				$rem=generateRandomString();
				$rempath="data/session/$rem";
			}while(file_exists($rempath));
			file_put_contents($rempath, $m['n_id']);
			setcookie("remember_user", $rem, time()+60*60*24*30, "/", NULL, NULL, true); // 1 month
		}else{
			setcookie("remember_user", "", time()-3600, "/");
		}
		session_start();
		$_SESSION["user"]=$m['n_id'];
		session_write_close();
		redirectTo((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!="")?$_REQUEST['returnto']:"/");
		break;
	case -1: // Something's wrong
		redirectWith("loginRedirection",0);
		break;
	default: // Bad ID or PW
		//redirectTo("user/login/bad");
		redirectWith("loginRedirection",1);
		break;
}?>
