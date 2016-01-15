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
                $res=$mysqli->query("SELECT count(*) FROM kmlaonline_important_notices_table WHERE n_state=0");
                $res=$res->fetch_array();
                if($res[0]>0) echo " ({$res[0]})";
                ?>
                </a>
            </button>
            <?php
            break;
        case 'birthday':
            ?>생일<?php
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
            $cat = $board->getCategory(getOrDefault($options['cat'][0], 139));?>
            <a href="<?php echo '/board/'.($one_cat ? $cat['s_id'] : urlencode(json_encode(array('cat'=>$options['cat'], 'title'=>$options['title']))));?>"><?php echo htmlspecialchars(getOrDefault($options['title'], $cat['s_name']));?></a>
            <?php
            break;
        case 'gallery':
            ?>
            <a href="/board/all_gallery">갤러리</a>
            <?php
            break;
    }
}

function moduleContent($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board, $curYear, $curMonth, $curDay;
    switch($module_name) {
        case 'important':
            articleList($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"), true,true,true,true);
            break;
        case 'birthday':
            include("birthday.php");
            break;
        case 'menu':
            include("menu.php");
            break;
        case 'kmlaboard':
            $dat="";
            if(file_exists("data/kmlaboard.txt") && filesize("data/kmlaboard.txt")>0){
                $dat=file_get_contents("data/kmlaboard.txt");
            }
            filterContent($dat);
            break;
        case 'article-list':
            articleList($board->getArticleList(arrayToCategories($options['cat']), false, 0, 0, 10),$options['show-cat'], $options['show-title'], $options['show-name'], $options['show-date'],);
            break;
        case 'gallery':
            include('gallery.php');
            break;
    }
}

function basicModuleOptions($options) {
    ?>
    <div class="form-group">
        <label>패널 색상</label>
        <select class="selectpicker" name="color">
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

function moduleOptions($module_name, $options) {
    switch($module_name) {
        case 'article-list':
        case 'important':

            ?>
            <h2>글 목록 설정</h2>
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
    }
}

function moduleContents($module_name, $options) {
    ?>
    <div class="grid-stack-item-content">
        <div class="main-block panel panel-<?php echo $options['color'];?>">
            <div class="main-block-title panel-heading">
                <button class="main-block-close main-block-button btn btn-default" type="button">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
                <button class="main-block-reload main-block-button btn btn-default" type="button">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
                <button class="main-block-options main-block-button btn btn-default" type="button" data-toggle="button" onclick="toggleOptions(!$(this).hasClass('active'), $(this));">
                    <span class="glyphicon glyphicon-cog"></span>
                </button>
                <div class="main-block-title-content">
                    <?php
                    moduleTitle($module_name, $options);
                    ?>
                </div>
            </div>

            <div class="panel-body main-block-body">
                <div class="main-block-content">
                    <?php
                    moduleContent($module_name, $options);
                    ?>
                </div>
                <div class="main-block-options-pane">
                    <form class="main-block-options-form" onsubmit>
                        미완성...
                        <?php
                        basicModuleOptions($options);
                        moduleOptions($module_name, $options);
                        ?>
                        <button type="button" class="main-block-options-submit btn btn-default">확인</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function getModule($module_name, $options, $x = 0, $y = 0, $w = 4, $h = 4) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
    ?>
    <div class="grid-stack-item"
        <?php
        echo 'data-gs-x="',$x,'" data-gs-y="',$y,'"';
        echo 'data-gs-width="',$w,'" data-gs-height="',$h,'"';
        echo 'data-module-name="',$module_name,'"';
        echo 'data-module-options=\'',htmlspecialchars(json_encode($options)),'\'';
        ?>
            >
        <?php moduleContents($module_name, $options); ?>
    </div>
<?php
}

function defaultOptions($module_name) {
    $defaults = array(
        'color' => 'default',
    );

    switch($module_name) {
        case 'important':
            break;
        case 'birthday':
            break;
        case 'menu':
            break;
        case 'kmlaboard':
            break;
        case 'article-list':
            $defaults['cat'] = array(139);
            $defaults['show-cat'] = true;
            $defaults['show-title'] = true;
            $defaults['show-name'] = true;
            $defaults['show-date'] = true;
            break;
        case 'gallery':
            break;
    }
    return $defaults;
}

function defaultModule($module_name) {
    moduleContents($module_name, defaultOptions($module_name));
}

function allModules($modules) {
    foreach($modules as $module) {
        getModule($module['name'], array_key_exists('options', $module['options'])
                  ? array_merge(defaultOptions($module['name']), $module['options']['options'])
                  : defaultOptions($module['name']),
                  $module['options']['x'], $module['options']['y'], $module['options']['w'], $module['options']['h']);
    }
}
?>
