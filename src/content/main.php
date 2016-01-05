<?php function mainBigBoard($i1=true, $i2=true, $i3=true, $i4=true, $height=20, $nextline=false){ global $me, $board, $member; $accessible_categories=getUserMainBoards($me); $alist=$board->getArticleList($accessible_categories, false, 0, 0, 16); ?>
<table style="width:100%">
	<tr style="height:<?php echo $height?>px;">
		<?php if($i1){ ?>
		  <th style="width:140px;">분류</th>
		<?php } ?>
		<?php if($i2){ ?>
		  <th>제목</th>
		<?php } ?>
		<?php if($i3){ ?>
		  <th style="width:100px;">이름</th>
		<?php } ?>
		<?php if($i4){ ?>
		  <th style="width:60px;">날짜</th>
		<?php } ?>
	</tr>
	<?php foreach($alist as $a){
        $b_bold_title=($a[ 'n_flag']&0x8) && checkCategoryAccess($a[ 'n_cat'], "flag bold title");
        $b_no_comment=($a[ 'n_flag']&0x2) && checkCategoryAccess($a[ 'n_cat'], "flag no comment");
        $b_anonymous=($a[ 'n_flag']&0x4) && checkCategoryAccess($a[ 'n_cat'], "flag anonymous");
        $pretty_title=htmlspecialchars($a[ 's_title']);
        if($a[ 'n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment))
            $pretty_title.=" <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>" ; ?>
        <tr style="height:<?php echo $height?>px;">
		<?php if($i1){ ?>
		<td style="text-align:center;">
			<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']} ");?>" style="color:black;">
				<?php echo htmlspecialchars($a[ 'cat'][ 's_name']) ?>
			</a>
		</td>
		<?php } if($i2){ ?>
		<td>
			<?php if($nextline){ ?>
			<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?" font-weight:bold; ":" ";?>" title="<?php echo strip_tags($pretty_title)?>">
				<?php echo $pretty_title ?>
			</a>
			<?php }else{ ?>
			<div style="width:100%;height:<?php echo $height?>px;display:block;overflow:hidden;position:relative;">
				<div style="width:640px;display:block;position:absolute;left:0;top:1px;white-space: nowrap;">
					<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?" font-weight:bold; ":" ";?>" title="<?php echo strip_tags($pretty_title)?>">
						<?php echo $pretty_title ?>
					</a>
				</div>
			</div>
			<?php } ?>
		</td>
		<?php } ?>
		<?php if($i3){ ?>
		<td style="text-align:center;">
			<?php if($b_anonymous) echo "익명"; else{ $m=$member->getMember($a['n_writer']); echo "<a href='/user/view/{$m[' n_id ']}/{$m['s_id ']}' style='color:black'>";
            putUserCard($m);
            echo "</a>"; } ?>
		</td>
		<?php } ?>
		<?php if($i4){ ?>
		<td style="text-align:center;">
			<?php echo date((time()-$a[ 'n_editdate']>=86400)?"y-m-d":"H:i:s", $a['n_editdate'])?>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
<?php } function printImportantBoard($i1=true, $i2=true, $i3=true, $i4=true, $height=18){ global $mysqli, $board, $member; $res=$mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"); ?>
<table style="width:100%">
	<tr style="height:<?php echo $height?>px;">
		<?php if($i1){ ?>
		<th style="width:140px;">분류</th>
		<?php } ?>
		<?php if($i2){ ?>
		<th>제목</th>
		<?php } ?>
		<?php if($i3){ ?>
		<th style="width:120px;">이름</th>
		<?php } ?>
		<?php if($i4){ ?>
		<th style="width:60px;">날짜</th>
		<?php } ?>
	</tr>
	<?php while ($row=$ res->fetch_array(MYSQLI_ASSOC)){ $a=$board->getArticle($row['n_article']); $a['cat']=$board->getCategory($a['n_cat']); $writer=$member->getMember($a['n_writer']); $b_bold_title=($a['n_flag']&0x8) && checkCategoryAccess($a['n_cat'], "flag bold title"); $b_no_comment=($a['n_flag']&0x2) && checkCategoryAccess($a['n_cat'], "flag no comment"); $b_anonymous=($a['n_flag']&0x4) && checkCategoryAccess($a['n_cat'], "flag anonymous"); ?>
	<tr style="height:<?php echo $height?>px;">
		<?php if($i1){ ?>
		<td style="text-align:center;">
			<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']} ");?>" style="color:black;">
				<?php echo htmlspecialchars($a[ 'cat'][ 's_name']) ?>
			</a>
		</td>
		<?php } ?>
		<?php if($i2){ ?>
		<td>
			<div style="width:100%;display:block;overflow:hidden">
				<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black; <?php echo $b_bold_title?"font-weight:bold; ":"";?>">
                    <?php
                    echo htmlspecialchars($a['s_title']);
                    if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
                        echo " <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
                    ?>
                </a>
			</div>
		</td>
		<?php } ?>
		<?php if($i3){ ?>
		<td style="text-align:center;">
			<?php if($b_anonymous) echo "익명"; else{ $m=$member->getMember($a['n_writer']); echo "<a href='/user/view/{$m[' n_id ']}/{$m['s_id ']}' style='color:black'>";
            putUserCard($m);
            echo "</a>"; } ?>
		</td>
		<?php } ?>
		<?php if($i4){ ?>
		<td style="text-align:center;">
			<?php echo date((time()-$a[ 'n_editdate']>=86400)?"y-m-d":"H:i:s", $a['n_editdate'])?>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
<?php } function printFreeBoard($i1=true, $i2=true, $i3=true, $i4=true, $height=7){ global $mysqli, $board, $member; $res=$mysqli->query("SELECT * FROM kmlaonline_board_data WHERE n_cat=139 and n_parent is null ORDER BY n_id DESC LIMIT 7"); ?>
<table style="width:100%">
	<tr style="height:<?php echo $height?>px;">
		<?php if($i1){ ?>
		<th style="width:140px;">분류</th>
		<?php } ?>
		<?php if($i2){ ?>
		<th>제목</th>
		<?php } ?>
		<?php if($i3){ ?>
		<th style="width:120px;">이름</th>
		<?php } ?>
		<?php if($i4){ ?>
		<th style="width:60px;">날짜</th>
		<?php } ?>
	</tr>
	<?php while ($row=$ res->fetch_array(MYSQLI_ASSOC)){ $a=$board->getArticle($row['n_id']); $a['cat']=$board->getCategory($a['n_cat']); $writer=$member->getMember($a['n_writer']); $b_bold_title=($a['n_flag']&0x8) && checkCategoryAccess($a['n_cat'], "flag bold title"); $b_no_comment=($a['n_flag']&0x2) && checkCategoryAccess($a['n_cat'], "flag no comment"); $b_anonymous=($a['n_flag']&0x4) && checkCategoryAccess($a['n_cat'], "flag anonymous"); ?>
	<tr style="height:<?php echo $height?>px;">
		<?php if($i1){ ?>
		<td style="text-align:center;">
			<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']} ");?>" style="color:black;">
				<?php echo htmlspecialchars($a[ 'cat'][ 's_name']) ?>
			</a>
		</td>
		<?php } ?>
		<?php if($i2){ ?>
		<td>
			<div style="width:100%;display:block;overflow:hidden">
				<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?" font-weight:bold; ":" ";?>">
                    <?php
                    echo htmlspecialchars($a['s_title']);
                    if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
                        echo " <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
                    ?>
                </a>
			</div>
		</td>
		<?php } ?>
		<?php if($i3){ ?>
		<td style="text-align:center;">
			<?php if($b_anonymous) echo "익명"; else{ $m=$member->getMember($a['n_writer']); echo "<a href='/user/view/{$m[' n_id ']}' style='color:black'>";
            putUserCard($m);
            echo "</a>"; } ?>
		</td>
		<?php } ?>
		<?php if($i4){ ?>
		<td style="text-align:center;">
			<?php echo date((time()-$a[ 'n_editdate']>=86400)?"y-m-d":"H:i:s", $a['n_editdate'])?>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
<?php } function printCategorySmall($prefix="" , $postfix="" , $height=18){ global $me, $board, $member; $accessible_categories=getCategoriesWithFixes($prefix,$postfix); if(count($accessible_categories)==0) $alist=array(); else $alist=$board->getArticleList($accessible_categories, false, 0, 0, 20); ?>
<table style="width:100%">
	<tr style="height:<?php echo $height?>px;">
		<th style="width:140px;">분류</th>
		<th>제목</th>
	</tr>
	<?php foreach($alist as $a){ $b_bold_title=($a[ 'n_flag']&0x8) && checkCategoryAccess($a[ 'n_cat'], "flag bold title"); $b_no_comment=($a[ 'n_flag']&0x2) && checkCategoryAccess($a[ 'n_cat'], "flag no comment"); $b_anonymous=($a[ 'n_flag']&0x4) && checkCategoryAccess($a[ 'n_cat'], "flag anonymous"); ?>
	<tr style="height:<?php echo $height?>px;">
		<td>
			<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']} ");?>" style="color:black;">
				<?php echo htmlspecialchars($a[ 'cat'][ 's_name']) ?>
			</a>
		</td>
		<td>
			<div style="width:100%;display:block;overflow:hidden">
				<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?" font-weight:bold; ":" ";?>">
                    <?php
                    echo htmlspecialchars($a['s_title']);
                    if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
                        echo " <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
                    ?>
                </a>
			</div>
		</td>
	</tr>
	<?php } ?>
</table>
<?php } function printContent(){ global $is_mobile; if($is_mobile) printContentMobile(); else printContentPc(); } function printContentPc(){ global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board; ?>
<div style="padding:5px;">
	<table style="width:100%;" class="notableborder-direct">
		<tr>
			<td style="width:600px;">
				<div class="main-block">
					<iframe src="/fetch_kmla_announcements.php" style="border:0;width:100%;height:67px;margin:0;padding:5px 0 2px 7px;overflow:hidden;border-box" scrolling="no" seamless="seamless" frameBorder="0" allowtransparency="true"></iframe>
				</div>
			</td>
			<td style="vertical-align:top">
				<?php printEverydayLinks( "display:block;padding:3px;float:right;", "display:block;padding:3px;clear:right;float:right;text-align:right"); ?>
			</td>
		</tr>
	</table>
	<table style="width:100%" class="notableborder-direct">
		<tr style="vertical-align:top">
			<td>
				<div class="main-block autoheight-0" style="min-height:388px;overflow:auto;">
					<div class="main-block-title">
						꼭 보세요
						<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
							<a href="/util/important">
                                신청목록 보기
                                <?php
                                $res=$mysqli->query("SELECT count(*) FROM kmlaonline_important_notices_table WHERE n_state=0");
                                $res=$res->fetch_array();
                                if($res[0]>0) echo " ({$res[0]})";
                                ?>
                            </a>
						</div>
					</div>
					<?php printImportantBoard(true,true,true,true); ?>
				</div>
			</td>
			<td style="width:240px;">
				<div class="main-block" style="height:120px;margin-bottom:6px;">
					<div class="main-block-title">
						<img src="/theme/dev/birthday.png" style="width:32px;" /> 생일!
					</div>
					<div style="display:block;height:7px;"></div>
					<div style="overflow:auto;display:block;height:80px;">
						<?php $minWave=date( "Y")-1997; $births=0; foreach($member->listMembersBirth(date("n"), date("j")) as $val){ if($val['n_level']>=$minWave){ $births++; echo "<a href='/user/view/{$val[' n_id ']}/{$val['s_id ']}'>";
									echo "<div style=\"float:left;display:block;padding:3px;\">";
									putUserCard($val);
									echo "</div>";
									echo "</a>"; } } if($births==0){ echo "생일인 재학생이 없습니다."; } ?>
					</div>
				</div>
				<div class="main-block gradient autoheight-0" style="position:relative;">
					<div class="main-block-title">
						<img src="/theme/dev/food.png" style="width:32px;" /> 식단!
						<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
							<a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
							<a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
							<a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
						</div>
					</div>
					<div style="clear:right;display:block;height:7px;"></div>
					<div style="text-align:center">
						<?php $curYear=date( "Y"); $curMonth=date( "n"); $curDay=date( "j"); if($is_morning && date( "H")>=22){ $curYear = date("Y", strtotime("+1 day")); $curMonth = date("m", strtotime("+1 day")); $curDay = date("d", strtotime("+1 day")); } $query="SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND n_day=$curDay"; if($res=$mysqli->query($query)){ while ($row = $res->fetch_array(MYSQLI_ASSOC)){ $scheduleData[$row['s_mode']]=$row['s_data']; } $res->close(); if($mysqli->more_results())$mysqli->next_result(); } echo "
						<div style='font-weight:bold;font-size:11pt;padding:4px;'>{$curYear}년 {$curMonth}월 {$curDay}일</div>"; ?>
						<div id="food-breakfast" class="morning">
							<?php echo isset($scheduleData[ 'food:0'])?nl2br($scheduleData[ 'food:0']): "<span style='color:#DDD'>(입력되지 않음)</span>"; ?>
						</div>
						<div id="food-lunch" class="afternoon">
							<?php echo isset($scheduleData[ 'food:1'])?nl2br($scheduleData[ 'food:1']): "<span style='color:#DDD'>(입력되지 않음)</span>"; ?>
						</div>
						<div id="food-dinner" class="night">
							<?php echo isset($scheduleData[ 'food:2'])?nl2br($scheduleData[ 'food:2']): "<span style='color:#DDD'>(입력되지 않음)</span>"; ?>
						</div>
					</div>
					<div style="position:absolute;right:0;bottom:0;text-align:right;padding:3pt;">
						<a href="/util/schedule?<?php echo " year=$curYear&amp;month=$curMonth&amp;mode=food:0 "?>">모두 보기</a>
					</div>
					<div style="clear:both"></div>
				</div>
			</td>
		</tr>
	</table>
	<table style="width:100%" class="notableborder-direct">
		<td>
			<div class="main-block">
				<div class="main-block-title">
					큼라보드

					<?php if(isUserPermitted($me[ 'n_id'], "kmlaboard_changer")){ ?>
					<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
						<a href="/util/kmlaboard">수정하기</a>
					</div>
					<?php } ?>
				</div>
				<div style="padding:5px;">
					<?php $dat="" ; if(file_exists( "data/kmlaboard.txt") && filesize( "data/kmlaboard.txt")>0){ $dat=file_get_contents("data/kmlaboard.txt"); } filterContent(nl2br(strip_tags($dat,"
					<b><big><small><i><u><strong><strike><a><font><img><q><s><sub><sup>")));
						?>
                </div>
            </div>
        </td>
    </table>
    <table style="width:100%" class="notableborder-direct">
        <td>
            <div class="main-block">
                <div class="main-block-title">
                    자유게시판
                    <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
                        <a href="/board/commu">더보기</a>
                    </div>
                </div>
                <?php printFreeBoard(true,true,true,true); ?>
            </div>
        </td>
    </table>
    <table style="width:100%" class="notableborder-direct">
        <tr style="vertical-align:top">
            <td style="width:56%;">
                <div class="main-block gradient autoheight-1" style="overflow:auto">
                    <div class="main-block-title">
                        내 게시판
                        <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/special:list-mine">모두 보기</a> | <a href="/user/settings?display=1">항목 바꾸기</a></div>
                    </div>
                    <?php mainBigBoard(true, true, true, false); ?>
                </div>
            </td>
            <td>
                <div class="main-block gradient autoheight-1" style="overflow:auto">
                    <div class="main-block-title">
                        <img src="/theme/dev/kmlacafe.png" style="width:24px;vertical-align:bottom;margin-bottom:2px;" /> 큼라 카페
                        <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/site_kmlacafe">모두 보기</a></div>
                    </div>
                    <?php
                        $att=array();
                        $cat=$board->getCategory(false,"site_kmlacafe");
                        $dir="./data/article_thumbs/";
                        if ($dh = opendir($dir)) {
                            $arrrr=array();
                            while (($file = readdir($dh)) !== false) {
                                $arrrr[]=$file;
                            }
                            sort($arrrr);
                            $arrrr=array_reverse($arrrr);
                            closedir($dh);
                        }
                        for($i=0;$i<2;$i++){
                            $aaaaaa=intval($arrrr[$i]);
                            $ar=$board->getArticle($aaaaaa);
                            $a=array();
                            $a['s_ort']=htmlspecialchars($ar['s_title']) . ($ar['n_comments']>0?" <span style='font-size:9pt;color:#008800'>[" . $ar['n_comments'] . "]</span>":"");
                            $a['n_ort']=0;
                            $a['path']="/data/article_thumbs/$aaaaaa.png";
                            $a['n_parent']=$aaaaaa;
                            $att[]=$a;
                        }

                        $articles=$board->getArticleList(array($cat['n_id']), false, 0);

                        $width=160;
                        $i=0;
                    ?>
                    <div style="width:160px;float:left">
                        <?php for($i=0;$i<2 && $i<count($att);$i++){ $v=$att[$i]; ?>
                            <a href="/board/<?php echo $cat['s_id'] ?>/view/<?php echo $v['n_parent']?>">
                                <img src="<?php echo $v['path'] ?>" style="width:160px;height:160px;" />
                                <div style="text-align:center;width:160px;"><?php echo $v['s_ort']; ?></div>
                            </a>
                        <?php } ?>
                    </div>
                    <div style="margin-left:160px;padding:5px;">
                        <?php foreach($articles as $a){
                            $b_bold_title=($a['n_flag']&0x8) && checkCategoryAccess($a['n_cat'], "flag bold title");
                            $b_no_comment=($a['n_flag']&0x2) && checkCategoryAccess($a['n_cat'], "flag no comment");
                            $b_anonymous=($a['n_flag']&0x4) && checkCategoryAccess($a['n_cat'], "flag anonymous");
                        ?>
                        <div style="height:18px;overflow:hidden;">
                            <a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?" font-weight:bold; ":" ";?>">
                                <?php
                                echo htmlspecialchars($a['s_title']);
                                if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
                                    echo " <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
                                ?>
                            </a> -
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
                        </div>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </table>
        <div class="main-block">
            <div class="main-block-title">
                갤러리
                <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/all_gallery">모두 보기</a></div>
            </div>
            <?php printGallery(); ?>
        </div>
        </div>
        <?php
        insertOnLoadScript('
            var minHeight=0;
            $(".autoheight-0").each(function(i){
                if($(this).height()>minHeight) minHeight=$(this).height()+$(this).position().top;
            }).each(function(i){
                $(this).height(minHeight-$(this).position().top);
            })
            minHeight=0;
            $(".autoheight-1").each(function(i){
                if($(this).height()>minHeight) minHeight=$(this).height();
            }).height(minHeight);
        ');
}
function printGallery($cnt=24){
	global $board;
	$att=array();
	$cat=$board->getCategory(false,"all_gallery");
	foreach($board->getArticleList(array($cat['n_id'])) as $ar){
		foreach($board->getAttachments(false, $ar['n_id']) as $a){
			$att[]=$a;
		}
	}
	$width=160;
	$i=0;
	?>
    <div style="padding:3px;">
        <div style="height:<?php echo $width?>px;position:relative;overflow:hidden;display:block;">
            <div style="width:<?php echo ($width+3)*count($att)?>px;height:<?php echo $width?>px;position:absolute;display:block;left:0;overflow:hidden;" id="main_scrollpreviewcont">
                <?php foreach($att as $v){ if($i++>=$cnt) break; ?>
                    <div style="width:<?php echo $width?>px;height:<?php echo $width?>px;display:block;float:left;padding-right:3px;">
                        <a href="/board/<?php echo $cat['s_id'] ?>/view/<?php echo $v['n_parent']?>">
                        <img src="<?php echo "/files/bbs/{$cat['n_id']}/{$v['n_parent']}/{$v['n_id']}/{$v['s_key']}/sizemode_160/{$v['s_name']}" ?>" style="width:160px;height:160px;" />
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
	insertOnLoadScript("main_scrollAdInit();");
}
function printEverydayLinks($lay1="display:block;padding:3px;float:left;", $lay2="display:block;padding:3px;float:right;text-align:right", $lay3="display:none"){
	global $board;
	echo "<div style=\"$lay1\">";
	// 그날그날: 택배, 선도, 혼정, 잔반
	$i=0;
	foreach(array("everyday_parcel"=>"택배", "everyday_guidance"=>"선도", /*"everyday_honjung"=>"혼정", */"leftover"=>"잔반") as $k=>$v){
		echo $i++>0?" | ":"";
		$cat=$board->getCategory(false,$k);
		$a=$board->getArticleList(array($cat['n_id']), false, false, 0, 1);
		if(count($a) == 0){
			echo "<a href='/board/$k' id='nav_everyday' style='color:gray'>$v 없음</a>";
		} else {
			$a = $a[0];
			$bold=(time() - $a['n_writedate']<43200)?"font-weight:bold;":"";
			echo "<a href=\"/board/$k/view/{$a['n_id']}\">{$v} <span style=\"$bold\">(".date("m월 d일", $a['n_writedate']).")</span></a>";
		}
	}
	echo "</div>";
	echo "<div style=\"$lay2\">";
	echo "<a href=\"/board/commu\">자유게시판 </a> | ";
	echo "<a href=\"/board/student_legislative/view/433333\">학교자료실</a> | ";
	echo "<a href=\"/board/department_environment\">환경부</a>";
	echo "<br>";
	echo "<a href=\"/board/student_mpt\">MPT</a> | ";
	echo "<a href=\"/board/student_ambassador\">대외홍보단</a>  | ";
	echo "<a href=\"/util/lectureroom\">공동강의실 신청 </a>";	
	echo "</div>";
	echo "<div style=\"$lay3\">";
	echo "<a href=\"http://www.minjok.hs.kr/\">학교 홈페이지</a> | ";
	echo "<a href=\"http://www.minjok.hs.kr/members/\">인트라넷</a> | ";
	echo "<a href=\"/user/message?compose_to=3\">오류 신고 및 문의</a>";	
	echo "</div>";
	echo "<div style='clear:both'></div>";
}
function printContentMobile(){
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
	?>
	<div style="padding:5px;">
		<?php printEverydayLinks(); ?>
			<div class="main-block">
				<iframe src="/fetch_kmla_announcements.php?lines=2" style="border:0;width:100%;height:73px;margin:0;padding:5px 0 2px 7px;overflow:hidden;box-sizing:border-box" scrolling="no" seamless="seamless" frameBorder="0" allowtransparency="true"></iframe>
			</div>
			<div class="main-block gradient">
				<div class="main-block-title">
					꼭 보세요
					<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
						<a href="/util/important">
                            신청목록 보기
                            <?php
                                $res=$mysqli->query("SELECT count(*) FROM kmlaonline_important_notices_table WHERE n_state=0");
                                $res=$res->fetch_array();
                                if($res[0]>0) echo " ({$res[0]})";
                            ?>
					   </a>
					</div>
				</div>
				<?php printImportantBoard(false,true,true,false, 24); ?>
			</div>
			<div class="main-block">
				<div class="main-block-title">
					내 게시판
					<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/special:list-mine">모두 보기</a> | <a href="/user/settings">항목 바꾸기</a></div>
				</div>
				<?php mainBigBoard(false,true,true,false, 24, true); ?>
			</div>
			<div class="main-block gradient" style="margin-top:6px;position:relative;">
				<div class="main-block-title">
					<img src="/theme/dev/food.png" style="width:32px;" /> 식단!
					<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
						<a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
						<a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
						<a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
					</div>
				</div>
				<div style="clear:both;display:block;height:7px;"></div>
				<div style="text-align:center">
					<?php
						$curYear=date("Y"); $curMonth=date("n"); $curDay=date("j");
						if($is_morning && date("H")>=22){
							$curYear = date("Y", strtotime("+1 day"));
							$curMonth = date("m", strtotime("+1 day"));
							$curDay = date("d", strtotime("+1 day"));
						}
						$query="SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND n_day=$curDay";
						if($res=$mysqli->query($query)){
							while ($row = $res->fetch_array(MYSQLI_ASSOC)){
								$scheduleData[$row['s_mode']]=$row['s_data'];
							}
							$res->close();
							if($mysqli->more_results())$mysqli->next_result();
						}
						?>
						<div id="food-breakfast" class="morning">
							<?php echo isset($scheduleData['food:0'])?nl2br($scheduleData['food:0']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
						<div id="food-lunch" class="afternoon">
							<?php echo isset($scheduleData['food:1'])?nl2br($scheduleData['food:1']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
						<div id="food-dinner" class="night">
							<?php echo isset($scheduleData['food:2'])?nl2br($scheduleData['food:2']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
				</div>
				<div style="position:absolute;right:0;bottom:0;text-align:right;padding:3pt;">
					<a href="/util/schedule?<?php echo " year=$curYear&amp;month=$curMonth&amp;mode=food:0 "?>">모두 보기</a>
				</div>
			</div>
			<div class="main-block">
				<div class="main-block-title">
					큼라보드
					<?php if(isUserPermitted($me['n_id'], "kmlaboard_changer")){ ?>
						<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
							<a href="/util/kmlaboard">수정하기</a>
						</div>
                    <?php } ?>
				</div>
				<div style="padding:5px;">
					<?php
					$dat="";
					if(file_exists("data/kmlaboard.txt") && filesize("data/kmlaboard.txt")>0){
						$dat=file_get_contents("data/kmlaboard.txt");
					}
					filterContent(nl2br(strip_tags($dat,"<b><big><small><i><u><strong><strike><a><font><img><q><s><sub><sup><marquee>")));
					?>
				</div>
			</div>
			<div class="main-block">
				<div class="main-block-title">
					<img src="/theme/dev/birthday.png" style="width:32px;" /> 생일!
				</div>
				<div style="clear:both;display:block;height:7px;"></div>
				<div style="overflow:auto;display:block;height:80px;">
					<?php
					$minWave=date("Y")-1997;
					$births=0;
					foreach($member->listMembersBirth(date("n"), date("j")) as $val){
						if($val['n_level']>=$minWave){
							$births++;
							echo "<a href='/user/view/{$val['n_id']}/{$val['s_id']}'>";
							echo "<div style=\"float:left;display:block;padding:3px;\">";
							putUserCard($val);
							echo "</div>";
							echo "</a>";
						}
					}
					if($births==0){
						echo "생일인 재학생이 없습니다.";
					}
					?>
                    <div style="clear:both;"></div>
				</div>
			</div>
			<div class="main-block">
				<div class="main-block-title">
					갤러리
					<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/all_gallery">모두 보기</a></div>
				</div>
				<?php printGallery(16); ?>
			</div>
			<div class="main-block gradient">
				<div class="main-block-title">
					<img src="/theme/dev/kmlacafe.png" style="width:24px;vertical-align:bottom;margin-bottom:2px;" /> 큼라 카페
					<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/site_kmlacafe">모두 보기</a></div>
				</div>
				<?php
				$att=array();
				$cat=$board->getCategory(false,"site_kmlacafe");
				$ar=$board->getArticle(426858);
				foreach($board->getAttachments(false, $ar['n_id']) as $a){
					$a['s_ort']=htmlspecialchars($ar['s_title']) . ($ar['n_comments']>0?" <span style='font-size:9pt;color:#008800'>[" . $ar['n_comments'] . "]</span>":"");
					$a['n_ort']=0;
					$att[]=$a;
				}
				foreach($articles=$board->getArticleList(array($cat['n_id']), false, 0) as $kk=>$ar){
					if($ar['n_id']==426858){ // Do not show article
						continue;
					}
					if($ar['n_id']==426861){ // Do not show images
						continue;
					}
					continue;
					foreach($board->getAttachments(false, $ar['n_id']) as $a){
						$a['s_ort']=htmlspecialchars($ar['s_title']) . ($ar['n_comments']>0?" <span style='font-size:9pt;color:#008800'>[" . $ar['n_comments'] . "]</span>":"");
						$a['n_ort']=$kk;
						$att[]=$a;
						break;
					}
				}
				$width=160;
				$i=0;
				foreach($articles as $a){
					$b_bold_title=($a['n_flag']&0x8) && checkCategoryAccess($a['n_cat'], "flag bold title");
					$b_no_comment=($a['n_flag']&0x2) && checkCategoryAccess($a['n_cat'], "flag no comment");
					$b_anonymous=($a['n_flag']&0x4) && checkCategoryAccess($a['n_cat'], "flag anonymous");
					?>
					<div style="height:18px;">
						<a href="<?php echo htmlspecialchars(" /board/{$a[ 'cat'][ 's_id']}/view/ " . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?" font-weight:bold; ":" ";?>">
							<?php
							echo htmlspecialchars($a['s_title']);
							if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
								echo " <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
							?>
						</a> -
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
					</div>
                <?php } ?>
			</div>
	</div>
	<?php
}
?>
