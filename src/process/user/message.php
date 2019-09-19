<?php
redirectLoginIfRequired();
$errors=array();
function redirWithBody($failReason){
	?><form method="post" action="/user/message" id="poster">
<?php foreach($_POST as $key=>$val){if(is_array($val)){foreach($val as $val2){$val2=htmlspecialchars($val2);echo "<input type='hidden' name='{$key}[]' value='$val2' />";}}else{$val=htmlspecialchars($val);echo "<input type='hidden' name='$key' value='$val' />";}} ?><input type="hidden" name="error_occured" value="<?php echo htmlspecialchars(json_encode($failReason)) ?>" /><input type="submit" id="submitter" value="Click here if the page doesn't continue" /></form><script type="text/javascript">$('#poster').submit();$('#submitter').css("visibility", "hidden");</script>
<?php
}
if(!isset($_POST['user_to']))
	$errors['__error']="보낼 사용자를 적어도 한 명 이상 지정해야 합니다.";
if(trim($_POST['s_data'])=="")
	$errors["s_data"]="내용을 입력하셔야 합니다.";
//print_r($errors); die();
$except_users=array();
if(count($errors)==0){
	foreach($_POST['user_to'] as $usr){
		if(($nid=$member->sendNote($me['n_id'], $usr,$_POST['s_data']))===false){
			$except_users[]=$usr;
		}else{
			addNotification($me['n_id'], $usr, "message:".$nid, "<b>" . putUserCard($me,0,false) . "</b>님이 메시지를 보냈습니다.","/user/message");
		}
	}
}
if(count($errors)>0){
	if(isAjax()){
		ajaxDie($errors);
	}else
		redirectWith("redirWithBody",$errors);
}else{
	$new_location="/user/message";
	$alert="";
	if(count($except_users)){
		foreach($except_users as $key=>$val){
			$m=$member->getMember($val);
			$except_users[$key]="{$m['s_id']} ({$m['s_name']})";
		}
		$alert=implode(", ",$except_users) . " 유저를 제외한 사용자들에게 ";
	}
	$alert=$alert."메시지를 전송했습니다.";
	if(isAjax()){
		ajaxOk(array(), $new_location, $alert);
	}else{
		redirectAlert($new_location, $alert);
	}
}