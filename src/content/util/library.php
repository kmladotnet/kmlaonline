<?php
redirectLoginIfRequired();
$title = "도서부 페이지 - " . $title;

function printContent(){
?>
    <div ng-app="libApp" ng-controller="libCtrl" ng-cloak>
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
                        <li><a class="navbar-brand" ng-click="">HOME</a></li>
                        <li><a ng-click="">도서 신청하기</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a ng-click="">My Page</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row">
            <h2>테스트</h2>
            <input name="test-query" ng-model="test" id="query" class="form-control">
            <button class="btn btn-info" ng-click="bookFetch(test)"></button>
            <p>status: {{status}}</p>
            <p>result: <br>{{testResult}}</p>
        </div>
    </div>
    <script type="text/javascript" src="/js/content/util/library.js"></script>
<?php }
?>