<div>
	<?php if ($is_mobile) { ?>
		<div id="total-header">
			<div id="userarea">				
				<?php
				$notic = $member->getNoticeCount($me['n_id']);
				if ($notic == 0)
					echo '<a class="backtomain" href="/">KMLA ONLINE</a>';
				else
					echo '<a class="backtomain" href="/user/notification">'.$notic.' 알림</a>';
				?>
				<?php if (isset($_SESSION['user'])) {  ?>
					<a href="<?php echo "/user/view/{$me['n_id']}/{$me['s_id']}"?>" class="userinfo"><?php
						if ($me['s_pic']) {
							?><img src="<?php echo htmlspecialchars($me['s_pic'])?>" style="padding-right:2px" /><?php
						} else {
							?><img src="/images/no-image.png" style="padding-right:2px" /><?php
						}
						?><span style="line-height:48px;"><?php echo htmlspecialchars($me['s_name']);?></span><?php
					?></a>
					<a href="/sitemap" class="sitemap"><i class="fa fa-bars"></i></a>
					<a href="/searchall" class="searchall"><i class="fa fa-search"></i></a>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
		<div id="total-header">
            <?php if (isset($_SESSION['user'])) { ?>
			<div id="userarea"><?php include "src/header/userarea.php"; ?></div>
            <?php } ?>
			<a id="back-to-main" href="./"></a><br />
		</div>
		<script type="text/javascript">prepareHeader();</script>
	<?php } ?>
	<div id="total-content"><?php if (function_exists("printContent")) { printContent(); } ?><div style="clear:both"></div></div>
</div>
