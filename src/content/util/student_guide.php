<?php
redirectLoginIfRequired();
$title = "선도부 게시판(beta) - " . $title;

function printContent(){
    global $me, $member;
    $me=array_merge($me, $member->getAdditionalData($me['n_id']));

    ?>
    <h1>선도부 게시판</h1>
    <div class="table" ng-app="myapp" ng-controller="guideCtrl">
        <form name="newArticle">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-5 col-sm-5">학생 이름</th>
                        <th class="col-md-2 col-sm-3">기소 일자</th>
                        <th class="col-md-2 col-sm-1">기소자</th>
                        <th class="col-md-3 col-sm-3">기소 항목</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <!--ui-select multiple ng-model="accusedSelect.selectedPeople" theme="bootstrap" ng-disabled="disabled" sortable="true" close-on-select="false">
                                <ui-select-match placeholder="기소할 학생을 선택해주세요...">{{$item.grade}}-{{$item.name}}</ui-select-match>
                                <ui-select-choices repeat="accused in (accusedArray | filter: $select.search) track by accused.student_id">
                                    <div ng-bind-html="accused.name | highlight: $select.search"></div>
                                    <small>
                                        {{accused.grade}}학년 {{accused.name}}
                                    </small>
                                </ui-select-choices>
                            </ui-select-->
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($me['s_name']) ?>" ng-disabled="point_disabled">
                        </td>
                        <td>
                            <!--div class="input-group">
                                <input type="text" class="form-control" uib-datepicker-popup="yyyy-MM-dd" ng-model="accused_date2" is-open="popup" datepicker-options="dateOptions" ng-required="true" close-text="Close">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" ng-click="openCalender()">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </button>
                                </span>
                            </div-->
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($me['s_name']) ?>" ng-disabled="point_disabled">
                        </td>
                        <td><input type="text" class="form-control" value="<?php echo htmlspecialchars($me['s_name']) ?>" ng-disabled="point_disabled"></td>
                        <td><input type="text" class="form-control" value="Late for School" ng-disabled="point_disabled"></td>
                    </tr>
                </tbody>
            </table>
            <p>{{status2}}</p>
            <p>{{data2}}</p>
        </form>
    </div>
    <?php
}
?>