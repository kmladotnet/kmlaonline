var app = angular.module("teacherApp", []);

app.controller("teacherCtrl", function($scope, $http){
    $scope.page = "/teacher/template/main.html";

    $scope.changePage = function(page){
        switch(page){
            case 'main':
                $scope.page = "/teacher/template/main.html";
                break;
            case 'bbq':
                $scope.page = '/teacher/template/bbq.html';
                break;
        }
    }
});

app.controller("bbqCtrl", function($scope, $http){
    $scope.bbqRequestedList = [];
    $scope.bbqAcceptedList = [];

    $scope.init = function(){
        $scope.config_calender();
        $scope.fetchRequestedList();
        $scope.fetchAcceptedList();
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

    $scope.fetchRequestedList = function(){
        $http({
            method: "GET",
            url: "/teacher/proc/getRequestedList?status=100"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.bbqRequestedList = response.data;
            console.log("success- fetchList");
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
            console.log("failed - fetchList");
        });
    };

    $scope.fetchAcceptedList = function(){
        $http({
            method: "GET",
            url: "/teacher/proc/getRequestedList?status=200"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.bbqAcceptedList = response.data;
            console.log("success- fetchList");
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
            console.log("failed - fetchList");
        });
    };

    $scope.accept = function(idx){
        console.log(idx + "is checked");
        $http({
            method: 'POST',
            url: '/teacher/proc/authBarbeque',
            data: { id: $scope.bbqRequestedList[idx].n_id, answer: "yes"},
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }).then(function mySuccess(response){
            $scope.fetchRequestedList();
            $scope.fetchAcceptedList();
            console.log("submit success");
        }, function myError(response){
            $scope.status = "Request failed";
            console.log("submit failed");
        });

        return false;
    };

    $scope.decline = function(idx){
        $http({
            method: 'POST',
            url: '/teacher/proc/authBarbeque',
            data: { id: $scope.bbqRequestedList[idx].n_id, answer: "no"},
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }).then(function mySuccess(response){
            $scope.fetchRequestedList();
            $scope.fetchAcceptedList();
            console.log("submit success");
        }, function myError(response){
            $scope.status = "Request failed";
            console.log("submit failed");
        });

        return false;
    };


});