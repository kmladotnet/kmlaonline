<?php
function articleList($article_list, $category=true, $title=true, $name=true, $date=true, $maxlen = 9999, $height=28, $nextline=false){
	global $me, $board, $member;
	?>
	<table style="width:100%;overflow:auto;">
		<tr style="height:<?php echo $height?>px;">
			<?php if($category){ ?><th style="width: 140px; text-align: center;">분류</th><?php } ?>
			<?php if($title){ ?><th>제목</th><?php } ?>
			<?php if($name){ ?><th style="width: 100px; text-align: center;">이름</th><?php } ?>
			<?php if($date){ ?><th style="width: 70px; text-align: center;">날짜</th><?php } ?>
		</tr>
		<?php
        $num = 0;
        foreach($article_list as $a){
            if(++$num > $maxlen)
                break;
            if(!array_key_exists("cat", $a)) {
                if(!array_key_exists("n_article", $a)) {
                    $a=$board->getArticle($a['n_id']);
                } else {
                    $a=$board->getArticle($a['n_article']);
                }
                $a['cat']=$board->getCategory($a['n_cat']);
            }
			$b_bold_title=($a['n_flag']&0x8);
			$b_no_comment=($a['n_flag']&0x2);
			$b_anonymous=($a['n_flag']&0x4);
            $has_comments = ($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment));
            $votes = get_votes($a['n_id']);
            $classes = "article-list-title";
            if($has_comments) {
                if($votes != 0) {
                    $classes .= " has-both";
                } else {
                    $classes .= " has-comments";
                }
            } else if($votes != 0) {
                $classes .= " has-votes";
            } else {
                $classes .= " has-none";
            }
			$pretty_title='<div class="'.$classes.'">'.formatTitle($a['s_title']).'</div>';
            if($votes != 0)
                $pretty_title .= ' <span class="'.($votes > 0 ? 'positive' : 'negative').'-vote-num">'.(($votes > 0) ? '+' : '').$votes.'</span>';
			if($has_comments)
				$pretty_title .= " <span class='comment-num'>{$a['n_comments']}</span>";
			?>
			<tr style="height:<?php echo $height?>px;">
				<?php if($category){ ?>
					<td style="text-align:center;">
                        <a href="<?php echo htmlspecialchars("/board/{$a['cat']['s_id']}");?>" style="color:black;"><?php echo htmlspecialchars($a['cat']['s_name']) ?>
                        </a>
                    </td>
				<?php } ?>
				<?php if($title){ ?>
					<td>
                        <div style="position:relative">
                            <div class="article-list-item">
                                <a href="<?php echo htmlspecialchars("/board/{$a['cat']['s_id']}/view/" . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?"font-weight:bold;":"";?>"><?php echo $pretty_title ?>
                                </a>
                            </div>
                            <span>&nbsp;</span>
                        </div>
					</td>
				<?php } ?>
				<?php if($name){ ?>
					<td style="text-align:center;">
						<?php
						if($b_anonymous)
							echo "익명";
						else{
							$m=$member->getMember($a['n_writer']);
							echo "<a href='/user/view/{$m['n_id']}' style='color:black'>";
							putUserCard($m);
							echo "</a>";
						}
						?>
					</td>
				<?php } ?>
				<?php if($date){ ?>
					<td style="text-align:center;"><?php echo  date((time()-$a['n_editdate']>=86400)?"y-m-d":"H:i:s", $a['n_editdate'])?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</table>
	<?php
}
