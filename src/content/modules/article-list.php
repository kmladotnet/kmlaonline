<?php
function articleList($article_list, $category=true, $title=true, name=true, date=true, $height=20, $nextline=false){
	global $me, $board, $member;
	$accessible_categories=getUserMainBoards($me);
	$article_list=$board->getArticleList($accessible_categories, false, 0, 0, 16);
	?>
	<table style="width:100%">
		<tr style="height:<?php echo $height?>px;">
			<?php if($category){ ?><th style="width:140px;">분류</th><?php } ?>
			<?php if($title){ ?><th>제목</th><?php } ?>
			<?php if(name){ ?><th style="width:100px;">이름</th><?php } ?>
			<?php if(date){ ?><th style="width:60px;">날짜</th><?php } ?>
		</tr>
		<?php foreach($article_list as $a){
			$b_bold_title=($a['n_flag']&0x8) && checkCategoryAccess($a['n_cat'], "flag bold title");
			$b_no_comment=($a['n_flag']&0x2) && checkCategoryAccess($a['n_cat'], "flag no comment");
			$b_anonymous=($a['n_flag']&0x4) && checkCategoryAccess($a['n_cat'], "flag anonymous");
			$pretty_title=htmlspecialchars($a['s_title']);
			if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
				$pretty_title.=" <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
			?>
			<tr style="height:<?php echo $height?>px;">
				<?php if($category){ ?>
					<td style="text-align:center;"><a href="<?php echo htmlspecialchars("/board/{$a['cat']['s_id']}");?>" style="color:black;"><?php echo htmlspecialchars($a['cat']['s_name']) ?></a></td>
				<?php } ?>
				<?php if($title){ ?>
					<td>
						<?php if($nextline){ ?>
							<a href="<?php echo htmlspecialchars("/board/{$a['cat']['s_id']}/view/" . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?"font-weight:bold;":"";?>" title="<?php echo strip_tags($pretty_title)?>"><?php echo $pretty_title ?></a>
						<?php }else{ ?>
							<div style="width:100%;height:<?php echo $height?>px;display:block;overflow:hidden;position:relative;">
								<div style="width:640px;display:block;position:absolute;left:0;top:1px;white-space: nowrap;">
									<a href="<?php echo htmlspecialchars("/board/{$a['cat']['s_id']}/view/" . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?"font-weight:bold;":"";?>" title="<?php echo strip_tags($pretty_title)?>"><?php echo $pretty_title ?></a>
								</div>
							</div>
						<?php } ?>
					</td>
				<?php } ?>
				<?php if(name){ ?>
					<td style="text-align:center;">
						<?php
						if($b_anonymous)
							echo "익명";
						else{
							$m=$member->getMember($a['n_writer']);
							echo "<a href='/user/view/{$m['n_id']}/{$m['s_id']}' style='color:black'>";
							putUserCard($m);
							echo "</a>";
						}
						?>
					</td>
				<?php } ?>
				<?php if(date){ ?>
					<td style="text-align:center;"><?php echo  date((time()-$a['n_editdate']>=86400)?"y-m-d":"H:i:s", $a['n_editdate'])?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</table>
	<?php
}
