<?php
require_once("article-list.php");
function moduleTitle($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
    switch($module_name) {
        case 'important':
            ?>
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
            <?php
            break;
        case 'birthday':
            ?> <img src="/theme/dev/birthday.png" style="width:32px;" /> 생일! <?php
            break;
        case 'menu':
            ?>
            <img src="/theme/dev/food.png" style="width:32px;" /> 식단!
            <?php
            break;
        case 'kmlaboard':
            ?>
            큼라보드
            <?php if(isUserPermitted($me['n_id'], "kmlaboard_changer")){ ?>
                <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
                    <a href="/util/kmlaboard">수정하기</a>
                </div>
            <?php }
            break;
        case 'article-list':
            $one_cat = count($options['article']['cat']) === 1;
            $cat = $board->getCategory(getOrDefault($options['article']['cat'][0], 139));
            echo htmlspecialchars(getOrDefault($options['article']['title'], $cat['s_name']));
            ?>
            <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
                <a href="<?php echo '/board/'.($one_cat ? $cat['s_id'] : urlencode(json_encode($options['article'])));?>">더보기</a>
            </div>
            <?php
            break;
        case 'gallery':
            ?>
            갤러리
            <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;"><a href="/board/all_gallery">모두 보기</a></div>
            <?php
            break;
    }
}

function moduleContent($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
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
            articleList($board->getArticleList(arrayToCategories($options['article']['cat']), false, 0, 0, 10),
                        getOrDefault($options['show-cat'], true), getOrDefault($options['show-title'], true),
                        getOrDefault($options['show-name'], true), getOrDefault($options['show-date'], true));
            break;
        case 'gallery':
            include('gallery.php');
            break;
    }
}

function moduleContents($module_name, $options) {
    ?>
    <div class="grid-stack-item-content">
        <div class="main-block">
            <div class="main-block-title">
                <button class="main-block-reload main-block-button btn btn-default" type="button">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
                <button class="main-block-close main-block-button btn btn-default" type="button">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
                <div class="main-block-title-content">
                    <?php
                    moduleTitle($module_name, $options);
                    ?>
                </div>
            </div>

            <div class="main-block-content">
                <?php
                moduleContent($module_name, $options);
                ?>
            </div>
        </div>
    </div>
    <?php
}

function getModule($module_name, $options, $x, $y, $w, $h) {
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

function allModules($modules) {
    foreach($modules as $module) {
        getModule($module['name'], $module['options']['options'], $module['options']['x'], $module['options']['y'], $module['options']['w'], $module['options']['h']);
    }
}
?>
