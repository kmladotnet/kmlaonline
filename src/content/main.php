<?php
require_once('modules/module.php');
function printContent(){
	global $is_mobile;
	if($is_mobile) printContentMobile();
	else printContentPc();
}
function printContentPc(){
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board, $user, $april_fools, $april_main;
	?>

	<div style="min-height: 400px">
        <div style="padding: 3px; padding-bottom: 6px; padding-top: 6px">
            <button type="button" id="main-edit-button" class="btn btn-default" onclick="toggleLayoutEditing();"><i class="fa fa-pencil" aria-hidden="true"></i> 편집 모드 시작</button>
            <button type="button" id="main-theme-button" class="btn btn-default" onclick="toggleThemeEditing();"><i class='fa fa-wrench' aria-hidden='true'></i> 큼온 설정<?php if($april_fools) echo ' (만우절 장난을 보기 싫다면 누르세요!)'; ?></button>
            <?php printEverydayLinks(); ?>
            <div id="main-theme-pane" style="margin-top: 6px; display:none">
                <form id="theme-form">
                    <div>
                        <h3>테마 설정</h3>
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
                        <div class="form-group">
                            상단메뉴 고정:
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php if(getTheme($me)['pinmenu']) echo "active"; ?>">
                                    <input type="radio" name="pinmenu" id="pinmenu-option" autocomplete="off"
                                           <?php if(getTheme($me)['pinmenu']) echo "checked"; ?>> 고정하기
                                </label>
                                <label class="btn btn-default <?php if(!getTheme($me)['pinmenu']) echo "active"; ?>">
                                    <input type="radio" name="dontpin" id="dontpin-option" autocomplete="off"
                                           <?php if(!getTheme($me)['pinmenu']) echo "checked"; ?>> 자동으로 숨기기
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            로고 바 표시:
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php if(!getTheme($me)['hidedasan']) echo "active"; ?>">
                                    <input type="radio" name="showdasan" id="showdasan-option" autocomplete="off"
                                           <?php if(!getTheme($me)['hidedasan']) echo "checked"; ?>>
                                    표시하기
                                </label>
                                <label class="btn btn-default <?php if(getTheme($me)['hidedasan']) echo "active"; ?>">
                                    <input type="radio" name="hidedasan" id="hidedasan-option" autocomplete="off"
                                           <?php if(getTheme($me)['hidedasan']) echo "checked"; ?>>
                                    숨기기
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            voting 버튼 위치:
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php if(!getTheme($me)['voteright']) echo "active"; ?>">
                                    <input type="radio" name="voteleft" id="voteleft-option" autocomplete="off"
                                           <?php if(!getTheme($me)['voteright']) echo "checked"; ?>> 왼쪽
                                </label>
                                <label class="btn btn-default <?php if(getTheme($me)['voteright']) echo "active"; ?>">
                                    <input type="radio" name="voteright" id="voteright-option" autocomplete="off"
                                           <?php if(getTheme($me)['voteright']) echo "checked"; ?>> 오른쪽
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            애니메이션:
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php if(!getTheme($me)['noanim']) echo "active"; ?>">
                                    <input type="radio" name="enable-anim" id="anim-option" autocomplete="off"
                                           <?php if(!getTheme($me)['noanim']) echo "checked"; ?>> 활성화
                                </label>
                                <label class="btn btn-default <?php if(getTheme($me)['noanim']) echo "active"; ?>">
                                    <input type="radio" name="noanim" id="noanim-option" autocomplete="off"
                                           <?php if(getTheme($me)['noanim']) echo "checked"; ?>> 비활성화
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3>기타</h3>
                        <div class="form-group">
                            글 제목 내 특수문자/반복된 문자:
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php if(!getTheme($me)['notitlesymbols']) echo "active"; ?>">
                                    <input type="radio" name="titlesymbols" id="titlesymbols-option" autocomplete="off"
                                           <?php if(!getTheme($me)['notitlesymbols']) echo "checked"; ?>> 보이기
                                </label>
                                <label class="btn btn-default <?php if(getTheme($me)['notitlesymbols']) echo "active"; ?>">
                                    <input type="radio" name="notitlesymbols" id="notitlesymbols-option" autocomplete="off"
                                           <?php if(getTheme($me)['notitlesymbols']) echo "checked"; ?>> 숨기기
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            베타 테스트:
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php if(getTheme($me)['beta']) echo "active"; ?>">
                                    <input type="radio" name="beta" id="beta-option" autocomplete="off"
                                           <?php if(getTheme($me)['beta']) echo "checked"; ?>> 활성화
                                </label>
                                <label class="btn btn-default <?php if(!getTheme($me)['beta']) echo "active"; ?>">
                                    <input type="radio" name="nobeta" id="nobeta-option" autocomplete="off"
                                           <?php if(!getTheme($me)['beta']) echo "checked"; ?>> 비활성화
                                </label>
                            </div>
                        </div>
                        <?php if ($april_main) { ?>
                            <div class="form-group">
                                노잼 모드 (만우절 장난 해제):
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default <?php if(getTheme($me)['nojam']) echo "active"; ?>">
                                        <input type="radio" name="nojam" id="nojam-option" autocomplete="off"
                                               <?php if(getTheme($me)['nojam']) echo "checked"; ?>> 활성화
                                    </label>
                                    <label class="btn btn-default <?php if(!getTheme($me)['nojam']) echo "active"; ?>">
                                        <input type="radio" name="jam" id="jam-option" autocomplete="off"
                                               <?php if(!getTheme($me)['nojam']) echo "checked"; ?>> 비활성화
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
            <div id="main-edit-pane" style="margin-top: 6px; display:none">
                <div class="btn-group" style="margin-top: 4px">
                    <select id="add-module" class="selectpicker" data-style="btn-info" data-size="10" title="패널 추가" data-selected-text-format="static" data-width="160px">
                        <option value="important">필수 공지</option>
                        <option value="birthday">생일</option>
                        <option value="menu">식단</option>
                        <option value="kmlaboard">큼라보드</option>
                        <option value="article-list">게시판</option>
                        <option value="gallery">갤러리</option>
                        <option value="weather">날씨</option>
                        <option value="minjok-news">인트라넷 공지</option>
                        <option value="court">법정</option>
                    </select>
                </div>
                <div style="float: right;">
                    <div class="btn-group" style="margin-top: 4px">
                        <button type="button" id="backup-layout" class="btn btn-default" onclick="backupLayout();"><i class="fa fa-cloud-upload"></i> 백업</button>
                        <?php
                        if(file_exists("data/user/main_layout_backup/{$me['n_id']}.txt")) { ?>
                            <button type="button" id="restore-layout" class="btn btn-default" onclick="restoreLayout();"><i class="fa fa-cloud-download"></i> 복구</button>
                        <?php } ?>
                        <select id="example-layout" class="selectpicker" data-style="btn-default" data-width="250px" data-size="10" title="예시 레이아웃 (먼저 백업하세요!)" data-selected-text-format="static">
                            <option value="colorful">알록달록</option>
                            <option value="warrior">키보드워리어</option>
                        </select>
                    </div>
                    <div class="btn-group" style="margin-top: 4px">
                        <button type="button" class="btn btn-primary" onclick="updateModules();"><i class="fa fa-floppy-o"></i> 레이아웃 저장</button>
                    </div>
                    <div class="btn-group" style="margin-top: 4px">
                        <button type="button" class="btn btn-danger" onclick="cancelLayout();"><i class="fa fa-trash-o"></i> 모든 변경사항 취소</button>
                        <button type="button" class="btn btn-danger" onclick="resetMainLayout();"><i class="fa fa-times"></i> 기본 레이아웃으로 초기화</button>
                    </div>
                </div>
                <div style="margin-top: 6px;">
                    <i class="fa fa-exclamation-triangle"></i> 편집 모드를 종료하거나 레이아웃 저장 버튼을 누르기 전엔 레이아웃이 저장되지 않습니다.
					또한, 새로 추가된 패널의 색을 변경하려면 저장하고 새로고침해야 합니다.
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
                cacheCategories();
                allModuleShells($modules);
            ?>
        </div>
    </div>
    <!-- gridstack -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.6.1/lodash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.js"></script>
    <script src="/js/content/main.js?v=1.2"></script>

    <script type="text/javascript">
    PNotify.prototype.options.delay = 2500;
    $(function () {
        var options = {
            animate: true,
            cellHeight: 64,
            verticalMargin: 6,
            draggable: {
                handle: '.main-block-title',
            },
            resizable: {
                handles: 'se, sw'
            }
        };
        $('.grid-stack').gridstack(options);
        reloadAllModules(false);
        $('.grid-stack').data('gridstack').disable();
    });
    rebindModules(false);
    bindAddModuleButton();
    bindExampleLayoutButton();
    </script>
	<?php
}

function printEverydayLinks(){
	global $board;
    ?>
    <div class="everyday-links">
        <!--button type="button" id="main-theme-button" class="btn btn-default" onclick="location = '/util/barbeque'">바베큐 신청</button>-->
        <!--button type="button" id="main-theme-button" class="btn btn-default" onclick="location = '/util/outdoor'">외출외박 신청</button-->
        <select id="everyday-other" class="selectpicker" data-style="btn-default" title="바로가기" data-width="180px" onchange="location = this.options[this.selectedIndex].value;">
            <?php
                foreach(array("everyday_parcel" => "택배", "everyday_guidance" => "선도", "leftover" => "잔반") as $k => $v) {
                    $cat = $board->getCategory(false, $k);
                    $a = $board->getArticleList(array($cat['n_id']), false, false, 0, 1);
                    if(count($a) != 0) {
                        $a = $a[0];
                        echo "<option value=\"/board/$k/view/{$a['n_id']}\" data-content='",((time() - $a['n_writedate'] < 43200) ? '<i class="fa fa-plus-circle" aria-hidden="true"></i> ' : ''),"{$v} <span>(".date("n월 j일", $a['n_writedate']).")</span>'>{$v}</option>";
                    }
                }
                $courtPost = getLatestCourtPost();
                if($courtPost) {
                    echo "<option value='/board/student_judicial/view/{$courtPost['n_id']}' data-content='법정리스트 <span>(".date("n월 j일", $courtPost['n_writedate']).")</span>'>법정리스트</option>";
                }
            ?>

            <option data-divider="true"></option>
            <option value="/board/department_environment">환경부</option>
            <option value="/board/student_mpt">MPT</option>
            <option value="/board/student_ambassador">대외홍보단</option>
            <option value="/util/lectureroom">공강 신청</option>
            <option value="/util/karaoke">노래방 신청</option>
            <?php
			if(date(n)==2 ||date(n)==3)
               echo  "<option value='/util/donation-cloth'>기부물품 신청</option>";
            ?>
        </select>
        <select id="datasheet" class="selectpicker" data-style="btn-default" title="통합정보망" data-width="180px" onchange="dataclick();">
            <option value = "1"> 24기 행정/수업반 </option>
            <option value = "2"> 23기 행정/수업반 </option>
            <option value = "3"> 22기 행정/수업반 </option>
            <option value = "4"> 교직원 연락망 </option>
            <option value = "5"> 여학생 방배정 </option>
            <option value = "6"> 남학생 방배정 </option>
            <option value = "7"> 등급 조건표</option>
            <option data-divider="true"></option>
            <option value = "8"> 등급 계산기 </option>
            <option value = "9"> GPA 계산기 </option>
            <option value = "10"> 바베큐 신청 </option>
            <option value = "11"> 외출외박 신청 </option>
        </select>
        <script language="javascript">
            function dataclick() {
                var selectedValue = datasheet.options[datasheet.selectedIndex].value;
                switch(selectedValue){
                    case "1":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/24기.xlsx");
                    break;
                    case "2":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/23기.xlsx");
                    break;
                    case "3":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/22기.xlsx");
                    break;
                    case "4":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/교직원연락망.xlsx");
                    break;
                    case "5":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/여학생방배정.xlsx");
                    break;
                    case "6":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/남학생방배정.xlsx");
                    break;
                    case "7":
                    window.open("https://view.officeapps.live.com/op/view.aspx?src=https://kmlaonline.net/data/datasheet/등급표.xlsx");
                    break;
                    case "8":
                    location.href="https://kmlaonline.net/util/calculator.php";
                    break;
                    case "9":
                    location.href="https://kmlaonline.net/util/gpa.php";
                    break;
                    case "10":
                    location.href="https://kmlaonline.net/util/barbeque.php";
                    break;
                    case "11":
                    location.href="https://kmlaonline.net/util/outdoor.php";
                    break;
                    default:
                    alert("error");
                }
            }
            </script>
    </div>
    <?php
}
function printContentMobile(){
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
	?>

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
                allModules($modules, true);
            ?>
        </div>
    </div>
    <!-- gridstack -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.6.1/lodash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.js"></script>

    <script src="/js/content/main.js?v=1.1"></script>

    <script type="text/javascript">
    $(function () {
        var options = {
            cellHeight: 64,
            verticalMargin: 6
        };
        rebindModules(true);
        $('.grid-stack').gridstack(options);
        $('.grid-stack').data('gridstack').disable();
    });
    </script>
	<?php
}
?>
