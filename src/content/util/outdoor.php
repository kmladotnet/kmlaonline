<?php
redirectLoginIfRequired();
$title = "외출 외박 신청서 작성 - " . $title;

function printContent(){
    ?>
    <div ng-app="outdoor" ng-controller="outdoorCtrl" ng-init="fetch()">
        <h1>외출 외박 신청서 작성</h1>
        <div class="well">
            <div class="checkbox">
                <label>
                    <input type="checkbox" ng-model="submitted">
                    Change
                </label>
            </div>
        </div>
        <ng-include src="viewFile()"></ng-include>
    </div>
<?php
echo $_SERVER["REQUEST_URI"];
}
?>