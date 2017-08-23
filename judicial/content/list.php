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
<body ng-cloak="" ng-controller="listCtrl" ng-init="setup()">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/judicial/main">KMLA 사법</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">기소하기
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">인트라넷 기소</a></li>
                            <li><a href="#">학생회 기소</a></li>
                        </ul>
                    </li>
                    <li><a href="#">내 기소 목록</a></li>
                    <li><a href="#">이번 주 법정</a></li>
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
                    <li><a href="main">기소하기</a></li>
                    <li class="active"><a href="">이번 주 법정 <span class="sr-only">(current)</span></a></li>
                    <li><a href="#">통계</a></li>
                    <li><a href="#">Export</a></li>
                </ul>
                <ul class="nav nav-sidebar">
                    <li><a href="">Nav item</a></li>
                    <li><a href="">Nav item again</a></li>
                    <li><a href="">One more nav</a></li>
                    <li><a href="">Another nav item</a></li>
                    <li><a href="">More navigation</a></li>
                </ul>
                <ul class="nav nav-sidebar">
                    <li><a href="">Nav item again</a></li>
                    <li><a href="">One more nav</a></li>
                    <li><a href="">Another nav item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div>
                    <h2>지금까지 추가한 내용 <button type="button" class="btn btn-success pull-right" ng-click="fetch()">보기</button></h2>
                    <div class="table-responsive">
                        <table class="table table-bordered table-checkered">
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
                                <tr ng-repeat="list in articleList" ng-class"">
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && list.status < 20000" style="vertical-align: middle;">{{list.num}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow) && list.status < 20000" style="vertical-align: middle;">{{list.grade}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && list.status < 20000" style="vertical-align: middle;">{{list.name}}</td>
                                    <td ng-if="list.status < 20000">{{list.accused_date}}</td>
                                    <td ng-if="list.status < 20000">{{list.accuser}}</td>
                                    <td ng-if="list.status < 20000">{{list.article}}</td>
                                    <td ng-if="list.status < 20000">{{list.point}}</td>
                                    <td rowSpan="{{list.row_span}}" ng-if="(! list.matchPreviousRow ) && list.status < 20000" style="vertical-align: middle;">{{list.sum}}</td>
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