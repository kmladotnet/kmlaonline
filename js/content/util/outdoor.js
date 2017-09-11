var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};
    $scope.submitted = false;
    $scope.viewFile = function(){
        console.log("wow");
        console.log("$scope.submitted");
        return $scope.submitted ? "/src/content/template/outdoor.html" : "/src/content/template/outdoor_print.html";
    };

    $scope.fetch = function(){
        console.log("Successfully works");
        $http({
            method: 'GET',
            url: '/proc/util/outdoor-basic'
        }).then(function mySuccess(response){
            $scope.info = response.data;
            $scope.status = response.statusText;
            console.log(response);
            console.log($scope.info);
        }, function myError(response){
            $scope.status = response.statusText;
        });
    };

    $scope.printOut = function(divName){
        var printContents = document.getElementById(divName).innerHTML;
        console.log(printContents);
        var popupWin = window.open('', '_blank', 'width=300, height=300');
        popupWin.document.open();
        popupWin.document.write('<html><head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" /></head><body onload="window.print()">' + printContents + '</body></html>');
        popupWin.document.close();
    };
});