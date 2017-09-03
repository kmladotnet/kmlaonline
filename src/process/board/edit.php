<?php
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
if(!isset($_POST['n_id']))
	$errors["n_id"]="글이 지정되지 않았습니다.";
else{
	$article=$board->getArticle($_POST['n_id']);
	if($article==false)
		$errors["n_id"]="없는 글입니다.";
	else if(($cat['n_id'] !== 578) && ($me['n_id'] !== 1576) && ($article["n_writer"]!=$me['n_id']) && !checkCategoryAccess($article['n_cat'], "manage modify"))
		$errors["n_id"]="내 글이 아닙니다!!";
}
if(isset($article) && !$article['n_parent'] && trim($_POST['s_title'])=="")
	$errors["s_title"]="제목을 입력하셔야 합니다.";
if(preg_replace('/\s+/', '', strip_tags($_POST['s_data'],"<img>"))=="")
	$errors["s_data"]="내용을 입력하셔야 합니다.";
$cat=$board->getCategory(false,$_POST['s_cat']);
if($cat===false)
	$errors["s_cat"]="카테고리가 존재하지 않습니다.";
else{
	if(!checkCategoryAccess($cat['n_id'], "edit"))
		$errors["_other"]="글을 수정할 수 없는 게시판입니다.";
	else{
		$attach=array();
		$attach_reference=array();
		$old_attach_=$board->getAttachments(false,$_POST['n_id']);
		$old_attach=array();
		$old_cat=$board->getCategory($article['n_cat']);
		$article_flag=0;
		if(isset($_POST['b_public_article']) && checkCategoryAccess($cat['n_id'], "flag public")) $article_flag|=0x1;
		if(isset($_POST['b_no_comment']) && checkCategoryAccess($cat['n_id'], "flag no comment")) $article_flag|=0x2;
		if(isset($_POST['b_anonymous'])) $article_flag|=0x4;
		if(isset($_POST['b_bold_title']) && checkCategoryAccess($cat['n_id'], "flag bold title")) $article_flag|=0x8;
		foreach($old_attach_ as $v){
			$old_attach["/files/bbs/{$old_cat['n_id']}/{$_POST['n_id']}/{$v['n_id']}/{$v['s_key']}/".rawurlencode($v['s_name'])]=$v;
		}
		$s_data=$_POST['s_data'];
		$uploaded_files_index=0;
		if(checkCategoryAccess($cat['n_id'], "attach upload")){
			if(isset($_POST['f_uploaded_files'])){
				foreach($_POST['f_uploaded_files'] as $iter=>$eachfile){
					if(isset($old_attach[$eachfile])){
						$attach[]=$old_attach[$eachfile]['n_id'];
						$board->editAttachment($old_attach[$eachfile]['n_id'], false, $_POST['f_uploaded_files_comment'][$iter], $uploaded_files_index++);
						unset($old_attach[$eachfile]);
					}else{
						$p=explode("/",$eachfile);
						if(count($p)<=1){
							$errors["__other"]="파일 처리 중 오류가 발생하였습니다.";
							break;
						}
						$file_name=urldecode(basename($eachfile));
						$new_filename=$_POST['f_uploaded_files_origname'][$iter];
						if(($newid=$board->addAttachment("./data/temp/$file_name",null,$new_filename,md5(print_r($me,true)),$_POST['f_uploaded_files_comment'][$iter], $uploaded_files_index++))===false){
							$errors["__other"]="파일 처리 중 오류가 발생하였습니다.";
							break;
						}
						$attach[]=$newid['n_id'];
						$newid['s_original']=$eachfile;
						$attach_reference[]=$newid;
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
			$ret=$board->editArticle($_POST['n_id'], $cat['n_id'], $_POST['s_title'], $s_data, $attach, $tag);
			if($ret===false){$errors["__other"]=$board->last_error;}else{
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
									foreach($member->listMembers(0,0,false,$tmp[2],true,true, false, true) as $usr){
										if($usr['s_name']==$tmp[2] && $usr['n_level']==$tmp[1])
											$triggered[$usr['n_id']]=$usr;
									}
								}
							}
						}
					}
					foreach($triggered as $usr){
						if($article_flag & 0x4) // Anonymous
							$member->addNotice($usr['n_id'], "tagged:article:".$_POST['n_id'], "익명 사용자가 <b>" . htmlspecialchars($_POST['s_title']) . "</b>로 태그했습니다.","article:".$_POST['n_id']);
						else
							$member->addNotice($usr['n_id'], "tagged:article:".$_POST['n_id'], putUserCard($me,0,false) . "님이 글 <b>" . htmlspecialchars($_POST['s_title']) . "</b>로 태그했습니다.","article:".$_POST['n_id']);
					}
				}
				if(checkCategoryAccess($cat['n_id'], "attach upload")){
					foreach($attach_reference as $v){
						if(!isset($v['s_original'])) continue;
						$eachfile=$v['s_original'];
						$path="/files/bbs/{$cat['n_id']}/{$_POST['n_id']}/{$v['n_id']}/{$v['s_key']}/".rawurlencode($v['s_name']);
						$s_data=str_replace($eachfile,$path,$s_data);
					}
				}
				$board->editArticle($_POST['n_id'], false, false, $s_data);
				$board->setArticleFlags($_POST['n_id'], $article_flag);
				foreach($old_attach as $val){
					$board->removeAttachment($val['n_id']);
				}
			}
		}
	}
}
$board->removeAttachmentWithoutParents(time()-86400);
if(count($errors)>0){
	if(isAjax()){
		ajaxDie($errors);
	}else
		redirectWith("redirWithBody",$errors);
}else{
	$new_location="/board/{$cat['s_id']}/view/{$article['n_id']}";
	if(isAjax()){
		ajaxOk(array(), $new_location);
	}else{
		redirectTo($new_location);
	}
}
?>
