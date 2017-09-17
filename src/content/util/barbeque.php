<?php
redirectLoginIfRequired();
$title = "바베큐 신청 - " . $title;

function printContent(){
    ?>
    <div ng-app="bbqApp" ng-controller="bbqCtrl" ng-init="init()" ng-cloak>
        <script type="text/ng-template" id="myModal.tmpl.html">
            <div class="modal-header" id="detail-modal-header">
                <h3>내가 참가 할 바베큐 항목 (상세보기)</h3>
            </div>

            <div class="modal-body" id="detail-modal-body">
                <p>바베큐 진행일: {{modalSelectedBBQ.date}}</p>
                <p>시간: {{modalSelectedBBQ.start_time}} ~ {{modalSelectedBBQ.finish_time}}</p>
                <p>지도 교사: {{modalSelectedBBQ.teacher_name}} 선생님</p>
                <p>참여 인원(지도 교사 제외): {{modalSelectedBBQ.student_list.split("|").length + 1}}</p>
                <p>참가 학생 명단: {{modalSelectedBBQ.student_name_list.split("|").join(", ")}}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" ng-click="close()" data-dismiss="modal">Close
                </button>
            </div>
        </script>

        <script type="text/ng-template" id="myViewModal.tmpl.html">
            <div class="modal-header" id="detail-modal-header2">
                <h3>내가 신청한 바베큐 항목 (상세보기)</h3>
            </div>

            <div class="modal-body" id="detail-modal-body2">
                <p>바베큐 진행일: {{modalSelectedBBQ.date}}</p>
                <p>시간: {{modalSelectedBBQ.start_time}} ~ {{modalSelectedBBQ.finish_time}}</p>
                <p>지도 교사: {{modalSelectedBBQ.teacher_name}} 선생님</p>
                <p>참여 인원(지도 교사 제외): {{modalSelectedBBQ.student_list.split("|").length + 1}}</p>
                <p>참가 학생 명단: {{modalSelectedBBQ.student_name_list.split("|").join(", ")}}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" ng-click="close()" data-dismiss="modal">Close
                </button>
            </div>
        </script>
        <h1>바베큐 신청</h1>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bbq-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bbq-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a class="navbar-brand" ng-click="changePage('home')">HOME</a></li>
                        <li><a ng-click="changePage('new-barbeque')">신청하기</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a ng-click="changePage('my-barbeque')">내 바베큐</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="col-xs-5">
                <table id="calender" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>일</th>
                            <th>월</th>
                            <th>화</th>
                            <th>수</th>
                            <th>목</th>
                            <th>금</th>
                            <th>토</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="week in calender">
                            <td ng-repeat="day in week track by $index" ng-click="test(day)">
                                <div>
                                    <span>{{day || ""}}</span>
                                    <div style="clear:both"></div>
                                </div>
                                <div style="width:100%; padding: 3px; text-align:center;">
                                    {{day !== 'undefined' ? "-" : ""}}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-7">
                <ng-include src="page"></ng-include>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/js/content/util/barbeque.js"></script>
<?php }
?>