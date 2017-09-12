var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize', 'ui.select']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};
    $scope.submitted = false;

    $scope.monthArray = [];
    $scope.dateArray = [];
    $scope.timeArray = [];
    $scope.dayArray = ["일", "월", "화", "수", "목", "금", "토"];

    for(var k = 1; k < 13; k++){
        $scope.monthArray.push({ name: k + "월", value: k});
    }

    for(var i = 0; i < 31; i++){
        $scope.dateArray.push({ name: (i + 1) + "일", value: (i + 1)});
    }

    for(var j = 0; j < 24; j++){
        $scope.timeArray.push({ name: (j < 12 ? "오전 " : "오후 ") + (j == 12 ? 12 : j % 12) + "시", value: j});
    }

    $scope.getDay = function(month, date){
        var temp = new Date(new Date().getYear(), month, date);
        return $scope.dayArray[temp.getDay()]   ;
    }

    $scope.getValidHour = function(hour){
        return (hour == 12 ? 12 : hour % 12);
    }

    $scope.viewFile = function(){
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