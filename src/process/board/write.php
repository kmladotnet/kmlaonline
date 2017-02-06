<?php
set_time_limit(0);
redirectLoginIfRequired();
$errors=array();
function redirWithBody($failReason){
	?>
	<form method="post" action="<?php echo htmlspecialchars($_POST['prev_url'])?>" id="poster">
		<?php
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				foreach($val as $val2){
					$val2=htmlspecialchars($val2);
					echo "<input type='hidden' name='{$key}[]' value='$val2' />";
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
if(preg_replace('/\s+/', '', strip_tags($_POST['s_data'],"<img>"))=="")
	$errors["s_data"]=lang("board","article","data required");
$cat=false;
if(isset($_POST['s_cat']))$cat=$board->getCategory(false,$_POST['s_cat']);
if($cat===false){
	$errors["s_cat"]=lang("board","category","nonexist");
}else{
	if(!checkCategoryAccess($cat['n_id'], "write") && !isset($_POST['n_parent'])) //Comment X, WRITE X -> X
		$errors["_other"]=lang("board","article","write unpermitted");
	else{
		if(!isset($_POST['n_parent'])){
			if((!isset($_POST['s_title']) || trim($_POST['s_title'])==""))
				$errors["s_title"]=lang("board","article","title required");
		}else{
			if(($parent_article=$board->getArticle($_POST['n_parent']))===false)
				$errors['n_parent']=lang("board","article","parent removed");
			else{
				$parent_writer=$member->getMember($parent_article['n_writer']);
				if(!doesAdminBypassEverythingAndIsAdmin(!($parent_article['n_flag'] & 0x2) || !$board->isUserAllowed($parent_article['n_cat'], $parent_writer, "flag no comment")))
					$errors['n_parent']=lang("board","article","child unpermitted");
			}
			if(!isset($_POST['s_title']) || trim($_POST['s_title'])==""){
				if(mb_strlen($_POST['s_data'])>48)
					$_POST['s_title']=mb_substr($_POST['s_data'],0,45) . "...";
				else
					$_POST['s_title']=$_POST['s_data'];
			}
		}
		$article_flag=0;
		if(isset($_POST['b_public_article'])) $article_flag|=0x1;
		if(isset($_POST['b_no_comment'])) $article_flag|=0x2;
		if(isset($_POST['b_anonymous'])) $article_flag|=0x4;
		if(isset($_POST['b_bold_title'])) $article_flag|=0x8;
		// 만우절
		//$ra = rand(1,5);
		//if($ra==1)	$article_flag|=0x4;
		//만우절 끝
		$attach=array();
		$attach_reference=array();
		$s_data=$_POST['s_data'];
		$uploaded_files_index=0;
		if(checkCategoryAccess($cat['n_id'], "attach upload")){
			if(isset($_POST['f_uploaded_files'])){
				foreach($_POST['f_uploaded_files'] as $iter=>$eachfile){
					$file_name=urldecode(basename($eachfile));
					$new_filename=$_POST['f_uploaded_files_origname'][$iter];
					if(($newid=$board->addAttachment("./data/temp/$file_name",null,$new_filename,"",$_POST['f_uploaded_files_comment'][$iter], $uploaded_files_index++))===false){
						$errors["__other"]=lang("file","process error");
						break;
					}
					$attach[]=$newid['n_id'];
					$newid['s_original']=$eachfile;
					$attach_reference[]=$newid;
				}
			}
		}
	}
}
if(count($errors)==0){
	if(isset($_POST['s_tag']) && trim($_POST['s_tag'])!=""){
		$tags=explode(",",$_POST['s_tag']);
		for($i=0;$i<count($tags);$i++) $tags[$i]=trim($tags[$i]);
		$tag=implode(",",$tags);
	}else{
		$tag="";
	}
	$article_id=$board->addArticle($cat['n_id'], $_POST['s_title'], $s_data,($article_flag & 0x4) ? '익명' : $me['s_name'], $me['n_id'], isset($parent_article)?$parent_article['n_id']:0, 0, $attach, $tag);
	if($article_id===false){
		$errors["__other"]=$board->last_error;
	}else{
		if(preg_match_all('/@(((찾기)\\{[^}]*\\}|[^\\s`~!@#$%^&*\\(\\)\\-=_+\\[\\]\\\\{}\\|;\':\",.\\/\\<\\>\\?]*))/i',strip_tags($s_data),$tagged)>0){
			$triggered=array();
			foreach($tagged[1] as $val){
				//@찾기{어쩌고_저쩌고}
				//@N기
				//@N이름
				if(substr($val,0,6)=="찾기"){
					$search=substr($val,7); $search=substr($search,0,strlen($search)-1); $search=html_entity_decode($search);
					$triggered=array_merge($triggered,$member->listMembers(0,0,false, $search, false,false,true,true,true,true,true,true,true,true));
				}else{
					if(preg_match("/([0-9]+)(.*)/i",$val,$tmp)>0){
						if($tmp[2]=="기"){
							$level=$tmp[1];
							foreach($member->listMembers(0,0,$level) as $usr){
								$triggered[$usr['n_id']]=$usr;
							}
						}else{
							foreach($member->listMembers(0,0,false, $tmp[2],true,true, false, true) as $usr){
								if($usr['s_name']==$tmp[2] && $usr['n_level']==$tmp[1])
									$triggered[$usr['n_id']]=$usr;
							}
						}
					}
				}
			}
			foreach($triggered as $usr){
				if($article_flag & 0x4){	 // Anonymous
					$tagby=lang("terms","anonymous");
					$member->addNotice($usr['n_id'], "tagged:article:$article_id", "익명 사용자가 {$_POST['s_title']}으로 태그하였습니다.","article:".$article_id);
				}else{
					$member->addNotice($usr['n_id'], "tagged:article:$article_id", "{$me['s_name']}님이 {$_POST['s_title']}으로 태그하였습니다.","article:".$article_id);
				}
			}
		}
		if(isset($_POST['n_parent'])){
			// Got Reply
			if($article_flag & 0x4) { // Anonymous
				$tagby=lang("terms","anonymous");
				$member->addNotice($parent_writer['n_id'], "reply:article:$article_id:from:".$me['n_id'], "익명 사용자가 <b>".htmlspecialchars($parent_article['s_title'])."</b>에 답변을 달았습니다.","/board/{$cat['s_id']}/view/$article_id");
				}
			else{
				$member->addNotice($parent_writer['n_id'], "reply:article:$article_id:from:".$me['n_id'], putUserCard($me,0,false)."님이 <b>".htmlspecialchars($parent_article['s_title'])."</b>에 답변을 달았습니다.","/board/{$cat['s_id']}/view/$article_id");
			}
		}
		if(checkCategoryAccess($cat['n_id'], "attach upload")){
			foreach($attach_reference as $v){
				$eachfile=$v['s_original'];
				$path="/files/bbs/{$cat['n_id']}/{$article_id}/{$v['n_id']}/{$v['s_key']}/".rawurlencode($v['s_name']);
				$s_data=str_replace($eachfile,$path,$s_data);
			}
		}
		if(isset($_POST['b_auto_html']))
			$s_data=nl2br(htmlspecialchars($s_data));
		$board->editArticle($article_id, false, false, $s_data);
		$board->setArticleFlags($article_id, $article_flag);
	}
}
$board->removeAttachmentWithoutParents(time()-86400);
if(count($errors)>0){
	if(isAjax()){
		ajaxDie($errors);
	}else
		redirectWith("redirWithBody",$errors);
}else{
	$new_location="/board/{$cat['s_id']}/view/$article_id";
	if(isAjax()){
		ajaxOk(array(), $new_location);
	}else{
		redirectTo($new_location);
	}
}
