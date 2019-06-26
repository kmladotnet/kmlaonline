<?php
$title="필수 공지 - " . $title;
/*
n_user n_article n_date s_reason n_state
state: 0-waiting 1-confirmed 2-denied 3-expired
*/
function printContentPartially($res){
	global $board, $member, $mysqli, $me;
	$permission_to_edit=isUserPermitted($me['n_id'], "important_article_chooser");
	$prc=0;
	?>
	<table style="width:100%" id="important_notices_table">
		<thead><tr style="background:#EEE;height:32px;"><th style="width:64px;text-align:center;">상태</th><th style="width:100px;text-align:center;">게시판</th><th>제목</th><th style="width:120px;">글쓴이</th><th style="width:80px;">날짜</th><th style="width:120px;">필공 요청자</th><th style="width:80px;">등록 날짜</th></tr></thead>
		<tbody>
			<?php while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$prc++;
				$article=$board->getArticle($row['n_article']);
				$cat=$board->getCategory($article['n_cat']);
				$b_anonymous=$article['n_flag']&0x4;
				$writer=$member->getMember($article['n_writer']);
				$requester=$member->getMember($row['n_user']);
				?>
				<tr style="height:32px;border-top:1px solid gray;" class="real-item">
					<td style="text-align:center;cursor:pointer;" onclick="smoothToggleVisibility($(this).parent().next());">
						<?php
						switch($row['n_state']){
							case 0: echo '대기중'; break;
							case 1: echo '승인됨'; break;
							case 2: echo '거부됨'; break;
							case 3: echo '만료됨'; break;
						}
						?>
					</td>
					<td style="cursor:pointer;text-align:center;" onclick="smoothToggleVisibility($(this).parent().next());"><?php echo htmlspecialchars($cat['s_name']); ?></td>
					<td style="cursor:pointer" onclick="changeLinkTo('/board/<?php echo $cat['s_id']?>/view/<?php echo $article['n_id']?>');"><a href="/board/<?php echo $cat['s_id']?>/view/<?php echo $article['n_id']?>"><?php echo formatTitle($article['s_title']); ?></a></td>
					<td style="cursor:pointer" ><?php if(!$b_anonymous) {putUserCard($writer);} else {echo "익명";}?></td>
					<td style="cursor:pointer" onclick="smoothToggleVisibility($(this).parent().next());"><?php if(time()-$article['n_writedate']<86400)
						echo htmlspecialchars(date("H:i:s",$article['n_writedate']));
					else
						echo htmlspecialchars(date("y-m-d",$article['n_writedate']));
					?></td>
					<td style="cursor:pointer" onclick="changeLinkTo('/user/view/<?php echo $requester['n_id']."/".rawurlencode($requester['s_id']); ?>');"><?php putUserCard($requester); ?></td>
					<td style="cursor:pointer" onclick="smoothToggleVisibility($(this).parent().next());"><?php if(time()-$row['n_date']<86400)
						echo htmlspecialchars(date("H:i:s",$row['n_date']));
					else
						echo htmlspecialchars(date("y-m-d",$row['n_date']));
					?></td>
				</tr>
				<tr style="display:none">
					<?php if($permission_to_edit){ ?>
						<td colspan="7" style="padding:5px;">
							<form action="/proc/util/important" method="post" id="important_form_<?php echo $row['n_id']?>" onsubmit="return saveAjax(this,'처리 중...');">
								<div style="float:right;display:block;width:170px;">
									<input type="hidden" id="important_util_action_<?php echo $row['n_id']?>" name="util_action" value="" />
									<input type="hidden" name="item" value="<?php echo $row['n_id']?>" />
									<input type="button" value="승인" style="width:80px;height:32px;" onclick="$('#important_util_action_<?php echo $row['n_id']?>').val('accept');$('#important_form_<?php echo $row['n_id']?>').submit();return false;" />
									<input type="button" value="거부" style="width:80px;height:32px;" onclick="$('#important_util_action_<?php echo $row['n_id']?>').val('deny');$('#important_form_<?php echo $row['n_id']?>').submit();return false;" />
									<input type="button" value="만료" style="width:80px;height:32px;" onclick="$('#important_util_action_<?php echo $row['n_id']?>').val('expire');$('#important_form_<?php echo $row['n_id']?>').submit();return false;" />
									<input type="button" value="삭제" style="width:80px;height:32px;" onclick="$('#important_util_action_<?php echo $row['n_id']?>').val('delete');$('#important_form_<?php echo $row['n_id']?>').submit();return false;" />
								</div>
								<p><b>요청 이유:</b> <?php echo htmlspecialchars($row['s_reason']); ?></p>
								<p><b>처리 이유:</b> <input type="text" name="s_process_reason" style="width:800px;" onkeydown="if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {return false;}};" /></p>
								<div style="clear:both"></div>
							</form>
						</td>
					<?php } ?>
				</tr>
			<?php }?>
		</tbody>
	</table>
	<?php
	if($prc==0){
		?><div style="padding:10px;text-align:center;font-size:15pt;">등록된 항목이 없습니다.</div><?php
	}
	?><div style="display:block;height:1px;overflow:hidden;width:100%;background:gray"></div><?php
}
function printContent(){
	global $mysqli,$board, $member;
	$pagestart=0; $pagecount=20;
	?>
	<h1 style="padding:10px;">필수 공지</h1>
	<p style="margin-left:15px;">
		작업을 수행하려면 <b>상태</b> 쪽에 있는 칸을 눌러 주세요.
	</p>
	<h2 style="padding:8px;">확인 요청</h2>
	<?php printContentPartially($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=0 ORDER BY n_id")); ?>
	<h2 style="padding:8px;">확인됨</h2>
	<?php
	printContentPartially($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state!=0 ORDER BY n_id DESC LIMIT $pagestart,$pagecount"));
	insertOnLoadScript('
		$("#important_notices_table tbody tr.real-item").mouseenter(function(){
			$(this).css("backgroundColor", "#EEE");
		}).mouseleave(function(){
			$(this).css("backgroundColor", "transparent");
		});
	');
}
