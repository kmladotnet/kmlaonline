<?php
redirectLoginIfRequired();
$title="설정 - " . $title;
function printContent(){
	global $me, $member, $board;
	$me=array_merge($me, $member->getAdditionalData($me['n_id']));
	insertOnLoadScript("putAlertOnLeave();");
	?>
	<form action="/ajax/user/settings" method="post" enctype="multipart/form-data" onsubmit="window.onbeforeunload=null;">
		<div class="tab_global_wrap">
			<div class="what_to_do">
				<?php if(!isset($_SESSION["setting_change_no_pw_needed"])){ ?>
					<div><?php elang("user","settings","password prev")?></div>
					<div><input type="password" name="s_pw_prev" style="width:100%;box-sizing:border-box" /></div>
				<?php } ?>
				<div>
					<input type="reset" value="<?php elang("generic","revert")?>" style="float:left;height:32px;width:70px;" />
					<input type="submit" value="<?php elang("user","settings","save")?>" style="float:right;height:32px;width:70px;" />
				</div>
			</div>
			<div class="tab_menu">
				<ul>
					<li><a id="tab_menu_switch_0" href="#" onclick="return changeTab(0);"><?php elang("user","settings","myinfo","")?></a></li>
					<li><a id="tab_menu_switch_1" href="#" onclick="return changeTab(1);"><?php elang("user","settings","mainboard","")?></a></li>
					<li><a id="tab_menu_switch_2" href="#" onclick="return changeTab(2);"><?php elang("user","settings","menu","")?></a></li>
					<li><a id="tab_menu_switch_3" href="#" onclick="return changeTab(3);"><?php elang("user","settings","mypage","")?></a></li>
					<li><a id="tab_menu_switch_4" href="#" onclick="return changeTab(4);"><?php elang("user","settings","message","")?></a></li>
				</ul>
			</div>
			<div class="tab_wrap" style="min-height:320px;">
				<div class="tab_menu_content">
					<div id="tab_menu_0" class="tab_menu_items">
						<table class="table_info_view">
							<tr>
								<th>ID</th>
								<td>
									<input type="text" name="s_id" value='<?php echo htmlspecialchars(isset($_POST['s_id'])?$_POST['s_id']:$me['s_id'])?>' />
									<?php if(strstr($me['s_id'],"@")) echo "<br /><span style='color:red'>영문자, 숫자, -, _ 로만 구성해 주세요!</span>"; ?>
								</td>
							</tr>
							<tr>
								<th>E-Mail</th>
								<td>
									<input type="text" name="s_email" value='<?php echo htmlspecialchars(isset($_POST['s_email'])?$_POST['s_email']:$me['s_email'])?>' /><br />
									<!-- <div class="changeinfo_information">바뀌면 확인 메일이 발송됩니다.</div> -->
								</td>
							</tr>
							<tr>
								<th>이름</th>
								<td><input type="text" name="s_kor_name" value='<?php echo htmlspecialchars(isset($_POST['s_name'])?$_POST['s_name']:$me['s_name'])?>' /></td>
							</tr>
							<tr>
								<th>영어 이름</th>
								<td><input type="text" name="s_eng_name" value='<?php echo htmlspecialchars(isset($_POST['s_real_name'])?$_POST['s_real_name']:$me['s_real_name'])?>' /></td>
							</tr>
							<tr>
								<th>생일</th>
								<td>
									<input type="text" style="width:60px;" name="n_birth_date_yr" value='<?php echo htmlspecialchars(isset($_POST['n_birth_date_yr'])?$_POST['n_birth_date_yr']:$me['n_birth_date_yr'])?>' />년 
									<input type="text" style="width:30px;" name="n_birth_date_month" value='<?php echo htmlspecialchars(isset($_POST['n_birth_date_month'])?$_POST['n_birth_date_month']:$me['n_birth_date_month'])?>' />월 
									<input type="text" style="width:30px;" name="n_birth_date_day" value='<?php echo htmlspecialchars(isset($_POST['n_birth_date_day'])?$_POST['n_birth_date_day']:$me['n_birth_date_day'])?>' />일
								</td>
							</tr>
							<tr>
								<th>패스워드 변경</th>
								<td>
									<input type="password" name="s_pw" value="" /><br />
									<input type="password" name="s_pw_check" value="" /><br />
								</td>
							</tr>
							<tr>
								<th>홈페이지</th>
								<td><input type="text" name="s_homepage" value='<?php echo htmlspecialchars(isset($_POST['s_homepage'])?$_POST['s_homepage']:$me['s_homepage'])?>' /></td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td><input type="text" name="s_phone" value='<?php echo htmlspecialchars(isset($_POST['s_phone'])?$_POST['s_phone']:$me['s_phone'])?>' /></td>
							</tr>
							<tr>
								<th>학년, 반, 방, 학번</th>
								<td>
									<input type="text" name="n_grade" value='<?php echo htmlspecialchars(isset($_POST['n_grade'])?$_POST['n_grade']:$me['n_grade'])?>' style="width:64px;" /> 학년 
									<input type="text" name="s_class" value='<?php echo htmlspecialchars(isset($_POST['s_class'])?$_POST['s_class']:$me['s_class'])?>' style="width:64px;" /> 반<br /> 
									방: <input type="text" name="s_room" value='<?php echo htmlspecialchars(isset($_POST['s_room'])?$_POST['s_room']:$me['s_room'])?>' style="width:64px;" /><br />
									학번: <input type="text" name="n_student_id" style="width:80px" value='<?php echo htmlspecialchars(isset($_POST['n_student_id'])?$_POST['n_student_id']:$me['n_student_id'])?>' />
								</td>
							</tr>
							<tr>
								<th>사진 변경</th>
								<td>
									<?php if($me['s_pic']){ ?>
										<a target="_blank" href="<?php echo htmlspecialchars(str_replace("picture/","picture_full/",$me['s_pic']))?>" rel="lightbox"><img src="<?php echo htmlspecialchars($me['s_pic'])?>" style="width:90px;height:90px;" /></a>
										<input id="b_remove_pic" type="checkbox" name="b_remove_pic" value="yes" />
										<label for="b_remove_pic">사진 제거</label><br />
									<?php } ?>
									<div class="upper-file"><input type="file" name="s_pic" /><span>파일 선택</span></div>
								</td>
							</tr>
							<tr>
								<th>아이콘 변경</th>
								<td>
									<?php if($me['s_icon']){ ?>
										<a target="_blank" href="<?php echo htmlspecialchars(str_replace("icon/","icon_full/",$me['s_icon']))?>" rel="lightbox"><img src="<?php echo htmlspecialchars($me['s_icon'])?>" style="width:90px;height:90px;" /></a>
										<input id="b_remove_icon" type="checkbox" name="b_remove_icon" value="yes" />
										<label for="b_remove_icon">아이콘 제거</label><br />
									<?php } ?>
									<div class="upper-file"><input type="file" name="s_icon" /><span>파일 선택</span></div>
								</td>
							</tr>
						</table>
					</div>
					<div id="tab_menu_1" class="tab_menu_items">
						<table class="table_info_view">
							<tr>
								<th>첫 화면에 보일 게시판</th>
								<td>
									<div style="float:right">
										<button style="width:120px;height:32px" onclick="$('.chk_board_on_main').attr('checked','checked'); return false;">모두 선택</button>
										<button style="width:120px;height:32px" onclick="$('.chk_board_on_main').removeAttr('checked'); return false;">모두 해제</button>
									</div>
									<?php
									$accessible_categories=getUserMainBoards($me);
									foreach($board->getCategoryList(0,0) as $val){
										if(checkCategoryAccess($val['n_id'], "list")){
											if(isset($accessible_categories[$val['n_id']]))
												$checked="checked=\"checked\"";
											else
												$checked="";
											?>
											<div style="padding:3px;"><input type="checkbox" class="chk_board_on_main" name="board_on_main[]" value="<?php echo $val['n_id']?>" id="_board_id_<?php echo $val['n_id']?>" <?php echo $checked?> /> <label for="_board_id_<?php echo $val['n_id']?>"><?php echo htmlspecialchars($val['s_name']); ?></label></div>
											<?php
										}
									}
									?>
								</td>
							</tr>
						</table>
					</div>
					<div id="tab_menu_2" class="tab_menu_items">
						<table class="table_info_view">
							<tr>
								<th>메뉴 관리</th>
								<td>
									<div style="float:right">
										<button style="width:120px;height:32px" onclick="return false;">모두 지우기</button>
									</div>
									<div style="float:left">
										<button style="width:120px;height:32px" onclick="settings_menu_addCategory(); return false;">분류 추가</button>
										<button style="width:120px;height:32px" onclick="settings_menu_addSubItem(); return false;">항목 추가</button>
									</div>
									<ul id="menusorter">
										<?php
										$menu_bar=getUserMenuBar($me);
										foreach($menu_bar as $menu_name=>$menu_items){
											?>
											<li>
												<div class="handle" style="background:black"><hr /><hr /><hr /></div>
												<div class="assigned">
													<b>분류</b>
													<input type="text" class="menu_titles" name="menu_titles[]" value="<?php echo htmlspecialchars($menu_name); ?>" style="width:64px;" />
												</div>
												<div class="remove" onclick="settings_menu_removeSelf(this);">X</div>
												<input type="hidden" class="menu_data" name="menu_data[]" value="divider" />
											</li>
											<?php
											foreach($menu_items as $menu_info){
												?><li>
													<div class="handle"><hr /><hr /><hr /></div>
													<div class="assigned-2">
														<b>하위 항목</b>
														<input type="text" class="menu_titles" name="menu_titles[]" value="<?php echo htmlspecialchars($menu_info[2]);?>" style="width:64px;" />
														<span class="desc"><?php
															switch($menu_info[0]){
																case "url":  echo getLinkDescription($menu_info[1]); break;
															}
														?></span>
													</div>
													<div class="remove" onclick="settings_menu_removeSelf(this);">X</div>
													<input type="hidden" class="menu_data" name="menu_data[]" value="<?php echo htmlspecialchars($menu_info[0].":".$menu_info[1]);?>" />
												</li><?php
											}
										}
										?>
									</ul>
									<div id="compactLinkSelector" style="display:none;text-align:left">
										<h1 style="text-align:center">잠시만 기다려 주세요...</h1>
									</div>
								</td>
						</table>
					</div>
					<div id="tab_menu_3" class="tab_menu_items">
						<div>
							NEED FIX<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						</div>
					</div>
					<div id="tab_menu_4" class="tab_menu_items">
						<div>
							NEED FIX<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						</div>
					</div>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
	</form>
	<script type="text/html" id="menuCategoryForm">
		<li>
			<div class="handle" style="background:black"><hr /><hr /><hr /></div>
			<div class="assigned">
				<b>분류</b>
				<input type="text" class="menu_titles" name="menu_titles[]" value="<%MENUNAME%>" style="width:64px;" />
			</div>
			<div class="remove" onclick="settings_menu_removeSelf(this);">X</div>
			<input type="hidden" class="menu_data" name="menu_data[]" value="divider" />
		</li>
	</script>
	<script type="text/html" id="menuItemForm">
		<li>
			<div class="handle"><hr /><hr /><hr /></div>
			<div class="assigned-2">
				<b>하위 항목</b>
				<input type="text" class="menu_titles" name="menu_titles[]" value="<%MENUNAME%>" style="width:64px;" />
				<span class="desc"><%MENUNAME%></span>
			</div>
			<div class="remove" onclick="settings_menu_removeSelf(this);">X</div>
			<input type="hidden" class="menu_data" name="menu_data[]" value="<%MENUACTION%>" />
		</li>
	</script>
	<?php
	if(isset($_POST['menu_titles'])){
		insertOnLoadScript("settings_menu_reload(JSON.parse(\"".addslashes(json_encode($_POST['menu_titles']))."\"),JSON.parse(\"".addslashes(json_encode($_POST['menu_data']))."\"));");
	}
	insertOnLoadScript("$( '#menusorter' ).sortable({ handle: '.handle' });");
	insertOnLoadScript("changeTab(" . ((isset($_GET['display'])&&is_numeric($_GET['display']))?$_GET['display']:"0") . ",true);");
}