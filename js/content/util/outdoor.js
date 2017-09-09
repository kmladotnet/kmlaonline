var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};

    $scope.fetch = function(){
        $http({
            method: 'GET',
            url: '/proc/util/outdoor-basic'
        }).success(function mySuccess(response){
            $scope.info = response;
        })
    }
});