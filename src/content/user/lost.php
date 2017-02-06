<?php
isset($_GET['p1']) || $_GET['p1']="";
isset($_GET['p2']) && $mode=$_GET['p2'];

$findwhat=$_GET['p1'];

switch($findwhat){
	case "id":
		$title = "ID 찾기 - $title";
		break;
	case "password":
		$title = "비밀번호 재설정 - $title";
		break;
	default:
		redirectAlert(false,"?");
}
function printContent(){
	global $findwhat, $mode;
	switch($findwhat){
		case "id":
			if(isset($mode)){
				if($mode=="ok"){
					printFindId(true);
				}
			}else{
				printFindId();
			}
			break;
		case "password":
			if(isset($mode)){
				if($mode=="ok"){
					printFindPassword(true);
				}
			}else{
				printFindPassword();
			}
			break;
	}
}
function printFindId($withKey=false){
	global $findwhat, $mode;
	?>
	<form method="post" action="/ajax/user/lost" onsubmit="return saveAjax(this,'ID 찾는 중...',null,function(){refreshCaptcha();});">
		<input type="hidden" name="findwhat" value="id" />
		<div style="text-align:center;width:100%">
			<h1>ID 찾기</h1>
			<div style="width:340px;margin:0 auto;display:block;">
				<table style="width:100%">
					<tr>
						<th style="width:130px;">E-Mail</th>
						<td><input type="text" name="s_email" class="login_input" style="width:100%" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};" /></td>
					</tr>
					<?php if($withKey){ ?>
						<tr>
							<th style="width:130px;">Key</th>
							<td><input type="text" autocomplete="off" name="s_key" class="login_input" style="width:100%" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};" /></td>
						</tr>
					<?php } ?>
					<tr>
						<th></th>
						<td><a onclick="return false"><img id="img_captcha" src="/files/captcha/0.png" onclick="return refreshCaptcha();" /></a></td>
					</tr>
					<tr>
						<th>사람확인</th>
						<td>
							<input type="text" autocomplete="off" name="s_captcha" style="width:100%" />
						</td>
					</tr>
				</table>
				<div style="float:right;text-align:right;"><a href="/user/lost/password">패스워드 찾기</a> <input type="submit" value="찾기" style="width:80px;height:32px;" /></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</form>
	<?php
}
function printFindPassword($withKey=false){
	?>
	<form method="post" action="/ajax/user/lost" onsubmit="return saveAjax(this,'비밀번호 찾는 중...',null,function(){refreshCaptcha();});">
		<input type="hidden" name="findwhat" value="password" />
		<div style="text-align:center;width:100%">
			<h1>비밀번호 재설정</h1>
			<div style="width:340px;margin:0 auto;display:block;">
				<table style="width:100%">
					<tr>
						<th style="width:130px;">E-Mail</th>
						<td><input type="text" name="s_email" class="login_input" style="width:100%" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};" /></td>
					</tr>
					<tr>
						<th style="width:130px;">ID</th>
						<td><input type="text" name="s_id" class="login_input" style="width:100%" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};" /></td>
					</tr>
					<?php if($withKey){ ?>
						<tr>
							<th style="width:130px;">Key</th>
							<td><input type="text" autocomplete="off" name="s_key" class="login_input" style="width:100%" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmdLoginPage').click();}};" /></td>
						</tr>
					<?php } ?>
					<tr>
						<th></th>
						<td><a onclick="return false"><img id="img_captcha" src="/files/captcha/0.png" onclick="return refreshCaptcha();" /></a></td>
					</tr>
					<tr>
						<th>사람확인</th>
						<td>
							<input type="text" autocomplete="off" name="s_captcha" style="width:100%" />
						</td>
					</tr>
				</table>
				<div style="float:right;text-align:right;"><a href="/user/lost/id">ID 찾기</a> <input type="submit" value="진행" style="width:80px;height:32px;" /></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</form>
	<?php
}