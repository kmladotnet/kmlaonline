<?php
redirectLoginIfRequired();
$user_not_found=false;

# RewriteRule ^user/([a-z]+)/([0-9]+)/([A-Za-z0-9_\-]+)$ index.php?action=user$1&userbynid=$2&userbysid=$3 [L]
# RewriteRule ^user/([a-z]+)/([A-Za-z0-9_\-]+)$ index.php?action=user$1&userbysid=$2 [L]

$user_by=(isset($_GET['p2'])?2:0) + (isset($_GET['p1'])?1:0);
isset($_GET['p1']) && $user_n_id=$_GET['p1'];
isset($_GET['p2']) && $user_s_id=$_GET['p2'];

$redir=false;
if($user_by==3){
	$user_to=$member->getMember($user_n_id,0);
	if($user_to===false) $user_not_found=true;
	else if($user_s_id!=$user_to['s_id']) $redir=true;
}else if($user_by==1){
	$redir=true;
	$user_to=$member->getMember($user_n_id,1);
	if($user_to===false) $user_to=$member->getMember($user_n_id,0);
}else{
	$user_to=$me;
	$redir=true;
}
if($user_to!==false && $redir===true)
	redirectTo("/user/view/{$user_to["n_id"]}/{$user_to["s_id"]}");
if($user_not_found===true)
	redirectAlert(false,"없는 사용자입니다.");
$title=$user_to['s_name']."의 사용자 정보 - " . $title;
function printContent(){
	global $user_to, $me, $user_s_id, $member;
	$is_self=$me==$user_to;
	$user_to=array_merge($user_to, $member->getAdditionalData($user_to['n_id']));
	?>
	<div class="userinfo_border" style="margin:12px;padding:12px;">
		<div class="user_pic">
			<?php if($user_to['s_pic']){ ?>
				<a target="_blank" href="<?php echo htmlspecialchars(str_replace("picture/","picture_full/",$user_to['s_pic']))?>" data-toggle="lightbox"><img src="<?php echo htmlspecialchars($user_to['s_pic'])?>" style="width:90px;height:90px;" /></a>
			<?php }else{ ?>
				<img src="/images/no-image.png" style="width:100px;height:100px;" />
			<?php } ?>
		</div>
		<div class="user_action">
			<a href="/board/special:list-all?search_mode=or&amp;search_writer=true&amp;search=<?php echo urlencode($user_to['n_level']."기 ".$user_to['s_name']); ?>">찾아보기</a> |
			<?php if($is_self){ ?>
				<a href="/user/settings">정보 수정</a> |
				<a href="/user/message">쪽지</a> |
				<a href="/user/logout" onclick="return true; $('#logout_form').submit(); return false;"><?php echo lang("generic","logout"); ?></a>
				<form method="post" action="/user/logout" id="logout_form" onsubmit="return true;"><input type="hidden" name="returnto" value="/" /></form>
			<?php }else{ ?>
				<a href="/user/message?compose_to=<?php echo $user_to['n_id'] ?>">쪽지 보내기</a>
				 | <a href="/user/block/<?php echo $user_to['n_id'] ?>">차단</a>
			 <?php } ?>
		</div>
		<div class="user_basics">
			<div>
				<?php if($user_to['s_icon']){ ?><img src="<?php echo htmlspecialchars($user_to['s_icon']) ?>" style="width:18pt;height:18pt;" /> <?php } ?>
				<span style="font-weight:bold;font-size:18pt;"><?php echo htmlspecialchars($user_to['s_name']); ?></span>
				<span style="font-size:12pt;color:gray;"><?php echo htmlspecialchars($user_to['s_real_name']); ?></span>
				<span style="font-size:12pt;color:gray;">(<?php echo htmlspecialchars($user_to['n_level']."기".($user_to['n_gender']==1?" 남자":($user_to['n_gender']==2?" 여자":" 기타"))); ?>)</span>
			</div>
			<div style="margin-top:5px;margin-bottom:5px;">
				<?php echo htmlspecialchars($user_to['s_status_message']) ?>
			</div>
		</div>
		<div class="user_info">
			<table class="userinfo_table">
				<tr>
					<th>생일</th>
					<td><?php echo htmlspecialchars(date("Y년 m월 d일",strtotime($user_to['n_birth_date_yr']."-".$user_to['n_birth_date_month']."-".$user_to['n_birth_date_day'])))?></td>
				</tr>
				<tr>
					<th>E-Mail</th>
					<td><a href="mailto:<?php echo htmlspecialchars($user_to['s_email'])?>"><?php echo htmlspecialchars($user_to['s_email'])?></a></td>
				</tr>
				<?php if($user_to['s_phone']){ ?>
					<tr>
						<th>전화번호</th>
						<td><a href="tel:<?php echo htmlspecialchars($user_to['s_phone'])?>"><?php echo htmlspecialchars($user_to['s_phone'])?></a></td>
					</tr>
				<?php } ?>
				<?php if($user_to['s_homepage']){ ?>
					<tr>
						<th>홈페이지</th>
						<td><a target="_blank" href="<?php echo htmlspecialchars($user_to['s_homepage'])?>"><?php echo htmlspecialchars($user_to['s_homepage'])?></a></td>
					</tr>
				<?php } ?>
				<?php if($user_to['s_interest']){ ?>
					<tr>
						<th>관심사</th>
						<td><?php echo htmlspecialchars($user_to['s_interest'])?></td>
					</tr>
				<?php } ?>
				<tr>
					<th>방</th>
					<td><?php echo htmlspecialchars($user_to['s_room'])?></td>
				</tr>
				<tr>
					<th>학년 반</th>
					<td><?php echo htmlspecialchars($user_to['n_grade'])?>학년 <?php echo htmlspecialchars($user_to['s_class'])?>반</td>
				</tr>
				<tr>
					<th>학번</th>
					<td><?php echo htmlspecialchars($user_to['n_student_id'])?></td>
				</tr>
				<tr>
					<th>글 수</th>
					<td><?php echo htmlspecialchars($user_to['n_posts_started'])?>개</td>
				</tr>
				<tr>
					<th>댓글 수</th>
					<td><?php echo htmlspecialchars($user_to['n_posts_participated'])?>개</td>
				</tr>
			</table>
		</div>
	</div>
	<?php
}
?>
