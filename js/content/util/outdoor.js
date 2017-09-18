var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize', 'ui.select']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};
    $scope.submitted = true;

    $scope.monthArray = [];
    $scope.dateArray = [];
    $scope.timeArray = [];
    $scope.dayArray = ["일", "월", "화", "수", "목", "금", "토"];
    $scope.subjectArray = [{ name: "국어과", value: 0}, { name: "외국어과", value: 1},
        { name: "수학과", value: 2},{ name: "과학과", value: 3}, { name: "사회과", value: 4},
        { name: "예체능과", value: 5}];
    $scope.subjectHeadArray = ["권택일 tr.", "엄세용 tr.", "이준석 tr.", "박홍제 tr.", "김태완 tr.", "곽노재 tr."];

    $scope.annDefaultGroup = [
        {number: 0, text: "학생이 직접 작성해서 1일 전까지 제출 완료합니다. (원본을 교육정보실에 사본은 사감실에 제출)"},
        {number: 1, text: "결재 순서: "},
        {number: 2, text: "수업 결손 없이 외출 • 외박만을 신청할 경우 학생부장까지 날인을 받고 사감실에 제출합니다."},
        {number: 3, text: "공결, 기타결 신청 시에는 신청서에 증빙서류를 첨부합니다."},
        {number: 4, text: "토플 응시로 인한 결석은 후에 점수표를 교육정보실에 제출합니다."},
        {number: 5, text: "질병귀가는 귀교하여 보건실에 진단서를 제출합니다."},
        {number: 6, text: "학기 중에 외부의 인턴, 봉사, 실험에 참가할 경우, 2주 전까지 <학기 중 외부활동 참가 계획서>를 제출하여 허락을 받고, 본 신청서에 허락 받은 참가 계획서와 활동기관에서 발급한 증빙서류를 첨부하여 1일 전까지 제출합니다. 귀교하여 활동기관에서 발행한 확인서를 교육정보실에 제출합니다."},
        {number: 7, text: "학교차량 이용을 위해 행정실에 차량지원신청서를 제출한 학생은 학생부장까지 날인을 받고 복사본 1부를 행정실에 제출합니다."}
    ];

    $scope.announceArr = [];

    for(var k = 1; k < 13; k++){
        $scope.monthArray.push({ name: k + "월", value: k});
    }

    for(var i = 0; i < 31; i++){
        $scope.dateArray.push({ name: (i + 1) + "일", value: (i + 1)});
    }

    for(var j = 0; j < 24; j++){
        $scope.timeArray.push({ name: (j < 12 ? "오전 " : "오후 ") + (j == 12 ? 12 : j % 12) + "시", value: j});
    }

    $scope.getAnnounce = function(){
        var temp = [];
        temp.push({number: 1, text: ($scope.annDefaultGroup[1].text + $scope.getSignOrder())});
        switch($scope.info.absent){
            case "yes":
                temp.push($scope.annDefaultGroup[0]);
                temp.push($scope.annDefaultGroup[3]);
                break;
            case "no":
                temp.push($scope.annDefaultGroup[2]);
        }
        switch($scope.info.type){
            case "0":
                temp.push($scope.annDefaultGroup[5]);
                break;
            case "1":
                temp.push($scope.annDefaultGroup[4]);
                break;
        }
        temp.push($scope.annDefaultGroup[7]);
        console.log(temp);
        $scope.announceArr = temp;
        console.log($scope.announceArr);
    };

    $scope.specialActTeacher = function(){
        return "인수연 tr.";
    }

    $scope.nurseTeacher = function(){
        return "오명남 tr.";
    }

    $scope.getSignOrder = function(){
        var temp = "어드바이저 tr. -> ";
        switch($scope.info.type){
            case "0":
                temp += ($scope.nurseTeacher() + " -> ");
                break;
            case "1":
                temp += ($scope.subjectHeadArray[$scope.info.subject.value]  + " -> ");
                break;
            case "2":
                temp += ($scope.specialActTeacher() + " -> ");
                break;
        }

        temp += "김명순 tr.";

        if($scope.info.absent == "yes") temp += " -> 김인석 tr.";

        return temp;
    }

    $scope.getDay = function(month, date){
        var temp = new Date(new Date().getYear(), month, date);
        return $scope.dayArray[temp.getDay()];
    };

    $scope.getValidHour = function(hour){
        return (hour == 12 ? 12 : hour % 12);
    };

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
        if ($scope.submitted) {
            return "/src/content/template/outdoor.html"
        } else {
            $scope.getAnnounce();
            return "/src/content/template/outdoor_print.html";
        }
        //return $scope.submitted ? "/src/content/template/outdoor.html" : "/src/content/template/outdoor_print.html";
    };

    $scope.headTeacher = function(subject){
        //console.log(subject);
        //console.log($scope.subjectHeadArray[parseInt(subject)]);
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
                return $scope.subjectHeadArray[$scope.info.subject.value];
                break;
            case "2":
                return "인수연 tr.";
                break;
            case "3":
                return "해당 없음";
        }
    };

    $scope.validity = function(){
        //console.log($scope.info.type);
        if(typeof $scope.info.type == 'undefined') return true;
        if(typeof $scope.info.absent == 'undefined') return true;
        if(typeof $scope.info.date.start_month == 'undefined' ||
            typeof $scope.info.date.start_date == 'undefined' ||
            typeof $scope.info.date.start_time == 'undefined') return true;
        if(typeof $scope.info.date.finish_month == 'undefined' ||
            typeof $scope.info.date.finish_date == 'undefined' ||
            typeof $scope.info.date.finish_time == 'undefined') return true;
        return false;
    };

    $scope.fetch = function(){
        //console.log("Successfully works");
        $http({
            method: 'GET',
            url: '/proc/util/outdoor-basic'
        }).then(function mySuccess(response){
            $scope.info = response.data;
            $scope.status = response.statusText;
            //console.log(response);
            //console.log($scope.info);
        }, function myError(response){
            $scope.status = response.statusText;
        });
    };

    $scope.stat = function(){
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };

        $http({
            method: 'POST',
            url: '/proc/util/outdoor-stat',
            data: $scope.info,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }).then(function mySuccess(response){
            console.log("stat success");
        }, function myError(response){
            console.log("stat failed");
        });

        return false;
    }

    $scope.printOut = function(divName){
        $scope.stat();
        var printContents = document.getElementById(divName).innerHTML;
        var popupWin = window.open('', '_blank', 'width=800, height=400');
        popupWin.document.open();
        popupWin.document.write('<html><head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" /><link rel="stylesheet" href="/css/content/util/outdoor.css"></head><body onload="window.print()">' + printContents + '</body></html>');
        popupWin.document.close();
    };
});