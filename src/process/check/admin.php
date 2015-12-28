<?php
if(!isset($me) || $me['n_admin']==0) ajaxDie(array(), lang("user","permission","error","admin"));
/*
category - add / edit / delete / truncate
*/
if(isset($_GET['fetch'])){
	switch($_GET['fetch']){
		case 'category':
			switch($_GET['sub']){
				case 'permissions':
					$perms=array_flip($board->getCategoryActionList());
					if(isset($_GET['user'])) $usr=$member->getMember($_GET['user']);
					foreach($perms as $key=>$val){ $perms[$key]=0; }
					do{
						$perms=array_merge($perms,$board->getCategoryPermissionList($_GET['cat']));
						if($_GET['level']=='all') break;
						if($_GET['level']!="user"){
							$perms=array_merge($perms,$board->getLevelPermissionList($_GET['cat'],$_GET['level']));
						}else{
							$perms=array_merge($perms,$board->getLevelPermissionList($_GET['cat'],$usr['n_level']));
							$perms=array_merge($perms,$board->getUserPermissionList($_GET['cat'],$_GET['user']));
						}
					}while(0);
					if($perms===false)
						ajaxDie(array(), lang("board","category","nonexist"));
					ajaxOk($perms);
					break;
			}
			break;
		case 'special_permission':
			$sel=$mysqli->real_escape_string($_GET['type']);
			$ret=array();
			$ret['defusers']=$ret['permissioners']="";
			if(false!==$res=$mysqli->query("SELECT * FROM kmlaonline_special_permissions_table WHERE s_type='".$mysqli->real_escape_string($sel)."'")){
				while ($row = $res->fetch_array(MYSQLI_BOTH)){
					$m=$member->getMember($row['n_user']);
					$ret['permissioners'].='<input type="checkbox" name="revoke_permission[]" id="revoke_permission_'.$m['n_id'].'" value="'.$m['n_id'].'" />
					<label for="revoke_permission_'.$m['n_id'].'">'.putUserCard($m,0,false).'</label><br />';
				}
				$usrs=$member->listMembers(0,20);
				foreach($usrs as $usr){
					if($usr['n_id']==1) continue;
					if(isUserPermitted($usr['n_id'], $sel)) continue;
					$ret['defusers'].='<input type="checkbox" id="grant_permission_'.$usr['n_id'].'" name="grant_permission[]" value="'.$usr['n_id'].'" />
					<label for="grant_permission_'.$usr['n_id'].'">'.putUserCard($usr,0,false).'</label><br />';
				}
				$res->close();
				ajaxOk($ret);
			}else{
				ajaxDie(array(), lang("generic","unknown error"));
			}
			break;
	}
}else{
	switch($_POST['admin_act']){
		case 'setadminoverride':
			session_start();
			if(isset($_POST['checked']))
				$_SESSION['admin_override']=true;
			else
				unset($_SESSION['admin_override']);
			session_write_close();
			ajaxOk();
			break;
		case 'category':
			switch($_POST['cat_act']){
				case 'add':
					if(false!==$board->addCategory($_POST['new_cat_id'], $_POST['new_cat_name'], $_POST['new_cat_description'], $_POST['new_cat_type']))
						ajaxOk(array(), "/admin?display=1", lang("board","category","added"));
					else
						ajaxDie(array(), lang("board","category","dupe"));
					break;
				case 'edit':
					foreach($board->getCategoryList() as $val){
						if($val['n_id']==1) continue;
						if(isset($_POST['cat_edit_'.$val['n_id']])){
							$s_id=$val['s_id']==$_POST['cat_edit_'.$val['n_id']."_s_id"]?false:$_POST['cat_edit_'.$val['n_id']."_s_id"];
							$s_name=$_POST['cat_edit_'.$val['n_id']."_s_name"];
							$s_desc=$_POST['cat_edit_'.$val['n_id']."_s_desc"];
							$n_type=$_POST['cat_edit_'.$val['n_id']."_type"];
							$board->editCategory($val['n_id'], $s_id, $s_name, $s_desc, $n_type);
						}
					}
					ajaxOk(array(), "/admin?display=1", lang("generic","tried"));
					break;
				case 'edit_default_permission':
					$cat=$_POST['what'];
					foreach($board->getCategoryActionList() as $action){
						$pname="b_allow_$action";
						if(!isset($_POST[$pname])) $_POST[$pname]=0;
						if($_POST['n_permission_type']=="category"){
							if($_POST['n_level']=='all'){
								$board->setCategoryPermission($cat, $action, $_POST[$pname]);
							}else{
								$board->setLevelPermission($cat, $action, $_POST['n_level'], $_POST[$pname]);
							}
						}else{
							$board->setUserPermission($cat, $action, $_POST['n_user'], $_POST[$pname]);
						}
					}
					ajaxOk(array(), false, lang("generic","tried"));
					break;
				case 'remove':
					if($board->getArticleCount(array($_POST['act_from'])))
						ajaxDie(array(), lang("board","category","child exists")." ".lang("board","category","request clear"));
					if($board->delCategory($_POST['act_from'])===true){
						ajaxOk(array(), "/admin?display=1", lang("generic","succeed"));
					}else
						ajaxDie(array(), lang("generic","failed"));
					break;
				case 'movedata':
					if($board->moveCategoryData($_POST['act_from'],$_POST['act_to'])===true){
						ajaxOk(array(), "/admin?display=1", lang("generic","succeed"));
					}else
						ajaxDie(array(), lang("generic","failed"));
					break;
				case 'truncate':
					if($board->clearCategory($_POST['act_from'])===true){
						ajaxOk(array(), "/admin?display=1", lang("generic","succeed"));
					}else
						ajaxDie(array(), lang("generic","failed"));
					break;
			}
			break;
		case 'special_permission':
			if(isset($_POST['revoke_permission']))
				foreach($_POST['revoke_permission'] as $val){
					if(is_numeric($val))
						permitUser($val, $_POST['permission_type'], 0);
				}
			if(isset($_POST['grant_permission']))
				foreach($_POST['grant_permission'] as $val){
					if(is_numeric($val))
						permitUser($val, $_POST['permission_type'], 1);
				}
			ajaxOk(array(), "/admin?display=2", lang("generic","tried"));
			break;
	}
}