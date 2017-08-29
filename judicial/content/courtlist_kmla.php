<!DOCTYPE html>
<html ng-app="kmla_court">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>온라인 사법부에 오신 것을 환영합니다.</title>


    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/select.css">

    <script type="text/javascript" src="js/jquery-3.2.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.8.5/css/selectize.default.css">
    <script type="text/javascript" src="js/angular.js"></script>
    <script type="text/javascript" src="js/angular-sanitize.js"></script>
    <script type="text/javascript" src="js/ui-bootstrap.js"></script>
    <script type="text/javascript" src="js/select.js"></script>
    <script type="text/javascript" src="js/court.js"></script>

</head>
<body ng-cloak="" ng-controller="kmla_listCtrl" ng-init="setup()">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="accuse">KMLA 사법</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="accuse">기소하기</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">이번 주 법정
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">큼온용</a></li>
                            <li><a href="#">좌석용</a></li>
                        </ul>
                    </li>
                    <li><a href="#">테스트</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/"><span class="glyphicon glyphicon-home"></span> kmlaonline</a></li>
                    <li><a href="/user/logout"><span class="glyphicon glyphicon-log-out"></span> 로그아웃</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li><a href="#">기소하기</a></li>
                    <li><a href="courtlist_kmla">이번 주 법정(큼온용)<span class="sr-only">(current)</span></a></li>
                    <li class="active"><a href="courtlist_seat">이번 주 법정(좌석용)</a></li>
                    <li><a href="#">통계</a></li>
                </ul>

            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div>
                    <h2>18회 법정리스트 (큼온용) - beta</h2>
                    <button class="btn btn-link" ng-click="exportToExcel('#courtList')">
                        <span class="glyphicon glyphicon-share"></span> 엑셀로 추출
                    </button>
                    <div class="table-responsive" id="courtList">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th class="col-md-1">No.</th>
                                    <th class="col-md-1">Grade</th>
                                    <th class="col-md-1">Name</th>
                                    <th class="col-md-2">Date</th>
                                    <th class="col-md-1">Accused by</th>
                                    <th class="col-md-4">Violated Article</th>
                                    <th class="col-md-1">Points</th>
                                    <th class="col-md-1">Sum</th>
                                </tr>
                            </thead>
                            <tbody align="center">
                                <tr>
                                    <th class="col-md-12 danger" colspan="8">재판결</th>
                                </tr>
                                <!--tr ng-repeat="list in articleList" class="table-striped">
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && list.status < 20000" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && list.status < 20000" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && list.status < 20000" style="vertical-align: middle;">{{list.name}}</td>
                                    <td ng-if="list.status < 20000">{{list.accused_date}}</td>
                                    <td ng-if="list.status < 20000">{{list.accuser}}</td>
                                    <td ng-if="list.status < 20000">{{list.article}}</td>
                                    <td ng-if="list.status < 20000">{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && list.status < 20000" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr-->
                                <tr ng-repeat="list in articleList_RT" class="table-striped">
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.name}}</td>
                                    <td>{{list.accused_date}}</td>
                                    <td>{{list.accuser}}</td>
                                    <td>{{list.article}}</td>
                                    <td>{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr>
                                <tr>
                                    <th class="col-md-12 warning" colspan="8">최후변론</th>
                                </tr>
                                <!--tr ng-repeat="list in articleList" ng-class"">
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && isFD(list.status)" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && isFD(list.status)" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && isFD(list.status)" style="vertical-align: middle;">{{list.name}}</td>
                                    <td ng-if="isFD(list.status)">{{list.accused_date}}</td>
                                    <td ng-if="isFD(list.status)">{{list.accuser}}</td>
                                    <td ng-if="isFD(list.status)">{{list.article}}</td>
                                    <td ng-if="isFD(list.status)">{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && isFD(list.status)" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr-->
                                <tr ng-repeat="list in articleList_FD" class="table-striped">
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.name}}</td>
                                    <td>{{list.accused_date}}</td>
                                    <td>{{list.accuser}}</td>
                                    <td>{{list.article}}</td>
                                    <td>{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr>
                                <tr>
                                    <th class="col-md-12 success" colspan="8">일반 판결</th>
                                </tr>
                                <!--tr ng-repeat="list in articleList" ng-class"">
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && isOD(list.status)" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && isOD(list.status)" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && isOD(list.status)" style="vertical-align: middle;">{{list.name}}</td>
                                    <td ng-if="isOD(list.status)">{{list.accused_date}}</td>
                                    <td ng-if="isOD(list.status)">{{list.accuser}}</td>
                                    <td ng-if="isOD(list.status)">{{list.article}}</td>
                                    <td ng-if="isOD(list.status)">{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && isOD(list.status)" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr-->
                                <tr ng-repeat="list in articleList_OD" class="table-striped">
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.name}}</td>
                                    <td>{{list.accused_date}}</td>
                                    <td>{{list.accuser}}</td>
                                    <td>{{list.article}}</td>
                                    <td>{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr>
                                <tr>
                                    <th class="col-md-12 info" colspan="8">법정 진행</th>
                                </tr>
                                <!--tr ng-repeat="list in articleList" ng-class"">
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && isCL(list.status)" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && isCL(list.status)" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && isCL(list.status)" style="vertical-align: middle;">{{list.name}}</td>
                                    <td ng-if="isCL(list.status)">{{list.accused_date}}</td>
                                    <td ng-if="isCL(list.status)">{{list.accuser}}</td>
                                    <td ng-if="isCL(list.status)">{{list.article}}</td>
                                    <td ng-if="isCL(list.status)">{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && isCL(list.status)" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr-->
                                <tr ng-repeat="list in articleList_CL" class="table-striped">
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.name}}</td>
                                    <td>{{list.accused_date}}</td>
                                    <td>{{list.accuser}}</td>
                                    <td>{{list.article}}</td>
                                    <td>{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="! list.matchPreviousRow" style="vertical-align: middle;">{{list.sum}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>{{status}}</p>
                    <p>{{data}}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>