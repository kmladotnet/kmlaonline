<?php
if(!isset($me)) ajaxDie(array(), lang("user","permission","error","login"));
/*
category - add / edit / delete / truncate
*/
if(isset($_GET['fetch'])){
	switch($_GET['fetch']){
		case 'category':
			switch($_GET['sub']){
				case 'permissions':
					if(false===checkCategoryAccess($_GET['cat'],"manage permission")) continue;
					$perms=array_flip($board->getCategoryActionList());
					foreach($perms as $key=>$val){ $perms[$key]=0; }
					do{
						$tmp=$board->getCategoryPermissionList($_GET['cat']);
						if($tmp!==false) $perms=array_merge($perms,$tmp);
						if($_GET['level']=='all') break;
						$tmp=$board->getLevelPermissionList($_GET['cat'],$_GET['level']);
						if($tmp!==false) $perms=array_merge($perms,$tmp);
						if($_GET['level']!="user") break;
						$tmp=$board->getUserPermissionList($_GET['cat'], $_GET['user']);
						if($tmp!==false) $perms=array_merge($perms,$tmp);
					}while(0);
					if($perms===false)
						ajaxDie(array(), lang("board","category","nonexist"));
					ajaxOk($perms);
					break;
			}
			break;
	}
}else{
	switch($_POST['manage_act']){
		case 'category':
			switch($_POST['cat_act']){
				case 'edit':
					foreach($board->getCategoryList() as $val){
						if(false===checkCategoryAccess($val['n_id'],"manage permission")) continue;
						if($val['n_id']==1) continue;
						if(isset($_POST['cat_edit_'.$val['n_id']])){
							$s_name=$_POST['cat_edit_'.$val['n_id']."_s_name"];
							$s_desc=$_POST['cat_edit_'.$val['n_id']."_s_desc"];
							$n_type=$_POST['cat_edit_'.$val['n_id']."_type"];
							$board->editCategory($val['n_id'], false, $s_name, $s_desc, $n_type);
						}
					}
					ajaxOk(array(), false, lang("generic","tried"));
					break;
				case 'edit_default_permission':
					$cat=$_POST['what'];
					if(false===checkCategoryAccess($cat,"manage permission")){
						ajaxDie(array(), lang("board","category","no manage"));
					}else{
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
					}
					break;
			}
			break;
	}
}