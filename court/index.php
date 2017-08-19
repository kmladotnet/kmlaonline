<!DOCTYPE html>
<html ng-app="courtApp">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>KMLA Court Application</title>

    <link rel="stylesheet" type="text/css" href="src/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="src/dashboard.css">
    <link rel="stylesheet" type="text/css" href="src/toaster.css">
</head>
<body ng-cloak="">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">KMLA Court</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span>로그인</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" sytle="margin-top:20px;">
        <div data-ng-view="" id="ng-view" class="slide-animation"></div>
    </div>
</body>
<toaster-container toaster-option="{'time-out': 3000}"></toaster-container>
<script src="src/angular.js"></script>
<script src="src/angular-route.js"></script>
<script src="src/angular-animate.js" ></script>
<script src="src/toaster.js"></script>
<script src="app/app.js"></script>
<script src="app/_data.js"></script>
<script src="app/directives.js"></script>
<script src="app/authCtrl.js"></script>
</html>