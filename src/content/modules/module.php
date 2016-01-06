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
            <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
                <a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
                <a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
                <a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
            </div>
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
    }
}

function moduleContent($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
    switch($module_name) {
        case 'important':
            articleList($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"), true,true,true,true);
            break;
        case 'birthday':
            include("modules/birthday.php");
            break;
        case 'menu':
            include("modules/menu.php");
            break;
        case 'kmlaboard':
            $dat="";
            if(file_exists("data/kmlaboard.txt") && filesize("data/kmlaboard.txt")>0){
                $dat=file_get_contents("data/kmlaboard.txt");
            }
            filterContent(nl2br(strip_tags($dat,"<b><big><small><i><u><strong><strike><a><font><img><q><s><sub><sup>")));
            break;
    }
}

function getModule($module_name, $options) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $board;
    ?>
    <div class="grid-stack-item"
        <?php
        echo 'data-gs-x="',getOrDefault($options['x'], 0),'" data-gs-y="',getOrDefault($options['y'], 0),'"';
        echo 'data-gs-width="',getOrDefault($options['w'], 1),'" data-gs-height="',getOrDefault($options['h'], 1),'"';
        ?>
            ><div class="grid-stack-item-content">
                <div class="main-block">
                    <div class="main-block-title">
                        <?php
                        moduleTitle($module_name, $options);
                        ?>
                    </div>

                    <div class="main-block-content">
                        <?php
                        moduleContent($module_name, $options);
                        ?>
                    </div>
                </div>
            </div>
    </div>
<?php
}
?>
