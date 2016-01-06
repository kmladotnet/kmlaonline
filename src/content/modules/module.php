<?php
require_once("article-list.php");
function moduleTitle($module_name, $options) {
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
    }
}

function moduleContent($module_name, $options) {
    switch($module_name) {
        case 'important':
            articleList($mysqli->query("SELECT * FROM kmlaonline_important_notices_table WHERE n_state=1 ORDER BY n_id DESC"), true,true,true,true);
            break;
    }
}

function getModule($module_name, $options) {
    ?>
    <div class="grid-stack-item"
        <?php
        echo 'data-gs-x="',getOrDefault($options['x'], 0),'" data-gs-y="',getOrDefault($options['y'], 0),'"';
        echo 'data-gs-width="',getOrDefault($options['w'], 1),'" data-gs-height="',getOrDefault($options['h'], 1),'"';
        ?>
            <div class="grid-stack-item-content">
                <div class="main-block">
                    <div class="main-block-title">
                        moduleTitle($module_name, $options);
                    </div>

                    <div class="main-block-content">
                        moduleContent($module_name, $options);
                    </div>
                </div>
            </div>
    </div>
<?php
}
?>
