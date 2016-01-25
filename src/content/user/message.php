<?php
redirectLoginIfRequired();
$title="쪽지 - " . $title;

//default values
isset($_GET['p1']) || $_GET['p1']=0;
isset($_GET['p2']) || $_GET['p2']=0;
$findwhat=$_GET['p1']; $mode=$_GET['p2'];

if($sent=($_GET['p1']==="sent"))
	$pagenumber=$_GET['p1'];
else
	$pagenumber=$_GET['p2'];
function printContent(){
	global $me,$member, $sent, $pagenumber;
	$articleperpage=20;
	$orderby_name="n_id";
	$orderby_desc=true;
	$incl_text=0;
	$search=((isset($_GET['search']) && $_GET['search']!="")?$_GET['search']:false);
	$article_count=$member->getNotesCount($me['n_id'], $sent?false:$me['n_id'], !$sent?false:$me['n_id'], $search, false, false, true ,true);
	if(!is_numeric($pagenumber)) $pagenumber=0;
	else if($pagenumber!=0) $pagenumber--;
	if($pagenumber<0) $pagenumber=0;
	$page_count=intval(($article_count+$articleperpage-1)/$articleperpage);
	$notes=$member->getNotesList($me['n_id'], $sent?false:$me['n_id'], !$sent?false:$me['n_id'], $pagenumber, $articleperpage, $search, false, false, true ,true);
	$pagenumber++;
	?>
	<div style="float:right;padding:20px;">
		<?php if($sent){ ?>
			<a href="/user/message">받은 쪽지함</a>
		<?php }else{ ?>
			<a href="/user/message/sent">보낸 쪽지함</a>
		<?php } ?>
		<a href="/ajax/user/clearmessage" onclick="return user_message_confirmClearAll();">초기화</a>
	</div>
	<?php if($sent){ ?>
		<h1 style="padding:5px;">보낸 쪽지</h1>
	<?php }else{ ?>
		<h1 style="padding:5px;">받은 쪽지</h1>
	<?php } ?>
	<div style="clear:both"></div>
	<div id="message_main">
		<form method="post" action="/ajax/user/message" onsubmit="return saveAjax(this, '메시지 보내는 중...', 's_data_ckeditor');">
			<div style="padding:5px;">
				<div id="div_message">
					<div id="compose" class="div_message_item">
						<textarea id="s_data_ckeditor" name="s_data" style="width:100%;box-sizing:border-box;height:80px;"></textarea>
						<div style="clear:both"></div>
						<input type="submit" name="cmd_write" value="보내기" style="float:right;width:80px;height:32px;" />
						<div style="clear:right"></div>
						<table style="margin-top:8px;">
							<tr>
								<td style="width:140px;vertical-align:top;text-align:center;">
									<b>보낼 대상</b>
									<ul class="user_list">
										<li style="border-top:1px dashed gray;cursor:pointer" onclick="smoothToggleVisibility('#id_finder');">찾아보기</li>
									</ul>
								</td>
								<td style="vertical-align:top;">
									<div id="id_finder" style="display:none">
										<input type="hidden" id="n_user_find_page" value="0" />
										<div>
											사용자: 
											<input type="text" id="s_user_to_find" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {document.getElementById('cmd_search_for_user').click();return false;}};" /> 
											<input id="cmd_search_for_user" type="button" value="찾기" onclick="user_message_searchForUsers(true);" /> 
											<input type="button" value="계속 찾기" onclick="user_message_searchForUsers(false);" id="cmd_search_more" disabled="disabled" />
										</div>
										<ul id="id_find_results"></ul>
										<input id="add_user_to_list" type="button" value="추가" onclick="user_message_applyToList();" />
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="message_list">
		<ul style="padding: 0">
			<li style="text-align:center;font-weight:bold;font-size:15pt;background:white" onclick="user_message_loadCompose();" id="div_message_compose">
				새로 쓰기
			</li>
			<?php foreach($notes as $val){
				$m=$member->getMember($val['n_from']);
				?>
				<li class="message_item" id="message_list_item_<?php echo $val['n_id']?>" onclick="user_message_loadMessage(<?php echo $val['n_id']?>);">
					<?php if($m['s_pic']){ ?><img class="image" src="<?php echo htmlspecialchars($m['s_pic'])?>" /><?php } ?>
					<div class="writer"><?php putUserCard($m); ?></div>
					<div class="when"><?php echo date("Y-m-d H:i:s",$val['n_date']) ?></div>
					<div class="preview">
						<?php
						if(mb_strlen($val['s_data'])>24)
							$str=mb_substr($val['s_data'],0,20,"UTF-8") . "...";
						else
							$str=$val['s_data'];
						$str=strip_tags($str);
						if($val['n_read'])
							echo $str;
						else
							echo "<span style='font-weight:bold;color:gray;'>(읽지 않은 메시지)</span>";
						?>
					</div>
					<div class="actions">
						<a onclick="return user_message_removeNote(<?php echo $val['n_id']?>);">삭제</a>
					</div>
					<div style="clear:both"></div>
				</li>
			<?php } ?>
		</ul>
		<div style="background:white">
			<div style="text-align:center">
				<?php $disp=array(1=>true); ?>
				<a href="<?php echo "/user/message/".($sent?"sent/":"")."1" ;?>" <?php if($pagenumber==1) echo "style='color:black'" ?>>[1]</a> 
				<?php if(2<$pagenumber-4) echo "..."; ?>
				<?php for($i=max(2,$pagenumber-4); $i<=min($page_count-1, $pagenumber+4); $i++){ ?>
					<a href="<?php echo "/user/message/".($sent?"sent/":"")."$i" ;?>" <?php if($pagenumber==$i) echo "style='color:black'" ?>>[<?php echo $i; $disp[$i]=true;?>]</a> 
				<?php } ?>
				<?php if($i<$page_count && $i!=max(2,$pagenumber-4)) echo "..."; ?>
				<?php if(!isset($disp[$page_count]) && $page_count>1){ ?><a href="<?php echo "/user/message/".($sent?"sent/":"")."$i" ;?>" <?php if($pagenumber==$page_count) echo "style='color:black'" ?>>[<?php echo $page_count?>]</a><?php } ?>
			</div>
			<div style="padding:10px;">
				<form method="get" action="/user/message<?php echo ($sent?"/sent":"")?>">
					<input type="text" name="search" value="<?php echo $search?htmlspecialchars($search):""?>" style="margin-left:3px;width:200px;vertical-align:middle;" />
					<input type="submit" id="search_button" value="검색" style="width:80px;vertical-align:middle;" />
				</form>
			</div>
			<div style="margin:0 auto;width:120px;text-align:center;">총 <?php echo $article_count?>개</div>
		</div>
	</div>

    <script src="/js/content/user/message.js"></script>
    <script src="//cdn.ckeditor.com/4.5.6/basic/ckeditor.js"></script>
	<script type="text/html" id="user_found_template">
		<li><input type="checkbox" id="chk_<%=USERINDEX%>" class="user_search_results" value="<%=USERINDEX%>" rel="<%=USERDESC%>" /> <label for="chk_<%=USERINDEX%>"><%=USERDESC%></label></li>
	</script>
	<script type="text/html" id="user_list_template">
		<li style="pointer:hand" onclick="if(confirm('보낼 사용자 목록에서 제거하시겠습니까?')){$(this).remove();}"><input name="user_to[]" value="<%=USERINDEX%>" type="hidden" class="user_index_<%=USERINDEX%>" /><%=USERDESC%></li>
	</script>
	<?php
	insertOnLoadScript("CKEDITOR.replace('s_data_ckeditor');");
	if(isset($_GET['compose_to'])){
		if(($m=$member->getMember($_GET['compose_to']))!==false){
			$nid=$m['n_id']; $sdesc=addslashes($m['s_id'] . " (" . $m['s_name'] . ")");
			insertOnLoadScript("user_message_addRecepientUser($nid, \"$sdesc\");");
		}
	}
}
