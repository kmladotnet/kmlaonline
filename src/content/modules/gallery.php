<?php
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
            <?php foreach($att as $v){ if($i++>=24) break; ?>
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
?>
