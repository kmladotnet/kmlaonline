var app = angular.module("bbqApp", ['ui.bootstrap', 'ngSanitize', 'ui.select']);

app.controller("bbqCtrl", function($http, $scope){

    $scope.hourArray = [];
    $scope.minArray = [];
    $scope.new_bbq = {};
    for(var j = 9; j < 21; j++){
        $scope.hourArray.push({ name: (j < 12 ? "오전 " : "오후 ") + (j == 12 ? 12 : j % 12) + "시", value: j});
    }

    for(var i = 0; i < 6; i++){
        $scope.minArray.push({ name: i + "0분", value: i * 10});
    }

    $scope.config = function(){
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
        console.log($scope.calender);

        $scope.changePage('home');
    };

    $scope.changePage = function(page){
        switch(page){
            case 'home':
                $scope.page = '/src/content/template/barbeque_home.html';
                break;
            case 'new-barbeque':
                $scope.page = '/src/content/template/barbeque_new.html';
                break;
            case 'my-barbeque':
                $scope.page = '/src/content/template/barbeque_list.html';
                break;
        }
    };

    $scope.test = function(day){
        if(day != "") alert(day + "일을 선택하셨습니다.");
    };
});