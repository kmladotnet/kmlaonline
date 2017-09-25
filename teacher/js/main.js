var app = angular.module("teacherApp", []);

app.controller("teacherCtrl", function($scope, $rootScope){
    $scope.page = "/teacher/template/main.html";

    $scope.changePage = function(page){
        switch(page){
            case 'main':
                $scope.page = "/teacher/template/main.html";
                break;
            case 'bbq':
                $scope.page = '/teacher/template/bbq.html';
                $rootScope.$broadcast("bbqSelected", {
                    page: 'bbq'
                });
                break;
        }
    }
});

app.controller("bbqCtrl", function($scope, $rootScope){
    $scope.bbqRequestedList = [{title: '12학년 11반', rep_student: '이영재', student_list: '김현재|이영재|최우석', date: '2017-09-25', status: 300},
                                {title: '12학년 9반', rep_student: '최우석', student_list: '김현재|이영재|최우석', date: '2017-09-25', status: 300}];
    $scope.bbqAcceptedList = [{title: '12학년 10반', rep_student: '김현재', student_list: '김현재|이영재|최우석', date: '2017-09-25', status: 300}];
    $scope.init = function(){
        $scope.config_calender();
    }

    $scope.config_calender = function(){

        $scope.hourArray = [];
        $scope.minArray = [];

        for(var j = 9; j < 21; j++){
            $scope.hourArray.push({ name: (j < 12 ? "오전 " : "오후 ") + (j == 12 ? 12 : j % 12) + "시", value: j});
        }

        for(var i = 0; i < 6; i++){
            $scope.minArray.push({ name: i + "0분", value: i * 10});
        }

        var now = new Date();

        var now_year = now.getFullYear();
        var now_month = now.getMonth(); // getMonth() returns month(0 - 11)

        $scope.numberOfDays = new Date(now_year, now_month + 1, 0).getDate();
        $scope.firstDay = new Date(now_year, now_month, 1).getDay();
        //$scope.numberOfWeeks = 1 + ($scope.numberOfDays - (7 - $scope.firstDay) - 1) / 7 + 1;
        $scope.calender = [];

        for(var i = -$scope.firstDay, j = 0; i < $scope.numberOfDays; i++, j++){
            if(typeof $scope.calender[Math.floor(j / 7)] === 'undefined') $scope.calender[Math.floor(j / 7)] = [];
            if(i >= 0) $scope.calender[Math.floor(j / 7)][j % 7] = i + 1;
            else $scope.calender[Math.floor(j / 7)][j % 7] = "";
        }
    };
});