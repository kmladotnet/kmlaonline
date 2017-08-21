<!DOCTYPE html>
<html ng-app="kmla_court">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>온라인 사법부에 오신 것을 환영합니다.</title>


    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">

    <script type="text/javascript" src="js/jquery-3.2.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/angular.js"></script>
    <script type="text/javascript" src="js/court_list.js"></script>

</head>
<body ng-cloak="" ng-controller="courtCtrl">
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
                    <li class="active"><a href="#">이번 주 법정 <span class="sr-only">(current)</span></a></li>
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

            <div>
                <h2>지금까지 추가한 내용 <button type="button" class="btn btn-success pull-right">제출</button></h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-1">학년</th>
                                <th class="col-md-2">학생 이름</th>
                                <th class="col-md-2">기소 일자</th>
                                <th class="col-md-2">기소자</th>
                                <th class="col-md-4">기소 항목</th>
                                <th class="col-md-1">벌점</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="list in articleList">
                                <td>{{list.grade}}</td>
                                <td>{{list.name}}</td>
                                <td>{{list.accuse_date}}</td>
                                <td>{{list.accuser}}</td>
                                <td>{{list.article}}</td>
                                <td>{{list.point}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</body>
</html>