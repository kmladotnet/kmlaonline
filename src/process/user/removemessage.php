<?php
redirectLoginIfRequired();
$errors=array();
if(!isset($_POST['id']))
	$errors['__error']=lang("user","message","id unspecified");
$n=$member->readNote($_POST['id']);
if($n['n_owner']!=$me['n_id'])
	$errors['__error']=lang("user","message","not mine");
if(count($errors)==0){
	if($member->removeNote($n['n_id'])===false)
		$errors['__error']=lang("generic","failed");
}
if(count($errors))
	ajaxDie($errors);
else
	ajaxOk();