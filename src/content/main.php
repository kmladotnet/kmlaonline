<?php
require_once('modules/module.php');
function printContent(){
	global $is_mobile;
	if($is_mobile) printContentMobile();
	else printContentPc();
}
function printContentPc(){
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board, $user;
	?>

    <!-- gridstack -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.3/gridstack.min.js"></script>

	<div style="padding:6px; min-height: 400px">
        <div style="padding: 4px; margin-bottom: 4px;">
            <button type="button" id="main-edit-button" class="btn btn-primary" onclick="toggleLayoutEditing();">편집 모드 시작</button>
            <?php printEverydayLinks(); ?>
            <div id="main-edit-pane" style="margin-top: 6px; display:none">
                <div>
                    <i class="fa fa-exclamation-triangle"></i> 편집 모드를 종료하거나 레이아웃 저장 버튼을 누르기 전엔 레이아웃이 저장되지 않습니다.
                </div>
                <div class="btn-group" style="margin-top: 4px">
                    <select id="example-layout" class="selectpicker" data-style="btn-default" data-width="300px" data-size="10" title="예시 레이아웃 (먼저 백업하세요!)">
                        <option data-divider="true"></option>
                        <option value="colorful">알록달록</option>
                        <option value="warrior">키보드워리어</option>
                    </select>
                </div>
                <div class="btn-group" style="margin-top: 4px">
                    <select id="add-module" class="selectpicker" data-style="btn-default" data-size="10" title="패널 추가">
                        <option data-divider="true"></option>
                        <option value="important">중요 공지</option>
                        <option value="birthday">생일</option>
                        <option value="menu">식단</option>
                        <option value="kmlaboard">큼라보드</option>
                        <option value="article-list">게시판</option>
                        <option value="gallery">갤러리</option>
                        <option value="weather">날씨</option>
                        <option value="minjok-news">인트라넷 공지</option>
                    </select>
                </div>
                <div class="btn-group" style="margin-top: 4px">
                    <button type="button" class="btn btn-success" onclick="updateModules();"><i class="fa fa-floppy-o"></i> 레이아웃 저장</button>
                </div>
                <div class="btn-group" style="margin-top: 4px">
                    <button type="button" id="backup-layout" class="btn btn-primary" onclick="backupLayout();"><i class="fa fa-cloud-upload"></i> 백업</button>
                    <?php
                    if(file_exists("data/user/main_layout_backup/{$me['n_id']}.txt")) { ?>
                        <button type="button" id="restore-layout" class="btn btn-primary" onclick="restoreLayout();"><i class="fa fa-cloud-download"></i> 복구</button>
                    <?php } ?>
                </div>
                <div class="btn-group" style="margin-top: 4px">
                    <button type="button" class="btn btn-danger" onclick="cancelLayout();"><i class="fa fa-trash-o"></i> 모든 변경사항 취소</button>
                    <button type="button" class="btn btn-danger" onclick="resetMainLayout();"><i class="fa fa-times"></i> 초기화</button>
                </div>
            </div>
        </div>

        <div class="grid-stack">
            <?php
                $modules = array();
                if(file_exists("data/user/main_layout/{$me['n_id']}.txt")) {
                    $modules = json_decode(file_get_contents("data/user/main_layout/{$me['n_id']}.txt"), true);
                } else {
                    $layout = '[{"name":"weather","options":{"x":8,"y":2,"w":2,"h":4,"options":{"color":"default"}}},{"name":"birthday","options":{"x":8,"y":0,"w":2,"h":2,"options":{"color":"default"}}},{"name":"menu","options":{"x":10,"y":0,"w":2,"h":6,"options":{"color":"default","all-day":false}}},{"name":"important","options":{"x":0,"y":0,"w":8,"h":6,"options":{"color":"default","show-cat":true,"show-title":true,"show-name":true,"show-date":true}}},{"name":"kmlaboard","options":{"x":0,"y":6,"w":12,"h":6,"options":{"color":"default"}}},{"name":"minjok-news","options":{"x":0,"y":12,"w":5,"h":4,"options":{"color":"default"}}},{"name":"article-list","options":{"x":0,"y":16,"w":5,"h":4,"options":{"color":"default","cat":["77"],"num":"6","show-cat":false,"show-title":true,"show-name":true,"show-date":true,"title":"큼라 카페"}}}]';

                    $modules = json_decode($layout, true);
                    $my_articles = json_decode( <<<JSON
                    {
                      "name":"article-list",
                      "options":{
                         "x":5,
                         "y":12,
                         "w":7,
                         "h":8,
                         "options":{
                            "num": "15",
                            "title":"내 게시판"
                         }
                      }
                    }
JSON
                               , true);
                    $my_articles['options']['options']['cat'] = array_values(getUserMainBoards($me));
                    $modules[] = $my_articles;
                    $layout = json_encode($modules);
                    file_put_contents("data/user/main_layout/{$me['n_id']}.txt", $layout);
                }
                allModules($modules);
            ?>
        </div>
    </div>
    <script type="text/javascript">
    PNotify.prototype.options.delay = 2500;
    $(function () {
        var options = {
            animate: true,
            cell_height: 64,
            vertical_margin: 6,
            draggable: {
                handle: '.main-block-title',
            },
            resizable: {
                handles: 'se, sw'
            }
        };
        $('.grid-stack').gridstack(options);
        reloadAllModules();
        $('.grid-stack').data('gridstack').disable();
    });
    bindAddModuleButton();
    bindExampleLayoutButton();
    </script>
	<?php
}

function printEverydayLinks(){
	global $board;
    ?>
    <div class="everyday-links">
        <div class="btn-group" role="group" aria-label="...">
            <?php
            // 그날그날: 택배, 선도, 잔반
            $i=0;
            foreach(array("everyday_parcel"=>"택배", "everyday_guidance"=>"선도", /*"everyday_honjung"=>"혼정", */"leftover"=>"잔반") as $k=>$v){
                $cat=$board->getCategory(false,$k);
                $a=$board->getArticleList(array($cat['n_id']), false, false, 0, 1);
                if(count($a)==0){
                    echo "<a class='btn btn-default' href='/board/$k' id='nav_everyday' style='color:gray'>$v 없음</a>";
                }else{
                    $a=$a[0];
                    $bold=(time()-$a['n_writedate']<43200)?"font-weight:bold;":"";
                    echo "<a class='btn btn-default' role='button' style='color: black' href=\"/board/$k/view/{$a['n_id']}\">{$v} <span style=\"$bold\">(".date("m월 d일", $a['n_writedate']).")</span></a>";
                }
            }
            ?>
        </div>
        <div class="btn-group" role="group" aria-label="...">
            <a class='btn btn-default' role='button' style='color: black' href="/board/department_environment">환경부</a>
            <a class='btn btn-default' role='button' style='color: black' href="/board/student_mpt">MPT</a>
            <a class='btn btn-default' role='button' style='color: black' href="/board/student_ambassador">대외홍보단</a>
            <a class='btn btn-default' role='button' style='color: black' href="/util/lectureroom">공강신청</a>
        </div>
    </div>
    <?php
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
