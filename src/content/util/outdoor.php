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
                    <input type="checkbox" ng-model="submitted" ng-disabled="validity()">
                    수정 모드
                </label>
                <p>작성을 완료하고 수정 모드를 해제하세요. (필수 항목 * 을 모두 체크해야 인쇄할 수 있습니다.)</p>
            </div>
            <div style="text-align: center;">
                <button class="btn btn-info" ng-if="!submitted" ng-click="printOut('print_area')">인쇄</button>
            </div>
        </div>
        <ng-include src="viewFile()"></ng-include>
    </div>
<?php
}
?>