<?php
redirectLoginIfRequired();
$title = "선도부 게시판(beta) - " . $title;

function printContent(){
    global $me, $member;
    $me=array_merge($me, $member->getAdditionalData($me['n_id']));

    ?>
    <h1>등급 계산기</h1>
    <div class="table" ng-app="guidance" ng-controller="guideCtrl">
    </div>
    <?php
}
?>