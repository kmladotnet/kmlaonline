<div style="overflow:auto;display:block;">
<?php
$minWave=date("Y")-1997;
$births=0;
foreach($member->listMembersBirth(date("n"), date("j")) as $val){
    if($val['n_id'] === $me['n_id']) {
        echo "<div>생일 축하해요!</div>";
    }
}
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
</div>
