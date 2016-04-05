<?php
require_once("article-list.php");
function moduleTitle($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board, $curYear, $curMonth, $curDay;
    switch($module_name) {
        case 'important':
            ?>
            꼭 보세요
            <button class="btn btn-link" style="padding:0;vertical-align:top">
                <a href="/util/important">
                신청목록 보기
                <?php
                $res=$mysqli->query("SELECT count(*) FROM kmlaonline_important_notices_table WHERE n_state=0")->fetch_array();
                if($res[0]>0) echo " ({$res[0]})";
                ?>
                </a>
            </button>
            <?php
            break;
        case 'birthday':
            ?> <a href="/util/schedule?<?php echo "year=$curYear&amp;month=$curMonth&amp;mode=normal"?>">
                생일
                <?php
                foreach($member->listMembersBirth(date("n"), date("j")) as $val){
                    if($val['n_id'] === $me['n_id']) {
                        echo " 축하해요!";
                        break;
                    }
                }
                ?>
            </a><?php
            break;
        case 'menu':
            ?>
            <a href="/util/schedule?<?php echo "year=$curYear&amp;month=$curMonth&amp;mode=food:0"?>">식단</a>
            <?php
            break;
        case 'kmlaboard':
            ?>
            큼라보드
            <?php if(isUserPermitted($me['n_id'], "kmlaboard_changer")){ ?>
                <button class="btn btn-link" style="padding:0;vertical-align:top">
                    <a href="/util/kmlaboard">
                    (수정하기)
                    </a>
                </button>
            <?php }
            break;
        case 'article-list':
            $one_cat = count($options['cat']) === 1;
            $cat = $one_cat ? $board->getCategory($options['cat'][0]) : null;
            if(!array_key_exists('title', $options) || $options['title'] === '') {
                if($one_cat) {
                    $options['title'] = $cat['s_name'];
                }
                else {
                    $options['title'] = '여러가지';
                }
            }
            ?>
            <a href="<?php echo '/board/'.($one_cat ? $cat['s_id'] : urlencode(json_encode(array('cat'=>$options['cat'], 'title'=>$options['title']))));?>"><?php echo htmlspecialchars($options['title']);?></a>
            <?php
            break;
        case 'gallery':
            ?>
            <a href="/board/all_gallery">갤러리</a>
            <?php
            break;
        case 'weather':
            ?>
            날씨
            <?php
            break;
        case 'minjok-news':
            ?>
            인트라넷 공지
            <?php
            break;
    }
}

function moduleContent($module_name, $options, $light = false) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board, $curYear, $curMonth, $curDay;
    switch($module_name) {
        case 'important':
            articleList($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"),!$light && $options['show-cat'], $options['show-title'], $options['show-name'], !$light && $options['show-date'], $options['num']);
            break;
        case 'menu':
            require_once("menu.php");
            printMenu($options['all-day']);
            break;
        case 'kmlaboard':
            $dat="";
            if(file_exists("data/kmlaboard.txt") && filesize("data/kmlaboard.txt")>0){
                $dat=file_get_contents("data/kmlaboard.txt");
            }
            filterContent($dat);
            break;
        case 'article-list':
            $catList = arrayToCategories($options['cat']);
            echo '<!-- ';
            print_r($catList);
            echo '-->';
            if(count($catList) > 0)
                articleList($board->getArticleList($catList, false, 0, 0, $options['num']),
                        !$light && $options['show-cat'], $options['show-title'], $options['show-name'], !$light && $options['show-date']);
                        
            break;
        case 'gallery':
        case 'birthday':
        case 'weather':
        case 'minjok-news':
            include($module_name.'.php');
            break;
    }
}

function basicModuleOptions($options) {
    ?>
    <div class="form-group">
        <label>패널 색상</label>
        <select class="selectpicker" data-width="150px" name="color">
            <?php
                $colors = array(
                    'default' => '기본값(회색)',
                    'success' => '초록',
                    'info' => '파랑',
                    'warning' => '노랑',
                    'danger' => '빨강'
                );
                foreach($colors as $k => $v) {
                    ?>
                    <option value="<?php echo $k; ?>" <?php if($options['color'] === $k) echo 'selected'; ?> >
                        <?php echo $v; ?>
                    </option>
                    <?php
                }
            ?>
        </select>
    </div>
<?php
}

function cacheCategories() {
    global $member, $me, $mysqli, $board, $curYear;
    $cat=array(
        "/^club_.*$/"=>array("동아리",array()),
        "/^department_.*$/"=>array("부서",array()),
        "/^student_.*$/"=>array("교내",array()),
        "/^(site_suggestions|login_candidates|login_approved|site_kmlacafe|site_notice)$/"=>array("큼라온라인", array()),
        "/.*?/"=>array("전체",array()),
    );
    for($i=$curYear-1995;$i>=1;$i--)
        $cat = array("/^wave{$i}_.*$/"=>array("{$i}기 게시판",array())) + $cat;
    foreach($board->getCategoryList(0,0) as $val){
        if($val['n_id']==1) continue;
        if(checkCategoryAccess($val['n_id'], "list")){
            foreach($cat as $k=>$v){
                if(preg_match($k, $val['s_id'])){
                    $cat[$k][1][]=$val;
                    break;
                }
            }
        }
    }
    if(!file_exists('/tmp/kmla/catcache')) {
        mkdir('/tmp/kmla/catcache', 0777, true);
    }
    file_put_contents("/tmp/kmla/catcache/{$me['n_id']}.txt", serialize($cat));
}

function moduleOptions($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board, $curYear, $curMonth, $curDay;
    switch($module_name) {
        case 'article-list':
        ?>
        <div class="form-group">
            <label>글 분류</label>
            <select class="selectpicker" name="cat" data-live-search="true" data-size="6" data-dropup-auto="false" data-icon-base='fa', data-tick-icon='fa-check' multiple>
                <?php
                    $cat = unserialize(file_get_contents("/tmp/kmla/catcache/{$me['n_id']}.txt"));
                    foreach(array_values($cat) as $a) {
                        if(count($a[1]) === 0) {
                            continue;
                        }
                    ?>
                        <optgroup label="<?php echo $a[0]; ?>">
                        <?php
                            foreach($a[1] as $b) {
                                ?>
                                <option value="<?php echo $b['n_id']; ?>" <?php if(in_array($b['n_id'], $options['cat'])) echo 'selected'; ?> >
                                    <?php echo $b['s_name']; ?>
                                </option>
                                <?php
                            }
                        ?>
                        </optgroup>
                    <?php
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>패널 제목</label>
            <input class="form-control" type="text" name="title" <?php
            $one_cat = count($options['cat']) === 1;
            $cat = $one_cat ? $board->getCategory($options['cat'][0]) : null;
            if(array_key_exists('title', $options) && (!$one_cat || $options['title'] !== $cat['s_name'])) {
                echo ' value="',$options['title'],'" ';
            }
            ?>placeholder="비우면 자동으로 설정됩니다." />
        </div>
        <?php
        case 'important':
            ?>
            <div class="form-group">
                <label>글 개수</label>
                <input class="form-control" type="number" name="num" value="<?php echo array_key_exists('num', $options) ? $options['num'] : 10; ?>">
            </div>
            <?php
                $shown = array(
                    'show-cat' => '분류',
                    'show-title' => '제목',
                    'show-name' => '이름',
                    'show-date' => '날짜'
                );
                foreach($shown as $k => $v) {
                    ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="<?php echo $k; ?>" <?php if($options[$k]) echo 'checked'; ?>>
                            <?php echo $v; ?> 표시
                        </label>
                    </div>
                    <?php
                }
            ?>
            <?php
            break;
        case 'menu':
            ?>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="all-day" <?php if($options["all-day"]) echo 'checked'; ?>>
                    넓은 레이아웃
                </label>
            </div>
            <?php
            break;
    }
}

function moduleContents($module_name, $options, $light = false) {
    ?>
    <div class="main-block panel panel-<?php echo $options['color'];?>">
        <div class="main-block-title panel-heading">
            <div class="btn-group main-block-button-group" role="group">
                <?php if(!$light) { ?>
                    <button class="main-block-options main-block-button main-block-hidden btn btn-default" type="button" data-toggle="button" onclick="toggleOptions(!$(this).hasClass('active'), $(this));">
                        <i class="fa fa-cog"></i>
                    </button>
                <?php } ?>
                <button class="btn btn-default main-block-button main-block-reload" type="button" style="border-radius: 12px; width: 24px;">
                    <i class="fa fa-refresh"></i>
                </button>
                <?php if(!$light) { ?>
                    <button class="main-block-close main-block-button main-block-hidden btn btn-default" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                <?php } ?>
            </div>
            <div class="main-block-title-content">
                <?php
                moduleTitle($module_name, $options);
                ?>
            </div>
        </div>

        <div class="panel-body main-block-body">
            <div class="main-block-content">
                <?php
                moduleContent($module_name, $options, $light);
                ?>
            </div>
            <?php if(!$light) { ?>
                <div class="main-block-options-pane">
                    <form class="main-block-options-form" onsubmit>
                        <?php
                        basicModuleOptions($options);
                        moduleOptions($module_name, $options);
                        ?>
                        <div class="main-block-options-warning">
                            레이아웃 저장 버튼을 눌러야 영구 저장됩니다.
                        </div>
                        <button type="button" class="main-block-options-submit btn btn-primary">(임시)적용</button>
                        <button type="reset" class="main-block-options-cancel btn btn-warning">취소</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}

function moduleContentsLite($module_name, $options) {
    ?>
    <div class="main-block panel panel-<?php echo $options['color'];?>">
        <div class="main-block-title panel-heading">
            <div class="btn-group main-block-button-group" role="group">
                <button class="btn btn-default main-block-button main-block-reload" type="button" style="border-radius: 12px; width: 24px;">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>
            <div class="main-block-title-content">
                <?php
                moduleTitle($module_name, $options);
                ?>
            </div>
        </div>

        <div class="panel-body main-block-body">
            <div class="main-block-content">
                <?php
                moduleContent($module_name, $options, true);
                ?>
            </div>
        </div>
    </div>
    <?php
}

function getModuleShell($module_name, $options, $x = 0, $y = 0, $w = 4, $h = 4) {
    ?>
    <div class="grid-stack-item"
        <?php
        echo ' data-gs-x="',$x,'" data-gs-y="',$y,'"';
        echo ' data-gs-width="',$w,'" data-gs-height="',$h,'"';
        echo ' data-module-name="',$module_name,'"';
        echo ' data-module-options=\'',htmlspecialchars(json_encode($options)),'\'';
        ?>>
        <div class="grid-stack-item-content"></div>
    </div>
    <?php
}

function getModule($module_name, $options, $x = 0, $y = 0, $w = 4, $h = 4, $light = false) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
    ?>
    <div class="grid-stack-item"
        <?php
        echo ' data-gs-x="',$x,'" data-gs-y="',$y,'"';
        echo ' data-gs-width="',$w,'" data-gs-height="',$h,'"';
        echo ' data-module-name="',$module_name,'"';
        echo ' data-module-options=\'',htmlspecialchars(json_encode($options)),'\'';
        ?>>
        <div class="grid-stack-item-content">
            <?php moduleContents($module_name, $options, $light); ?>
        </div>
    </div>
<?php
}

function defaultOptions($module_name) {
    $defaults = array(
        'color' => 'default',
    );

    switch($module_name) {
        case 'birthday':
            break;
        case 'menu':
            $defaults['all-day'] = false;
            break;
        case 'kmlaboard':
            break;
        case 'article-list':
            $defaults['cat'] = array(139);
        case 'important':
            $defaults['num'] = 10;
            $defaults['show-cat'] = true;
            $defaults['show-title'] = true;
            $defaults['show-name'] = true;
            $defaults['show-date'] = true;
            break;
        case 'gallery':
            break;
        case 'weather':
            break;
        case 'minjok-news':
            break;
    }
    return $defaults;
}

function defaultModule($module_name) {
    moduleContents($module_name, defaultOptions($module_name));
}

function allModules($modules, $light = false) {
    foreach($modules as $module) {
        getModule($module['name'], array_key_exists('options', $module['options'])
                  ? array_merge(defaultOptions($module['name']), $module['options']['options'])
                  : defaultOptions($module['name']),
                  $module['options']['x'], $module['options']['y'], $module['options']['w'], $module['options']['h'], $light);
    }
}

function allModuleShells($modules) {
    foreach($modules as $module) {
        getModuleShell($module['name'], array_key_exists('options', $module['options'])
                  ? array_merge(defaultOptions($module['name']), $module['options']['options'])
                  : defaultOptions($module['name']),
                  $module['options']['x'], $module['options']['y'], $module['options']['w'], $module['options']['h']);
    }
}
?>
