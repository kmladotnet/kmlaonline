<?php
function loginRedirection($a){
	if($a==0){
		?><script type="text/javascript">alert("<?php echo addslashes(lang("generic","unknown error")." ".lang("generic","try later")) ?>");location.href="/";</script><?php
	}else if($a==2){
		?><script type="text/javascript">alert("가입 승인 절차가 진행 중입니다. 관리자 승인 이후 로그인이 가능하오니 양해 부탁드립니다.");location.href="/";</script><?php
	}else if($a==1){
		?>
		<form method="post" action="user/login/bad" id="poster">
			<input type="hidden" name="returnto" value="<?php echo ((isset($_REQUEST['returnto']) && $_REQUEST['returnto']!="")?$_REQUEST['returnto']:"/")?>" />
			<input type="submit" id="submitter" value="Click here if the page doesn't continue" />
		</form>
		<script type="text/javascript">$('#poster').submit();$('#submitter').css("visibility", "hidden");</script>
		<?php
	}else if($a==4){
		?>
		<script type="text/javascript">alert("큼온이 오늘은 좀 쉬고 싶다고 하네요 ㅇㅅㅇ 한 번 더 눌러보면 들어갈 수도?");location.href="/";</script>
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
		}
/*		if($m['n_level']==21){
			redirectWith("loginRedirection",-1);
			break;
		}*/
		if(file_exists("data/user_pending_list/{$m['n_id']}.txt")){
			redirectWith("loginRedirection", 2);
			break;
		}
		/*if($m['n_id']==1426){
			redirectWith("loginRedirection",-1);
		}*/


		if(mt_rand(1, 2) == 1){
			redirectWith("loginRedirection", 4);
			break;
		}


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
