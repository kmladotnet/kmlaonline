<div>
	<?php if ($is_mobile) { ?>
		<div id="total-header">
			<div class="userarea">
				<?php
				$notic = $member->getNoticeCount($me['n_id']);
				if ($notic == 0)
					echo '<a class="backtomain" href="/"><img id="menu-logo-image-2" src="/images/logo-s.png" alt="KMLAONLINE" style="width: 20px; height: 20px; margin: 6px; margin-top: 4px;"> KMLA Online</a>';
				else
					echo '<a class="backtomain" href="/user/notification"><img id="menu-logo-image-2" src="/images/logo-s.png" alt="KMLAONLINE" style="width: 20px; height: 20px; margin: 6px; margin-top: 4px;"> '.$notic.' 알림</a>';
				?>
				<?php if (isset($_SESSION['user'])) {  ?>
					<a href="<?php echo "/user/view/{$me['n_id']}/{$me['s_id']}"?>" class="userinfo"><?php
						if ($me['s_pic']) {
							?><img src="<?php echo htmlspecialchars($me['s_pic'])?>" style="padding-right:2px" /><?php
						} else {
							?><img src="/images/no-profile.png" style="padding-right:2px" /><?php
						}
						?><span style="line-height:48px;"><?php echo htmlspecialchars($me['s_name']);?></span><?php
					?></a>
					<a href="/sitemap" class="sitemap"><i class="fa fa-bars"></i></a>
					<a href="/searchall" class="searchall"><i class="fa fa-search"></i></a>
				<?php } ?>
			</div>
		</div>
	<?php } else if($_SERVER["REQUEST_URI"] !== '/util/library'){
        if(!(!!$me && getTheme($me)['hidedasan'])) { ?>
            <div id="total-header" style="background: url('/images/big-logo-chuseok2.png') no-repeat;" <?php if($april_fools_2) echo 'style="height:311px; background: url(/images/bamboozle.png) no-repeat;"'; ?>>
                <?php if (isset($_SESSION['user'])) { ?>
                    <div class="userarea"><?php include "src/header/userarea.php"; ?></div>
                <?php } ?>
                <a id="back-to-main" href="/"></a><br />
            </div>
        <?php }} ?>
		<script type="text/javascript">prepareHeader();</script>
	<div id="total-content">
        <?php if (function_exists("printContent")) { printContent(); } ?>
        <div style="clear:both"></div>
    </div>
</div>
