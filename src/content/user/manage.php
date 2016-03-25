<?php
redirectLoginIfRequired();
$title="Manage - " . $title;
function printContent(){
	/*
	Manage Users in selected boards
	Manage Board Categories
	Manage Articles
	*/
	global $board, $member;
	?>
	<div class="tab_global_wrap">
		<div class="what_to_do">
		</div>
		<div class="tab_menu">
			<ul>
				<li><a id="tab_menu_switch_0" href="#" onclick="return changeTab(0);"<?php if(!isset($_GET['display']) || $_GET['display']==0) echo ' class="tab_menu_selected"';?>>게시판 접근</a></li>
				<!--
				<li><a id="tab_menu_switch_1" href="#" onclick="return changeTab(1);"<?php if(isset($_GET['display']) && $_GET['display']==1) echo ' class="tab_menu_selected"';?>>동아리 활동</a></li>
				<li><a id="tab_menu_switch_2" href="#" onclick="return changeTab(2);"<?php if(isset($_GET['display']) && $_GET['display']==2) echo ' class="tab_menu_selected"';?>>MPT 활동</a></li>
				<li><a id="tab_menu_switch_3" href="#" onclick="return changeTab(3);"<?php if(isset($_GET['display']) && $_GET['display']==3) echo ' class="tab_menu_selected"';?>>부서 활동</a></li>
				-->
			</ul>
		</div>
		<div class="tab_wrap">
			<div class="tab_menu_content">
				<div id="tab_menu_0" class="tab_menu_items"<?php if(!isset($_GET['display']) || $_GET['display']==0) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<?php 
						$i=0; $cpp=5; $catList=array();
						foreach($board->getCategoryList() as $val){
							if(false===checkCategoryAccess($val['n_id'],"manage permission")) continue;
							$catList[]=$val;
						}
						$cntperpage=count($catList)-1; $cntperpage=($cntperpage-($cntperpage%$cpp))/$cpp;
						if(count($catList)==0){ ?>
							<tr><th></th><td><div style="width:100%;text-align:center;padding:15px;font-size:18pt;">관리할 수 있는 게시판이 없습니다.</div></td></tr>
						<?php }else{ ?>
							<tr>
								<th>수정</th>
								<td>
									<form method="post" action="/check" onsubmit="return saveAjax(this,'카테고리 작업 중...');">
										<input type="hidden" name="action" value="manage" />
										<input type="hidden" name="manage_act" value="category" />
										<input type="hidden" name="cat_act" value="edit" />
										<div style="display:block;float:right">
											ID로 찾기: <input onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {return findCategoryPage($('#txt_cat_id').val(), <?php echo $cntperpage?>)}};" type="text" id="txt_cat_id" style="width:80px;" /><input type="button" onclick="return user_manage_findCategoryPage($('#txt_cat_id').val(), <?php echo $cntperpage?>)" value="찾기" style="width:48px;" />
										</div>
										<div style='padding:5px;display:block;float:left;'>
											<?php
											for($j=0;$j<=$cntperpage;$j++){
												echo "<span style='padding:5px;'><a onclick='user_manage_changeCategoryPage($j, $cntperpage);'>[".($j+1)."]</a></span> ";
											}
											?>
										</div>
										<div style='clear:both'></div>
										<?php
										foreach($catList as $val){
											foreach($val as $key=>$a){ $val[$key]=htmlspecialchars($a); }
											if($i%$cpp==0){
												?>
												<div id="class_group_<?php echo $i/$cpp?>" style="overflow:hidden;<?php if($i!=0) echo "display:none;"; ?>">
													<table style="width:100%;" class="table_info_view">
														<tr><th style="width:24px;text-align:left;">수정</th><th style="width:24px;text-align:left;">번호</th><th style="width:80px;text-align:left;">카테고리 ID</th><th style="text-align:left;">카테고리 이름</th><th style="width:48px;text-align:left;">글 수</th></tr>
												<?php
											}
											if($val['n_id']==1){
												echo "<tr><td></td><td>&nbsp;1&nbsp;</td><td></td><td>분류되지 않음</td><td>{$val['n_count']}</td></tr>";
											}else{
												?>
												<tr>
													<td><input type='hidden' id='searchby_<?php echo $val['s_id']?>' value='<?php echo ($i-($i%$cpp))/$cpp?>' /><input type='checkbox' name='cat_edit_<?php echo $val['n_id']?>' value='yes' onchange="smoothToggleVisibility('.cat_edit_form_<?php echo $val['n_id']?>',$(this).is(':checked')?2:1);" /></td>
													<td><a onclick="return user_manage_prepareCategoryPermission(<?php echo $val['n_id']?>, '<?php echo addslashes($val['s_name'])?>');">[<?php echo $val['n_id']?>]</a></td>
													<td><?php echo $val['s_id']?></td>
													<td><input type='text' style='width:98%' name='cat_edit_<?php echo $val['n_id']?>_s_name' value='<?php echo $val['s_name']?>' /></td>
													<td><?php echo $val['n_count']?></td>
												</tr>
												<tr class="cat_edit_form_<?php echo $val['n_id']?>" style="display:none">
													<td></td>
													<td></td>
													<td colspan='3'>
														<div style="float:left">설명</div>
														<select name="cat_edit_<?php echo $val['n_id']?>_type" style="width:70px;float:right">
															<option value="0" <?php echo($val['n_viewmode']==0)?"selected='selected'":""?>>게시판</option>
															<option value="1" <?php echo($val['n_viewmode']==1)?"selected='selected'":""?>>갤러리</option>
															<option value="2" <?php echo($val['n_viewmode']==2)?"selected='selected'":""?>>포럼</option>
														</select>
														<div style="clear:both"></div>
														<textarea style="width:100%;height:120px;" name="cat_edit_<?php echo $val['n_id']?>_s_desc"><?php echo htmlspecialchars($val['s_desc'])?></textarea>
													</td>
												</tr>
												<tr class="cat_edit_form_<?php echo $val['n_id']?>" style="height:16px;display:none"><td colspan='5'></td></tr>
												<?php
											}
											if($i%$cpp==$cpp-1) echo "</table></div>";
											$i++;
										}
										if($i%$cpp!=0) echo "</table></div>";
										?>
										<input type="submit" class="btn btn-default" value="진행" style="width:96px;height:32px;" />
									</form>
								</td>
							</tr>
							<tr>
								<th>카테고리 작업</th>
								<td>
									먼저 위에서 권한을 수정할 카테고리 번호를 선택해 주세요.
									<div style="display:none" id="categoryPermissionChangeDiv">
										<div id="cat_perm_what_desc" style="padding:5px;font-weight:bold;font-size:12pt;float:left"></div>
										<div style="clear:both"></div>
										<form method="post" action="/check" onsubmit="return saveAjax(this,'권한 작업 중...');">
											<input type="hidden" name="action" value="manage" />
											<input type="hidden" name="manage_act" value="category" />
											<input type="hidden" name="cat_act" value="edit_default_permission" />
											<input type="hidden" name="what" id="cat_perm_what" value="" />
											<div style="float:left;display:block;width:180px;">
												<div style="padding:5px;font-weight:bold;font-size:11pt;">기수 선택</div>	
												<div style="padding:3px;">
													<input type="radio" onclick="user_manage_fetchCategoryPermission();$('#n_level_select').removeAttr('disabled');$('#n_user_select').attr('disabled','disabled');" name="n_permission_type" value="category" id="n_permission_type_category" checked="checked" /><label for="n_permission_type_category"> 분류 전체 설정</label><br />
													<select name="n_level" id="n_level_select" onchange="user_manage_fetchCategoryPermission();">
														<option value="all" selected="selected">기본값/외부인</option>
														<?php for($i=intval(date("Y"))-1995,$j=0;$i>=1;$i--,$j++){ ?>
															<option value="<?php echo $i?>"><?php echo $i?></option>
														<?php } ?>
													</select>
												</div>
												<div style="padding:3px;">
													<input type="radio" onclick="user_manage_fetchUserPermission();$('#n_user_select').removeAttr('disabled');$('#n_level_select').attr('disabled','disabled');" name="n_permission_type" value="user" id="n_permission_type_user" /><label for="n_permission_type_user"> 사용자별 설정</label><br />
													<select name="n_user" id="n_user_select" onchange="user_manage_fetchUserPermission();" disabled="disabled">
														<?php
														$usrs=$member->listMembers(0,20);
														foreach($usrs as $usr){
															if($usr['n_id']==1) continue;
															?>
															<option value="<?php echo $usr['n_id']?>"><?php echo $usr['n_level'] . " " . $usr['s_name'] . " (" . $usr['s_id'] . ")"; ?></option>
															<?php
														}
														?>
													</select>
													<input type="hidden" id="n_user_find_page" value="0" />
													<input type="text" style="width:160px;" id="s_user_to_find" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmd_search_for_user').click();return false;}};" />
													<input type="button" id="cmd_search_for_user" value="찾아보기" onclick="user_manage_searchForUsers(true);" />
													<input type="button" id="cmd_search_more" value="계속 찾기" onclick="user_manage_searchForUsers(false);" />
												</div>
											</div>
											<div style="margin-left:180px;display:none;" id="categoryPermissionSelectDiv">
												<div style="padding:5px;font-weight:bold;font-size:11pt;">권한 선택</div>
												<?php
												$disp=$board->getCategoryActionList(true);
												$real=$board->getCategoryActionList();
												foreach($real as $val=>$pname){
													echo "<input type='checkbox' class='cat_perm_each' name='b_allow_$pname' id='cat_perm_each_$pname' value='1' style='height:24px;vertical-align:middle;' /> <label style='vertical-align:middle;height:24px;' for='cat_perm_each_$pname'>{$disp[$val]}</label><br />";
												}
												?>
												<big>PRESET</big>
												<input type="button" onclick="$('#cat_perm_each_list').prop('checked',true);$('#cat_perm_each_search').prop('checked',true);$('#cat_perm_each_view').prop('checked',true);$('#cat_perm_each_write').prop('checked',false);$('#cat_perm_each_edit').prop('checked',false);$('#cat_perm_each_edit').prop('checked',false);$('#cat_perm_each_delete').prop('checked',false);$('#cat_perm_each_comment_view').prop('checked',true);$('#cat_perm_each_comment_write').prop('checked',false);$('#cat_perm_each_comment_edit').prop('checked',false);$('#cat_perm_each_comment_delete').prop('checked',false);$('#cat_perm_each_attach_download').prop('checked',true);$('#cat_perm_each_attach_upload').prop('checked',false);" value="읽기 전용" style="height:32px;" />
												<input type="button" onclick="$('#cat_perm_each_list').prop('checked',true);$('#cat_perm_each_search').prop('checked',true);$('#cat_perm_each_view').prop('checked',true);$('#cat_perm_each_write').prop('checked',false);$('#cat_perm_each_edit').prop('checked',false);$('#cat_perm_each_edit').prop('checked',false);$('#cat_perm_each_delete').prop('checked',false);$('#cat_perm_each_comment_view').prop('checked',true);$('#cat_perm_each_comment_write').prop('checked',true);$('#cat_perm_each_comment_edit').prop('checked',true);$('#cat_perm_each_comment_delete').prop('checked',true);$('#cat_perm_each_attach_download').prop('checked',true);$('#cat_perm_each_attach_upload').prop('checked',false);" value="읽기 전용, 댓글 가능" style="height:32px;" />
												<input type="button" onclick="$('#cat_perm_each_list').prop('checked',true);$('#cat_perm_each_search').prop('checked',true);$('#cat_perm_each_view').prop('checked',true);$('#cat_perm_each_write').prop('checked',true);$('#cat_perm_each_edit').prop('checked',true);$('#cat_perm_each_edit').prop('checked',true);$('#cat_perm_each_delete').prop('checked',true);$('#cat_perm_each_comment_view').prop('checked',true);$('#cat_perm_each_comment_write').prop('checked',true);$('#cat_perm_each_comment_edit').prop('checked',true);$('#cat_perm_each_comment_delete').prop('checked',true);$('#cat_perm_each_attach_download').prop('checked',true);$('#cat_perm_each_attach_upload').prop('checked',true);" value="글 쓸 수 있음" style="height:32px;" />
												<br />
												<br />
												<input type="submit" value="저장" style="width:96px;height:32px;" />
												<input type="button" onclick="user_manage_flipChecked('cat_perm_each');" value="뒤집기" style="width:96px;height:32px;" />
												<input type="button" onclick="user_manage_setChecked('cat_perm_each',true);" value="모두 선택" style="width:96px;height:32px;" />
												<input type="button" onclick="user_manage_setChecked('cat_perm_each',false);" value="모두 해제" style="width:96px;height:32px;" />
											</div>
										</form>
									</div>
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
				<div id="tab_menu_1" class="tab_menu_items"<?php if(isset($_GET['display']) && $_GET['display']==1) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr>
					</table>
				</div>
				<div id="tab_menu_2" class="tab_menu_items"<?php if(isset($_GET['display']) && $_GET['display']==2) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr>
					</table>
				</div>
				<div id="tab_menu_3" class="tab_menu_items"<?php if(isset($_GET['display']) && $_GET['display']==3) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr><tr><th>A</th><td>B</td></tr>
					</table>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php
}
?>
