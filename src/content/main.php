<?php
function printContent(){
	global $is_mobile;
	if($is_mobile) printContentMobile();
	else printContentPc();
}
function printContentPc(){
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
	?>
    <h1>공사중!!!!!</h1>
	<div style="padding:5px;">
		<table style="width:100%;" class="notableborder-direct">
			<tr>
				<td style="width:600px;">
					<div class="main-block">
						<iframe src="/fetch_kmla_announcements.php" style="border:0;width:100%;height:67px;margin:0;padding:5px 0 2px 7px;overflow:hidden;border-box" scrolling="no" seamless="seamless" frameBorder="0" allowtransparency="true"></iframe>
					</div>
				</td>
				<td style="vertical-align:top">
					<?php printEverydayLinks("display:block;padding:3px;float:right;", "display:block;padding:3px;clear:right;float:right;text-align:right"); ?>
				</td>
			</tr>
		</table>
        <div class="grid-stack">
            <div class="grid-stack-item"
                data-gs-x="0" data-gs-y="0"
                data-gs-width="10" data-gs-height="6">
                    <div class="grid-stack-item-content">
                        <div class="main-block">
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
                            <?php
                                require_once("modules/article-list.php");
                                articleList($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"), true,true,true,true);
                            ?>
                        </div>
                    </div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="10" data-gs-y="0"
                data-gs-width="2" data-gs-height="2">
                    <div class="grid-stack-item-content">
                        <div class="main-block">
						<div class="main-block-title">
							<img src="/theme/dev/birthday.png" style="width:32px;" /> 생일!
						</div>
						<?php include("modules/birthday.php"); ?>
						</div>
					</div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="10" data-gs-y="2"
                data-gs-width="2" data-gs-height="4">
                    <div class="grid-stack-item-content">
                        <div class="main-block gradient">
                            <div class="main-block-title">
                                <img src="/theme/dev/food.png" style="width:32px;" /> 식단!
                                <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
                                    <a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
                                    <a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
                                    <a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
                                </div>
                            </div>
                        <?php include("modules/menu.php"); ?>
					   </div>
					</div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="0" data-gs-y="6"
                data-gs-width="12" data-gs-height="6">
                    <div class="grid-stack-item-content">
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
                                filterContent(nl2br(strip_tags($dat,"<b><big><small><i><u><strong><strike><a><font><img><q><s><sub><sup>")));
                                ?>
                            </div>
                        </div>
					</div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="0" data-gs-y="12"
                data-gs-width="12" data-gs-height="4">
                    <div class="grid-stack-item-content">
                        <div class="main-block">
                            <div class="main-block-title">
                                자유게시판
                                    <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
                                        <a href="/board/commu">더보기</a>
                                    </div>
                            </div>
                            <?php
                                require_once("modules/article-list.php");
                                articleList($mysqli->query("SELECT * FROM kmlaonline_board_data WHERE n_cat=139 and n_parent is null ORDER BY n_id DESC LIMIT 7"), true, true, true, true);
                            ?>
                        </div>
					</div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="0" data-gs-y="16"
                data-gs-width="7" data-gs-height="6">
                    <div class="grid-stack-item-content">
                        <div class="main-block gradient" style="overflow:auto">
                            <div class="main-block-title">
                                내 게시판
                                <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/special:list-mine">모두 보기</a> | <a href="/user/settings?display=1">항목 바꾸기</a></div>
                            </div>
                            <?php
                                require_once("modules/article-list.php");
                                $accessible_categories=getUserMainBoards($me);
                                articleList($board->getArticleList($accessible_categories, false, 0, 0, 16), true, true, true, false);
                            ?>
					   </div>
					</div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="7" data-gs-y="16"
                data-gs-width="5" data-gs-height="6">
                    <div class="grid-stack-item-content">
                        <div class="main-block gradient" style="overflow:auto">
                            <div class="main-block-title">
                                <img src="/theme/dev/kmlacafe.png" style="width:24px;vertical-align:bottom;margin-bottom:2px;" /> 큼라 카페
                                <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/site_kmlacafe">모두 보기</a></div>
                            </div>
                            <div style="padding:5px;">
                            <?php
                                require_once("modules/article-list.php");
                                $articles=$board->getArticleList(array($board->getCategory(false,"site_kmlacafe")['n_id']), false, 0, 0, 16);
                                articleList($articles, false, true, true, false);
                            ?>
                            </div>
                        </div>
					</div>
            </div>

            <div class="grid-stack-item"
                data-gs-x="0" data-gs-y="22"
                data-gs-width="12" data-gs-height="4">
                    <div class="grid-stack-item-content">
                        <div class="main-block">
                            <div class="main-block-title">
                                갤러리
                                <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/all_gallery">모두 보기</a></div>
                            </div>
                            <?php printGallery(); ?>
                        </div>
					</div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(function () {
        var options = {
            animate: true,
            vertical_margin: 4,
            min_width: 720,
            draggable: {
                handle: '.main-block-title',
            }
        };
        $('.grid-stack').gridstack(options);
    });
    </script>
	<?php
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
		if(count($a)==0){
			echo "<a href='/board/$k' id='nav_everyday' style='color:gray'>$v 없음</a>";
		}else{
			$a=$a[0];
			$bold=(time()-$a['n_writedate']<43200)?"font-weight:bold;":"";
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
			<?php
                require_once("modules/article-list.php");
                articleList($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"), false, true, true, false, 24);
            ?>
		</div>
		<div class="main-block">
			<div class="main-block-title">
				내 게시판
				<div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/special:list-mine">모두 보기</a> | <a href="/user/settings">항목 바꾸기</a></div>
			</div>
			<?php
                require_once("modules/article-list.php");
                $accessible_categories=getUserMainBoards($me);
                articleList($board->getArticleList($accessible_categories, false, 0, 0, 16), false, true, true, false, 24, true);
            ?>
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
            <?php include("modules/menu.php"); ?>
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
			<?php include("modules/birthday.php"); ?>
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
					<a href="<?php echo htmlspecialchars("/board/{$a['cat']['s_id']}/view/" . $a['n_id'])?>" style="color:black;<?php echo $b_bold_title?"font-weight:bold;":"";?>">
						<?php
						echo htmlspecialchars($a['s_title']);
						if(($a['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment)))
							echo " <span style='font-size:9pt;color:#008800'>[{$a['n_comments']}]</span>";
						?>
					</a>
					-
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
