<?php
if(!isset($me) || $me['n_admin']==0) redirectAlert(false,lang("user","permission","error","admin"));
$title="Admin - " . $title;
function printContent(){
	/*
	Manage Users
	Manage Board Categories
	Manage Articles
	*/
	global $board, $member, $mysqli;
	?>
	<div class="tab_global_wrap" style="min-height:240px;">
		<div class="what_to_do">
			<div style="padding-bottom:12px;"><?php echo lang("admin","only")?></div>
			<div><input type="checkbox" name="admin_override" id="chk_admin_override" onchange="setAdminOverride($(this));" <?php if(isset($_SESSION['admin_override'])) echo "checked='checked'" ?> /> <label for="chk_admin_override"><?php echo lang("admin","override")?></label></div>
		</div>
		<div class="tab_menu">
			<ul>
				<li><a id="tab_menu_switch_0" href="#" onclick="return changeTab(0);"<?php if(!isset($_GET['display']) || $_GET['display']==0) echo ' class="tab_menu_selected"';?>><?php echo lang("admin","manage","user");?></a></li>
				<li><a id="tab_menu_switch_1" href="#" onclick="return changeTab(1);"<?php if(isset($_GET['display']) && $_GET['display']==1) echo ' class="tab_menu_selected"';?>><?php echo lang("admin","manage","category");?></a></li>
				<li><a id="tab_menu_switch_2" href="#" onclick="return changeTab(2);"<?php if(isset($_GET['display']) && $_GET['display']==2) echo ' class="tab_menu_selected"';?>><?php echo lang("admin","manage","misc");?></a></li>
				<!--
				<li><a id="tab_menu_switch_2" href="#" onclick="return changeTab(2);"<?php if(isset($_GET['display']) && $_GET['display']==2) echo ' class="tab_menu_selected"';?>>부서 관리</a></li>
				<li><a id="tab_menu_switch_3" href="#" onclick="return changeTab(3);"<?php if(isset($_GET['display']) && $_GET['display']==3) echo ' class="tab_menu_selected"';?>>동아리 관리</a></li>
				-->
			</ul>
		</div>
		<div class="tab_wrap">
			<div class="tab_menu_content">
				<div id="tab_menu_0" class="tab_menu_items"<?php if(!isset($_GET['display']) || $_GET['display']==0) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<tr>
							<th><?php elang("generic","list")?></th>
							<td>
								<table class="table_info_view">
									<thead>
										<tr>
											<th style="width:16px;text-align:left"></th>
											<th style="width:36px;text-align:left"><?php echo lang("generic","index")?></th>
											<th style="width:80px;text-align:left"><?php echo lang("generic","id")?></th>
											<th style="width:36px;text-align:left"><?php echo lang("generic","level")?></th>
											<th style="width:80px;text-align:left"><?php echo lang("generic","name")?></th>
											<th style="text-align:left"><?php echo lang("generic","student_id")?></th>
										</tr>
									</thead>
									<tbody id="search_results">
										<?php
										$usrs=$member->listMembers(0,20);
										foreach($usrs as $usr){
											if($usr['n_id']==1) continue;
											?>
											<tr>
												<td><input type="radio" name="user_selection" id="user_selection_<?php echo $usr['n_id']?>" /></td>
												<td><?php echo $usr['n_id']?></td>
												<td><?php echo htmlspecialchars($usr['s_id'])?></td>
												<td><?php echo $usr['n_level']?></td>
												<td><?php echo $usr['s_name']?></td>
												<td><?php echo $usr['s_email']?></td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<th><?php echo lang("generic","find")?></th>
							<td>
								<input type="hidden" id="n_user_find_page" value="0" />
								<input type="text" id="s_user_to_find" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmd_search_for_user').click();return false;}};" />
								<input type="button" id="cmd_search_for_user" value="<?php echo lang("generic","find")?>" onclick="searchForUsers(true);" />
								<input type="button" id="cmd_search_more" value="<?php echo lang("generic","findnext")?>" onclick="searchForUsers(false);" />
							</td>
						</tr>
						<tr>
							<th><?php echo lang("generic","misc")?></th>
							<td>
								<?php echo lang("admin","user","select first")?><br />
								<form method="post" action="/proc/admin/user" id="user_action" style="display:none" onsubmit="return saveAjax(this,'유저 작업 중...');">
									<input type="hidden" name="what" value="" />
									<input type="hidden" name="user" value="" />
									<input type="button" value="<?php echo lang("generic","login")?>" />
									<input type="button" value="<?php echo lang("generic","remove")?>" />
								</form>
							</td>
						</tr>
					</table>
				</div>
				<div id="tab_menu_1" class="tab_menu_items"<?php if(isset($_GET['display']) && $_GET['display']==1) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<tr>
							<th><?php echo lang("generic","add")?></th>
							<td>
								<form method="post" action="/check" onsubmit="return saveAjax(this,'카테고리 추가 중...');">
									<input type="hidden" name="action" value="admin" />
									<input type="hidden" name="admin_act" value="category" />
									<input type="hidden" name="cat_act" value="add" />
									<div style="display:inline-block;width:100px;">카테고리 ID:</div><input type="text" name="new_cat_id" value="" /><br />
									<div style="display:inline-block;width:100px;">카테고리 이름:</div><input type="text" name="new_cat_name" value="" /><br />
									<div style="display:inline-block;width:100px;">카테고리 종류:</div><select name="new_cat_type" style="width:100px;"><option value="0">게시판</option><option value="1">갤러리</option><option value="2">포럼</option></select><br />
									<div style="display:inline-block;width:100px;">카테고리 설명:</div><input type="text" name="new_cat_description" value="" /><br />
									<input type="submit" class="btn btn-default"  value="추가" style="width:96px;height:32px;" />
								</form>
							</td>
						</tr>
						<tr>
							<th><?php echo lang("generic","edit")?></th>
							<td>
								<form method="post" action="/check" onsubmit="return saveAjax(this,'카테고리 수정 중...');">
									<input type="hidden" name="action" value="admin" />
									<input type="hidden" name="admin_act" value="category" />
									<input type="hidden" name="cat_act" value="edit" />
									<?php  $i=0; $cpp=25; $catList=$board->getCategoryList(); $cnt=count($catList)-1; $cnt=($cnt-($cnt%$cpp))/$cpp; ?>
									<div style="display:block;float:right">
										ID로 찾기: <input onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {return findCategoryPage($('#txt_cat_id').val(), <?php echo $cnt?>)}};" type="text" id="txt_cat_id" style="width:80px;" /><input type="button" onclick="return findCategoryPage($('#txt_cat_id').val(), <?php echo $cnt?>)" value="찾기" style="width:48px;" />
									</div>
									<div style='padding:5px;display:block;float:left;'>
										<?php
										for($j=0;$j<=$cnt;$j++){
											echo "<span style='padding:5px;'><a onclick='changeCategoryPage($j, $cnt);'>[".($j+1)."]</a></span>";
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
												<td><a onclick="return prepareCategoryPermission(<?php echo $val['n_id']?>, '<?php echo addslashes($val['s_name'])?>');">[<?php echo $val['n_id']?>]</a></td>
												<td><input type='text' style='width:98%' name='cat_edit_<?php echo $val['n_id']?>_s_id' value='<?php echo $val['s_id']?>' /></td>
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
									<input type="submit" value="진행" style="width:96px;height:32px;" />
								</form>
							</td>
						</tr>
						<tr>
							<th>카테고리 작업</th>
							<td>
								먼저 위에서 권한을 수정할 카테고리 번호를 선택해 주세요.
								<div style="display:none" id="categoryPermissionChangeDiv">
									<div id="cat_perm_what_desc" style="padding:5px;font-weight:bold;font-size:12pt;float:left"></div>
									<div style="float:right">
										<form method="post" action="/check" onsubmit="if(confirmCategoryAction(this)) return saveAjax(this,'카테고리 작업 중...'); return false;">
											<input type="hidden" name="action" value="admin" />
											<input type="hidden" name="admin_act" value="category" />
											<input type="hidden" name="cat_act" id="cat_act_what" value="" />
											<input type="hidden" name="act_from" id="cat_act_from" value="" />
											<input type="hidden" name="act_to" id="cat_act_to" value="" />
											<input type="submit" value="제거" onclick="prepareRemove();" />
											<input type="submit" value="비우기" onclick="prepareTruncate();" />
											<input type="submit" value="옮기기" onclick="return prepareMove();" />
										</form>
									</div>
									<div style="clear:both"></div>
									<form method="post" action="/check" onsubmit="return saveAjax(this,'권한 설정 중...');">
										<input type="hidden" name="action" value="admin" />
										<input type="hidden" name="admin_act" value="category" />
										<input type="hidden" name="cat_act" value="edit_default_permission" />
										<input type="hidden" name="what" id="cat_perm_what" value="" />
										<div style="float:left;display:block;width:180px;">
											<div style="padding:5px;font-weight:bold;font-size:11pt;">기수 선택</div>
											<div style="padding:3px;">
												<input type="radio" onclick="fetchCategoryPermission();$('#n_level_select').removeAttr('disabled');$('#n_user_select').attr('disabled','disabled');" name="n_permission_type" value="category" id="n_permission_type_category" checked="checked" /><label for="n_permission_type_category"> 분류 전체 설정</label><br />
												<select name="n_level" id="n_level_select" onchange="fetchCategoryPermission();">
													<option value="all" selected="selected">기본값/외부인</option>
													<?php for($i=intval(date("Y"))-1995,$j=0;$i>=1;$i--,$j++){ ?>
														<option value="<?php echo $i?>"><?php echo $i?></option>
													<?php } ?>
												</select>
											</div>
											<div style="padding:3px;">
												<input type="radio" onclick="fetchUserPermission();$('#n_user_select').removeAttr('disabled');$('#n_level_select').attr('disabled','disabled');" name="n_permission_type" value="user" id="n_permission_type_user" /><label for="n_permission_type_user"> 사용자별 설정</label><br />
												<select name="n_user" id="n_user_select" onchange="fetchUserPermission();" disabled="disabled">
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
												<br />
												* 사용자 검색은 <b>사용자 관리</b>에서
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
											<input type="button" onclick="flipChecked('cat_perm_each');" value="뒤집기" style="width:96px;height:32px;" />
											<input type="button" onclick="setChecked('cat_perm_each',true);" value="모두 선택" style="width:96px;height:32px;" />
											<input type="button" onclick="setChecked('cat_perm_each',false);" value="모두 해제" style="width:96px;height:32px;" />
										</div>
									</form>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="tab_menu_2" class="tab_menu_items"<?php if(isset($_GET['display']) && $_GET['display']==2) echo ' style="display:block"';?>>
					<?php
					$possible_permissions=array(
						"important_article_chooser"=>"필수공지글 관리",
						"edit_food_table"=>"식단표 관리",
						"lectureroom_manager"=>"공동강의실 관리",
						"kmlaboard_changer"=>"큼라보드 수정",
					);
					?>
					<form method="post" action="/check" onsubmit="return saveAjax(this,'특별 권한 설정 중...');">
						<input type="hidden" name="action" value="admin" />
						<input type="hidden" name="admin_act" value="special_permission" />
						<table class="table_info_view">
							<tr>
								<th>권한 종류 선택</th>
								<td>
									<?php $sel=""; $a=false; foreach($possible_permissions as $k=>$v){ ?>
										<input onclick="fetchSpecialPermissioners('<?php echo $k?>');" type="radio" name="permission_type" id="permission_type_<?php echo $k?>" value="<?php echo $k?>" <?php if(!$a){$a=true; echo "checked=\"checked\""; $sel=$k;}?> />
										<label for="permission_type_<?php echo $k?>"><?php echo $v?></label>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<th>권한 있는 사람</th>
								<td>
									선택된 사람의 권한을 제거합니다.<br />
									<div id="div_special_permissioners" style="padding:5px;">
										<?php
										if(false!==$res=$mysqli->query("SELECT * FROM kmlaonline_special_permissions_table WHERE s_type='".$mysqli->real_escape_string($sel)."'")){
											while ($row = $res->fetch_array(MYSQLI_BOTH)){
												$m=$member->getMember($row['n_user']);
												?>
												<input type="checkbox" name="revoke_permission[]" id="revoke_permission_<?php echo $m['n_id']?>" value="<?php echo $m['n_id']?>" />
												<label for="revoke_permission_<?php echo $m['n_id']?>"><?php putUserCard($m);?></label>
												<br />
												<?php
											}
											$res->close();
										}
										?>
									</div>
								</td>
							</tr>
							<tr>
								<th>추가</th>
								<td>
									<div id="grant_permission_found_users" style="padding:5px">
										<?php
										$usrs=$member->listMembers(0,20);
										foreach($usrs as $usr){
											if($usr['n_id']==1) continue;
											if(isUserPermitted($usr['n_id'], $sel)) continue;
											?>
											<input type="checkbox" id="grant_permission_<?php echo $m['n_id']?>" name="grant_permission[]" value="<?php echo $usr['n_id']?>" />
											<label for="grant_permission_<?php echo $m['n_id']?>"><?php putUserCard($usr);?></label><br />
											<?php
										}
										?>
									</div>
									<input type="text" id="txt_find_user_special_permission" style="width:80px;">
									<button onclick="findSpecialPermissionUser($('#txt_find_user_special_permission').val()); return false;">사용자 찾기</button>
									<button onclick="findSpecialPermissionUser($('#txt_find_user_special_permission').val(),true); return false;">계속 찾기</button>
									<button onclick="$('#grant_permission_found_users').children().remove(); return false;">비우기</button>
								</td>
							</tr>
							<tr>
								<th></th>
								<td style="text-align:right">
									<input type="submit" value="저장" style="width:80px;height:32px;" />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div id="tab_menu_3" class="tab_menu_items"<?php if(isset($_GET['display']) && $_GET['display']==3) echo ' style="display:block"';?>>
					<table class="table_info_view">
						<tr>
							<th>A</th>
							<td>B</td>
						</tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
						<tr><th>A</th><td>B</td></tr>
					</table>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php
}
