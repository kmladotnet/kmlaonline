<?php
redirectLoginIfRequired();
$title = "테스트 Push Notification - " . $title;

function printContent(){
    global $me;
?>
    <div ng-app="testApp" ng-controller="testCtrl" ng-init="init()" ng-cloak>
        <h2>Test Notification</h2>
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
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row">
            <div class="col-xs-5">
                <h3>새로운 게시글</h3>
                <form name="new_request">
                    <textarea class="form-control" placeholder="내용 입력" ng-model="selected.reason"></textarea>
                    <div style="text-align: center; margin-top: 10px;">
                        <button class="btn btn-info" type="button" ng-click="submit()">신청하기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/js/content/util/test-notification.js"></script>
    <script src="/js/autobahn.js"></script>
    <script>
        var conn = new ab.Session('wss://kmlaonline.net/test/',
            function() {
                conn.subscribe('test', function(topic, data) {
                    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                    console.log('New article published to category "' + topic + '" : ' + data.title);
                });
            },
            function() {
                console.warn('WebSocket connection closed');
            },
            {'skipSubprotocolCheck': true}
        );
    </script>
<?php }
?>