<!DOCTYPE html>
<html ng-app="kmla_court">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>온라인 사법부에 오신 것을 환영합니다.</title>


    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/ng-tags-input.css">
    <link rel="stylesheet" type="text/css" href="css/ng-tags-input.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/select.css">

    <script type="text/javascript" src="js/jquery-3.2.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.8.5/css/selectize.default.css">
    <script type="text/javascript" src="js/angular.js"></script>
    <script type="text/javascript" src="js/angular-sanitize.js"></script>
    <script type="text/javascript" src="js/ng-tags-input.js"></script>
    <script type="text/javascript" src="js/select.js"></script>
    <script type="text/javascript" src="js/court.js"></script>

    <style>
        body {
            padding: 15px;
        }

        .select2 > .select2-choice.ui-select-match {
            /* Because of the inclusion of Bootstrap */
            height: 29px;
        }

        .selectize-control > .selectize-dropdown {
            top: 36px;
        }
        /* Some additional styling to demonstrate that append-to-body helps achieve the proper z-index layering. */
        .select-box {
          background: #fff;
          position: relative;
          z-index: 1;
        }
        .alert-info.positioned {
          margin-top: 1em;
          position: relative;
          z-index: 10000; /* The select2 dropdown has a z-index of 9999 */
        }
    </style>

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
                    <li class="active"><a href="">기소하기 <span class="sr-only">(current)</span></a></li>
                    <li><a href="list">이번 주 법정</a></li>
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
                    <h1 class="page-header">새로운 항목 기소</h1>
                    <div class="table-responsive">
                        <form name="newArticle">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-1 col-sm-2">학년</th>
                                        <th class="col-md-2 col-sm-2">학생 이름</th>
                                        <th class="col-md-2 col-sm-2">기소 일자</th>
                                        <th class="col-md-2 col-sm-2">기소자</th>
                                        <th class="col-md-4 col-sm-3">기소 항목</th>
                                        <th class="col-md-1 col-sm-1">벌점</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group has-feedback" ng-class="{'has-success': newArticle.grade.$valid}">
                                                <input type="text" class="form-control" ng-model="accused_grade" name="grade" ng-required="true" ng-pattern="/1[0-2]/" ng-maxlength="2">
                                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="newArticle.grade.$valid"></span>
                                            </div>
                                        </td>
                                        <td ng-class="{'has-success': newArticle.name.$valid}">
                                            <div class="form-group has-feedback" ng-class="{'has-success': newArticle.name.$valid}">
                                                <input type="text" class="form-control" ng-model="accused_name" name="name" ng-maxlength="4" ng-minlength="2" ng-pattern="/[가-힣]/" ng-required="true">
                                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="newArticle.name.$valid"></span>
                                            </div>
                                        </td>
                                        <td><input type="date" class="form-control" ng-model="accused_date"></td>
                                        <td><input type="text" class="form-control" ng-model="accuser"></td>
                                        <td><input type="text" class="form-control" ng-model="accused_article"></td>
                                        <td><input type="text" class="form-control" ng-model="accused_point"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>{{newArticle.grade.$valid}}</p>
                            <p>{{newArticle.name.$valid}}</p>
                            <p>{{status}}</p>
                            <p>{{data}}</p>
                            <p>{{PostDataResponse}}</p>
                            <p>{{ResponseDetails}}</p>
                        </form>
                    </div>
                    <button type="button" class="btn btn-default pull-right" ng-click="submitNewArticle(accused_grade, accused_name, accused_date, accuser, accused_article, accused_point)">테스트 제출</button>
                    <button type="button" class="btn btn-info pull-right" ng-click="addNewArticle(accused_grade, accused_name, accused_date, accuser, accused_article, accused_point)">기소</button>
                    <br/>

                </div>
                <div>
                    <h1 class="page-header">새로운 항목 기소(2)</h1>
                    <div class="table">
                        <form name="newArticle">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-4 col-sm-2">학생 이름</th>
                                        <th class="col-md-2 col-sm-3">기소 일자</th>
                                        <th class="col-md-2 col-sm-2">기소자</th>
                                        <th class="col-md-3 col-sm-3">기소 항목</th>
                                        <th class="col-md-1 col-sm-2">벌점</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <tags-input type="text" ng-model="accused_name2" name="name2">
                                                <auto-complete source="loadTags($query)" min-length="0" load-on-focus="true" max-results-to-show="8" template="grade-name"></auto-complete>
                                            </tags-input>

                                            <script type="text/ng-template" id="grade-name">
                                                <span>{{data.grade}}</span>
                                                <span>{{data.text}}</span>
                                            </script>
                                        </td>
                                        <td><input type="date" class="form-control" ng-model="accused_date2"></td>
                                        <td>
                                            <ui-select ng-model="selected.value" theme="bootstrap">
                                                <ui-select-match>
                                                    <span ng-bind="$select.selected.name"></span>
                                                </ui-select-match>
                                                <ui-select-choices repeat="item in (itemArray | filter: $select.search) track by item.id">
                                                    <span ng-bind="item.name"></span>
                                                </ui-select-choices>
                                            </ui-select>
                                        </td>
                                        <td><input type="text" class="form-control" ng-model="accused_article2"></td>
                                        <td><input type="text" class="form-control" ng-model="accused_point2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <button type="button" class="btn btn-default pull-right" ng-click="dateTest()">테스트 제출</button>
                    <br/>

                </div>
                <!--div>
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
                </div-->
            </div>
        </div>
    </div>
</body>
</html>