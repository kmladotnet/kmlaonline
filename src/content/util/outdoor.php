<?php
redirectLoginIfRequired();
$title = "외출 외박 신청서 작성 - " . $title;

function printContent(){
    ?>
    <div ng-app="outdoor" ng-controller="outdoorCtrl" ng-init="fetch()">
        <h1>외출 외박 신청서 작성</h1>
        <ng-include src="'/src/content/template/outdoor.html'"></ng-include>
    </div>
<?php
echo $_SERVER["REQUEST_URI"];
}
?>