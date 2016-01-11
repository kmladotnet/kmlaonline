<?php if(!isset($_SESSION['user'])){  ?>
<?php }else{ ?>
	<table style="width:100%;height:100px">
		<tr style="height:100px">
			<td style="width:100px;">
				<?php if($me['s_pic']){ ?>
					<a target="_blank" href="<?php echo htmlspecialchars(str_replace("picture/","picture_full/",$me['s_pic']))?>" rel="lightbox"><img src="<?php echo htmlspecialchars($me['s_pic'])?>" class="userarea-image" /></a>
				<?php }else{ ?>
					<img class="userarea-image" src="/images/no-image.png" />
				<?php } ?>
			</td>
			<td style="text-align:center" id="profile_nav">
				<form method="post" action="/user/logout" id="logout_form" onsubmit="return true;">
					<input type="hidden" name="returnto" value="/" />
					<div><?php putUserCard($me); ?></div>
					<div><a href="<?php echo "/user/view/{$me['n_id']}/{$me['s_id']}"?>"><?php echo lang("generic","information"); ?></a> | <a href="/user/message"><?php echo lang("generic","message"); ?></a></div>
					<div><a href="/user/settings"><?php echo lang("generic","setting"); ?></a> | <a href="/user/manage"><?php echo lang("generic","manage"); ?></a></div>
					<div><a href="/board/special:list-all">모든글</a><?php if($me['n_admin']>0){ ?> | <a href="/admin"><?php echo lang("generic","admin"); ?></a><?php } ?></div>
					<a href="/user/logout" onclick="$('#logout_form').submit(); return false;"><?php echo lang("generic","logout"); ?></a>
				</form>
			</td>
		</tr>
	</table>
	<div style="overflow:hidden;width:222px;display:block;margin-top:-6px;">
		<a href="#" onclick="return editStatusMessageShow();"><div id="status_message"><?php echo htmlspecialchars($me['s_status_message']) ?></div></a>
		<form method="post" action="ajax/user/statusmessage" onsubmit="return doAjaxStatusMessageChange();">
			<input type="hidden" name="returnto" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="submit" style="display:none" />
			<input id="status_message_edit" style="display:none" name="s_status_message" type="text" value="<?php echo htmlspecialchars($me['s_status_message']) ?>" />
		</form>
	</div>
<?php }
