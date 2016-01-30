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
            <button type="button" id="main-theme-button" class="btn btn-primary" onclick="toggleThemeEditing();">테마 설정</button>
            <?php printEverydayLinks(); ?>
            <div id="main-theme-pane" style="margin-top: 6px; display:none">
                <form id="theme-form">
                    <div class="form-group">
                        모서리 모양:
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default <?php if(!getTheme($me)['square']) echo "active"; ?>" style="border-bottom-left-radius:4px!important;border-top-left-radius:4px!important">
                                <input type="radio" name="round" id="round-option" autocomplete="off"
                                       <?php if(!getTheme($me)['square']) echo "checked"; ?>> 둥글둥글
                            </label>
                            <label class="btn btn-default <?php if(getTheme($me)['square']) echo "active"; ?>" style="border-radius:0">
                                <input type="radio" name="square" id="square-option" autocomplete="off"
                                       <?php if(getTheme($me)['square']) echo "checked"; ?>> 네모네모
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        음영:
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-flat <?php if(!getTheme($me)['gradients']) echo "active"; ?>">
                                <input type="radio" name="flat" id="flat-option" autocomplete="off"
                                       <?php if(!getTheme($me)['gradients']) echo "checked"; ?>> 납작납작
                            </label>
                            <label class="btn btn-gradient <?php if(getTheme($me)['gradients']) echo "active"; ?>">
                                <input type="radio" name="gradients" id="gradients-option" autocomplete="off"
                                       <?php if(getTheme($me)['gradients']) echo "checked"; ?>> 볼록볼록
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div id="main-edit-pane" style="margin-top: 6px; display:none">
                <div>
                    <i class="fa fa-exclamation-triangle"></i> 편집 모드를 종료하거나 레이아웃 저장 버튼을 누르기 전엔 레이아웃이 저장되지 않습니다.
                </div>
                <div class="btn-group" style="margin-top: 4px">
                    <select id="add-module" class="selectpicker" data-style="btn-default" data-size="10" title="패널 추가">
                        <option data-divider="true"></option>
                        <option value="important">필수 공지</option>
                        <option value="birthday">생일</option>
                        <option value="menu">식단</option>
                        <option value="kmlaboard">큼라보드</option>
                        <option value="article-list">게시판</option>
                        <option value="gallery">갤러리</option>
                        <option value="weather">날씨</option>
                        <option value="minjok-news">인트라넷 공지</option>
                    </select>
                </div>
                <div style="float: right;">
                    <div class="btn-group" style="margin-top: 4px">
                        <button type="button" class="btn btn-success" onclick="updateModules();"><i class="fa fa-floppy-o"></i> 레이아웃 저장</button>
                    </div>
                    <div class="btn-group" style="margin-top: 4px">
                        <button type="button" id="backup-layout" class="btn btn-primary" onclick="backupLayout();"><i class="fa fa-cloud-upload"></i> 백업</button>
                        <?php
                        if(file_exists("data/user/main_layout_backup/{$me['n_id']}.txt")) { ?>
                            <button type="button" id="restore-layout" class="btn btn-primary" onclick="restoreLayout();"><i class="fa fa-cloud-download"></i> 복구</button>
                        <?php } ?>
                        <select id="example-layout" class="selectpicker" data-style="btn-primary" data-width="250px" data-size="10" title="예시 레이아웃 (먼저 백업하세요!)">
                            <option data-divider="true"></option>
                            <option value="colorful">알록달록</option>
                            <option value="warrior">키보드워리어</option>
                        </select>
                    </div>
                    <div class="btn-group" style="margin-top: 4px">
                        <button type="button" class="btn btn-danger" onclick="cancelLayout();"><i class="fa fa-trash-o"></i> 모든 변경사항 취소</button>
                        <button type="button" class="btn btn-danger" onclick="resetMainLayout();"><i class="fa fa-times"></i> 초기화</button>
                    </div>
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
                    echo "<a class='btn ".((time()-$a['n_writedate']<43200) ? 'btn-primary':'btn-default')."' role='button' href=\"/board/$k/view/{$a['n_id']}\">{$v} <span>(".date("m월 d일", $a['n_writedate']).")</span></a>";
                }
            }
            ?>
        </div>
        <div class="btn-group" role="group" aria-label="...">
            <a class='btn btn-default everyday-button' role='button' href="/board/department_environment">환경부</a>
            <a class='btn btn-default everyday-button' role='button' href="/board/student_mpt">MPT</a>
            <a class='btn btn-default everyday-button' role='button' href="/board/student_ambassador">대외홍보단</a>
            <a class='btn btn-default everyday-button' role='button' href="/util/lectureroom">공강신청</a>
        </div>
    </div>
    <?php
}
function printContentMobile(){
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
	?>
	<!-- gridstack -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.3/gridstack.min.js"></script>

	<div style="padding:6px">
        <div style="padding:4px">
            <?php printEverydayLinks(); ?>
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
    $(function () {
        var options = {
            cell_height: 64,
            vertical_margin: 6
        };
        $('.grid-stack').gridstack(options);
        $('.grid-stack').data('gridstack').disable();
    });
    </script>
	<?php
}
?>
