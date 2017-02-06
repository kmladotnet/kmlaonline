<?php
redirectLoginIfRequired();
if(isUserPermitted($me['n_id'], "kmlaboard_changer")){
	file_put_contents("data/kmlaboard.txt", $_POST['data']);
	ajaxOk(array(), '/util/kmlaboard');
}else{
	redirectAlert('/util/kmlaboard', "권한이 없습니다.");
}