<div style="overflow:auto;display:block;height:80px;">
<?php
$minWave=date("Y")-1997;
$births=0;
foreach($member->listMembersBirth(date("n"), date("j")) as $val){
    if($val['n_level']>=$minWave){
        $births++;
        echo "<a href='/user/view/{$val['n_id']}/{$val['s_id']}'>";
        echo "<div style=\"float:left;display:block;padding:3px;\">";
        putUserCard($val);
        echo "</div>";
        echo "</a>";
    }
}
if($births==0){
    echo "생일인 재학생이 없습니다.";
}
?>
