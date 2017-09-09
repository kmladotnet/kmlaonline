var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};

    $scope.fetch = function(){
        $http({
            method: 'GET',
            url: '/proc/util/outdoor-basic'
        }).then(function mySuccess(response){
            $scope.info = response;
            $scope.status = response.statusText;
        }, function myError(response){
            $scope.status = response.statusText;
        })
    }
});