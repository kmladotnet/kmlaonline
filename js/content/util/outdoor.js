var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize', 'ui.select']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};
    $scope.submitted = false;

    $scope.monthArray = [{ name: "1월", value: 1 }, { name: "2월", value: 2 },
                        { name: "3월", value: 3 }, { name: "4월", value: 4 },
                        { name: "5월", value: 5 }, { name: "6월", value: 6 },
                        { name: "7월", value: 7 }, { name: "8월", value: 8 },
                        { name: "9월", value: 9 }, { name: "10월", value: 10 },
                        { name: "11월", value: 11 }, { name: "12월", value: 12 }];

    $scope.dateArray = [];
    $scope.timeArray = [];

    for(var i = 0; i < 31; i++){
        $scope.dateArray.push({ name: (i + 1) + "일", value: (i + 1)});
    }

    for(var j = 0; j < 24; j++){
        $scope.dateArray.push({ name: (j < 12 ? "오전 " : "오후 ") + (j % 12) + "시", value: j});
    }

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