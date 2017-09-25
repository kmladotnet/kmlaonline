var app = angular.module("teacherApp", []);

app.controller("teacherCtrl", function($scope){
    $scope.page = "../template/main.html";

    $scope.changePage = function(page){
        switch(page){
            case 'main':
                $scope.page = "../template/main.html";
                break;
            case 'bbq':
                $scope.page = '../template/bbq.html';
                break;
        }
    }
});

app.controller("bbqCtrl", function($scope){
    $scope.bbqRequestedList = [{title: '12학년 11반', student_list: '김현재|이영재|최우석', date: '2017-09-25', status: 300}]
});