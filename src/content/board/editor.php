<?php
function printEditPage(){
	global $article, $board, $board_cat;
	$posted=isset($_POST["prev_url"]);
	if(!$posted){
		if(checkCategoryAccess($board_cat['n_id'], "attach upload")){
			$attaches=$board->getAttachments(false,$article['n_id']);
			$article['f_uploaded_files']=array();
			foreach($attaches as $v){
				$path="/files/bbs/{$article['n_cat']}/{$article['n_id']}/{$v['n_id']}/{$v['s_key']}/".rawurlencode($v['s_name']);
				$article['f_uploaded_files'][]=$path;
				$article['f_uploaded_files_comment'][]=$v['s_comment'];
			}
		}
	}
	$flag=$article['n_flag'];
	$_POST['b_public_article']=($flag&0x1?"true":"false");
	$_POST['b_no_comment']=($flag&0x2?"true":"false");
	$_POST['b_anonymous']=($flag&0x4?"true":"false");
	$_POST['b_bold_title']=($flag&0x8?"true":"false");
	putEditorForm("/ajax/board/edit", $posted?$_POST:$article, !is_null($article['n_parent']));
}
function printWritePage(){
	putEditorForm("/ajax/board/write", $_POST);
}
function printCommentPage(){
	putEditorForm("/ajax/board/write", $_POST, true);
}
function putEditorForm($form_action, $prev_info, $comment_mode=false){
	global $board_id, $board_act, $board, $board_cat, $board_item, $article, $member, $board, $maxUploadFileSize, $is_mobile;
	if($comment_mode){
		$comment=$article;
		$cat=$board->getCategory($comment['n_cat']);
		$parent_top=$parent=$comment;
		$dp=($_GET['bact']=="edit")?false:$article;
		while($parent_top['n_parent']){
			$parent_top=$board->getArticle($parent_top['n_parent']);
			if($dp==false) $dp=$parent_top;
		}
	}
	$m=$member->getMember($article['n_writer']);
	insertOnLoadScript("putAlertOnLeave();");
	?>
	<form method="post" action="<?php echo $form_action?>" enctype="multipart/form-data" onsubmit="return saveAjax(this,'글 저장 중...','s_data_ckeditor');">
		<input type="hidden" name="prev_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'])?>" />
		<?php if($comment_mode){ ?>
			<input type="hidden" name="n_parent" value="<?php echo $board_item?>" />
			<input type="hidden" name="n_top_parent" value="<?php echo $parent_top['n_id']?>" />
			<input type="hidden" name="s_cat" value="<?php echo $cat['s_id']?>" />
		<?php } ?>
		<?php if(isset($prev_info["n_id"])){ ?><input type="hidden" name="n_id" value="<?php echo $prev_info['n_id']; ?>" /><?php } ?>
		<div style="padding:10px;">
			<h1><?php echo ($comment_mode?"댓글 ":"글").($_GET['bact']=="edit"?"수정":"쓰기") ?></h1>
			<?php if($comment_mode){ 
				$b_anonymous=$article['n_flag']&0x4;?>
				<div style="border:1px solid gray;margin:5px;padding:5px;">
					<div id="article_comment_sub_<?php echo $dp['n_id']?>" class="acomment">
						<div><?php if($b_anonymous){ ?>익명<?php }else{ ?><a href="<?php echo "/user/view/{$m['n_id']}/".htmlspecialchars($m['s_id'])?>"><?php putUserCard($m)?></a><?php } ?></div>
						<div style="margin-top:14px;margin-bottom:6px;"><?php echo filterContent($dp['s_data']); ?></div>
						<div style="font-size:8pt;color:gray;float:left;"><?php echo date("Y-m-d H:i:s", $dp['n_writedate'])?></div>
						<div style="clear:both"></div>
					</div>
				</div>
			<?php } ?>
			<div style="display:block;margin-right:6px;">
				<div style="display:block;margin-right:4px;">
					<?php if(!$is_mobile){ ?>
						<table style="width:100%">
							<tr style="height:42px;">
								<th style="width:80px;"><?php if(!$comment_mode){ ?>게시판<?php } ?></th>
								<td>
									<?php if(!$comment_mode){ ?>
										<div style="float:left">
											<select name="s_cat" class="selectpicker">
												<?php
												$d=$board->getCategoryList();
												foreach($d as $val){
													if($val['n_id']==1) continue;
													if(false===checkCategoryAccess($val['n_id'],"write")) continue;
													?>
													<option value="<?php echo htmlspecialchars($val['s_id'])?>" <?php if($val['s_id']==$board_id) echo "selected='selected'";?>><?php echo htmlspecialchars($val['s_name'])?></option>
													<?php
												}
												?>
											</select>
										</div>
									<?php } ?>
									<div style="float:right;padding-top:3px;width:480px;display:block;text-align:right;">
										<?php if(checkCategoryAccess($board_cat['n_id'], "flag no comment")){ ?>
											<input type="checkbox" id="chk_b_no_comment" name="b_no_comment" value="true" <?php echo (isset($_POST['b_no_comment']) && $_POST['b_no_comment']=="true")?"checked='checked'":"" ?> />
											<label for="chk_b_no_comment">댓글 금지</label>
										<?php } if(!($comment_mode&&$board_cat['n_id']==77)&&checkCategoryAccess($board_cat['n_id'], "flag anonymous")){ ?>
											<input type="checkbox" id="chk_b_anonymous" name="b_anonymous" value="true" <?php echo (isset($_POST['b_anonymous']) && $_POST['b_anonymous']=="true")?"checked='checked'":"" ?> />
											<label for="chk_b_anonymous">익명</label>
										<?php } if(checkCategoryAccess($board_cat['n_id'], "flag bold title")){ ?>
											<input type="checkbox" id="chk_b_bold_title" name="b_bold_title" value="true" <?php echo (isset($_POST['b_bold_title']) && $_POST['b_bold_title']=="true")?"checked='checked'":"" ?> />
											<label for="chk_b_bold_title">제목 굵게</label>
										<?php } ?>
									</div>
								</td>
							</tr>
							<tr style="height:42px;">
								<th>제목</th>
								<td>
									<input type="text" class="form-control" name="s_title" style="width:100%;" <?php echo isset($prev_info['s_title'])?"value='".htmlspecialchars($prev_info['s_title'])."'":""?> />
									<?php if($comment_mode) echo "<br />입력하지 않아도 됩니다."; ?>
								</td>
							</tr>
							<tr style="height:42px;">
								<th>키워드</th>
								<td>
									<input type="text" class="form-control" name="s_tag" style="width:100%;" <?php echo isset($prev_info['s_tag'])?"value='".htmlspecialchars($prev_info['s_tag'])."'":""?> />
									쉼표로 구분합니다.
								</td>
							</tr>
						</table>
					<?php }else{ ?>
						<?php if(!$comment_mode){ ?>
							<div>
								<b>게시판</b>
								<select name="s_cat" class="selectpicker">
									<?php
									$d=$board->getCategoryList();
									foreach($d as $val){
										if($val['n_id']==1) continue;
										if(false===checkCategoryAccess($val['n_id'],"write")) continue;
										?>
										<option value="<?php echo htmlspecialchars($val['s_id'])?>" <?php if($val['s_id']==$board_id) echo "selected='selected'";?>><?php echo htmlspecialchars($val['s_name'])?></option>
										<?php
									}
									?>
								</select>
							</div>
						<?php } ?>
						<div>
							<?php if(checkCategoryAccess($board_cat['n_id'], "flag no comment")){ ?>
								<input type="checkbox" id="chk_b_no_comment" name="b_no_comment" value="true" <?php echo (isset($_POST['b_no_comment']) && $_POST['b_no_comment']=="true")?"checked='checked'":"" ?> />
								<label for="chk_b_no_comment">댓글 금지</label>
							<?php } if(checkCategoryAccess($board_cat['n_id'], "flag anonymous")){ ?>
								<input type="checkbox" id="chk_b_anonymous" name="b_anonymous" value="true" <?php echo (isset($_POST['b_anonymous']) && $_POST['b_anonymous']=="true")?"checked='checked'":"" ?> />
								<label for="chk_b_anonymous">익명</label>
							<?php } if(checkCategoryAccess($board_cat['n_id'], "flag bold title")){ ?>
								<input type="checkbox" id="chk_b_bold_title" name="b_bold_title" value="true" <?php echo (isset($_POST['b_bold_title']) && $_POST['b_bold_title']=="true")?"checked='checked'":"" ?> />
								<label for="chk_b_bold_title">제목 굵게</label>
							<?php } ?>
						</div>
						<div>
							<b>제목</b><br />
							<input class="form-control" type="text" name="s_title" style="width:100%;" <?php echo isset($prev_info['s_title'])?"value='".htmlspecialchars($prev_info['s_title'])."'":""?> />
							<?php if($comment_mode) echo "<br />입력하지 않아도 됩니다."; ?>
						</div>
						<div>
							<b>태그</b> (쉼표로 구분합니다.)<br />
							<input type="text" class="form-control" name="s_tag" style="width:100%;" <?php echo isset($prev_info['s_tags'])?"value='".htmlspecialchars($prev_info['s_tag'])."'":""?> />
						</div>
					<?php } ?>
				</div>
				<div style="margin-bottom:20px;">
					<textarea name="s_data" id="s_data_ckeditor" style="width:100%;height:480px;"><?php echo isset($prev_info['s_data'])?htmlspecialchars($prev_info['s_data']):""?></textarea>
				</div>
			</div>
			<?php if(checkCategoryAccess($board_cat['n_id'], "attach upload")){
				if(!$is_mobile){ ?>
					<div style="display:block;margin-right:4px;">
						<div class="fieldset flash" style="width:100%">
							<span class="legend">업로드</span>
							<?php if($comment_mode){ ?>
								<div style="font-weight:bold;display:block;position:relative;top:-20px;left:15px;margin-bottom:7px;">주: 게시글에 삽입되지 않은 파일은 보이지 않습니다.</div>
							<?php } ?>
							<div style="display:block;position:relative;top:-20px;left:15px;">
								<div>
									<span id="spanButtonPlaceHolder"></span>
									<input id="btnCancel" class="btn btn-default" type="button" value="모든 업로드 취소" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
									파일 최대 크기: <?php echo ini_get( 'upload_max_filesize' ) ?>
								</div>
								<div style="float:left;width:400px;margin-left:-10px;">
									<div class="fieldset flash" id="fsUploadProgress">
										<span class="legend">업로드 상태</span>
										<div style="width:360px;margin:0 auto;margin-top:-24px;display:block;height:24px;line-height:24px;background:#FCFCFC;position:relative;">
											<div style="display:block;width:0;position:absolute;background:#CCF;height:24px;" id="total_upload_progress_bar"></div>
											<div style="display:block;width:100%;position:absolute;text-align:center;" id="total_upload_progress">0%</div>
										</div>
										<div style="clear:both"></div>
									</div>
								</div>
								<div class="fieldset flash" style="width:640px;float:right;margin-right:20px;">
									<input type="hidden" id="uploaded_file_maximum_size" value="<?php echo $maxUploadFileSize ?> B" />
									<input type="hidden" id="uploaded_files_index" value="<?php echo isset($prev_info['uploaded_files_index'])?htmlspecialchars($prev_info['uploaded_files_index']):0?>" name="uploadedFilesIndex" />
									<span class="legend">업로드 목록 (<span id="uploaded_files_count">0</span>)</span>
									<div class="upload-file-list">
										<ul style="display:block;width:100%;" id="fsUploadedFiles"></ul>
										<div style="clear:both"></div>
									</div>
								</div>
								<div style="clear:both"></div>
							</div>
						</div>
					</div>
				<?php }else{ ?>
					모바일에서는 추가 업로드가 불가능합니다.
					<div>
						<input type="hidden" id="uploaded_file_maximum_size" value="<?php echo $maxUploadFileSize ?> B" />
						<input type="hidden" id="uploaded_files_index" value="<?php echo isset($prev_info['uploaded_files_index'])?htmlspecialchars($prev_info['uploaded_files_index']):0?>" name="uploadedFilesIndex" />
						<span class="legend">업로드 목록 (<span id="uploaded_files_count">0</span>)</span>
						<div class="upload-file-list">
							<ul style="display:block;width:100%;" id="fsUploadedFiles"></ul>
							<div style="clear:both"></div>
						</div>
					</div>
				<?php }
			} ?>
		</div>
		<input type="submit" class="btn btn-default" value="<?php echo ($comment_mode?"댓글 ":"글").($_GET['bact']=="edit"?"수정":"쓰기") ?>" style="float:right;width:120px;height:32px;margin-right:7px;" onclick="window.onbeforeunload=null;return true;" />
		<div type="clear:both"></div>
	</form>

    <script src="//cdn.ckeditor.com/4.5.6/full/ckeditor.js"></script>
	<?php
	if(checkCategoryAccess($board_cat['n_id'], "attach upload")){
		if(isset($prev_info['f_uploaded_files'])){
			foreach($prev_info['f_uploaded_files'] as $key=>$val){
				insertOnLoadScript("board_addFileToWriteList('".addslashes($val)."', '".addslashes(urldecode(basename($val)))."', '".addslashes($prev_info['f_uploaded_files_comment'][$key])."', " . ((substr($val,11)=="/data/temp/")?"false":"true") . ");"); 
			}
		}
	}
	insertOnLoadScript("$( '#fsUploadedFiles' ).sortable();");
	insertOnLoadScript("board_prepareSwfUploadBoardWrite(\"".session_id()."\");");
	insertOnLoadScript("CKEDITOR.replace('s_data_ckeditor', {
        language: 'ko',
        font_names : '맑은 고딕;나눔고딕;나눔명조;나눔펜;굴림;바탕;돋움;궁서;Register;Sensation;Arial;Times New Roman;Verdana;Trebuchet MS;'
    });");
}
