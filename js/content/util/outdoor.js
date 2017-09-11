var app = angular.module("outdoor", ['ui.bootstrap', 'ngSanitize']);

app.controller("outdoorCtrl", function($http, $scope){
    $scope.info = {};
    $scope.submitted = false;
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
});