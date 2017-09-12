var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize', 'ui.select']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};
    $scope.submitted = false;

    $scope.monthArray = [];
    $scope.dateArray = [];
    $scope.timeArray = [];
    $scope.dayArray = ["일", "월", "화", "수", "목", "금", "토"];
    $scope.subjectArray = [{ name: "국어과", value: 0}, { name: "외국어과", value: 1},
        { name: "수학과", value: 2},{ name: "과학과", value: 3}, { name: "사회과", value: 4},
        { name: "예체능과", value: 5}];
    $scope.subjectHeadArray = ["권택일 tr.", "엄세용 tr.", "이준석 tr.", "박홍제 tr.", "김태완 tr.", "곽노재 tr."];

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
        return $scope.dayArray[temp.getDay()];
    }

    $scope.getValidHour = function(hour){
        return (hour == 12 ? 12 : hour % 12);
    }

    $scope.getReadableDateTime = function(month, date, hour){
        if(typeof month != 'undefined' && typeof date != 'undefined' && typeof hour != 'undefined'){
            return month + "월 " + date + "일 "
                + $scope.getDay(month, date) + "요일 "
                + $scope.getValidHour(hour) + "시 " +  (hour < 12 ? "(AM)" : "(PM)");
        } else return "날짜와 시간을 지정해주세요";

    };

    $scope.getSubject = function(){
        if($scope.info.type == "1"){
            return $scope.subjectArray[$scope.info.subject.value].name;
        } else {
            return "        과";
        }
    };

    $scope.viewFile = function(){
        return $scope.submitted ? "/src/content/template/outdoor.html" : "/src/content/template/outdoor_print.html";
    };

    $scope.headTeacher = function(subject){
        console.log(subject);
        console.log($scope.subjectHeadArray[parseInt(subject)]);
        return $scope.subjectHeadArray[subject];
    };

    $scope.getGyomoo = function(){
        if($scope.info.absent == "yes") return "김인석 tr.";
        else return "해당 없음";
    }

    $scope.getType = function(){
        switch($scope.info.type){
            case "0":
                return "오명남 tr.";
                break;
            case "1":
                return $scope.headTeacher($scope.info.subject.value);
                break;
            case "2":
                return "인수연 tr.";
                break;
            case "3":
                return "해당 없음";
        }
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
        var popupWin = window.open('', '_blank', 'width=800, height=400');
        popupWin.document.open();
        popupWin.document.write('<html><head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" /><link rel="stylesheet" href="/css/content/util/outdoor.css" media="all"></head><body onload="window.print()">' + printContents + '</body></html>');
        popupWin.document.close();
    };
});