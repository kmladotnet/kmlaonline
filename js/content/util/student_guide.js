var app = angular.module("guidance", ['ui.select', 'ngSanitize', 'ui.bootstrap']);

app.controller("guideCtrl", function($http, $scope){
    $scope.accusedSelect = {};
    $scope.accusedArray = [];

    $scope.accusedFetch = function() {
        $scope.code = null;
        $scope.response = null;
        $scope.dateFormat = 'MM-dd';
        $http({
            method: "GET",
            url: "/judicial/process/getMemberList.php"
        }).then(function mySuccess(response){
            $scope.status2 = response.statusText;
            $scope.accusedArray = response.data;
        }, function myError(response){
            $scope.data2 = response.data || 'Request failed';
            $scope.status2 = response.statusText;
        });
    }
});