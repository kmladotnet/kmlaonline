'use strict';

var app = angular.module("libApp", ['ui.bootstrap', 'ngSanitize']);

app.controller("libCtrl", function($scope, $http){

    $scope.bookFetch = function(query) {
        $http({
            method: "GET",
            url: "https://openapi.naver.com/v1/search/book.json?query=" + query,
            headers: {
                'X-Naver-Client-Id': 'UBWiQy6YaPCYeziwL2JW',
                'X-Naver-Client-Secret': 'InvxlYEdmf'
            }
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.testResult = response.data;
        }, function myError(response){
            $scope.testResult = response.data || 'Request failed';
            $scope.status = response.statusText;
        });
    }

    $scope.changePage = function(page){

    };

    $scope.submit = function(){

        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };

        $http({
            method: 'POST',
            url: '/proc/util/barbeque_submit_new',
            data: $scope.new_bbq,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }).then(function mySuccess(response){
            $scope.new_bbq = {};
            $scope.changePage('my-barbeque');
            console.log("submit success");
        }, function myError(response){
            $scope.status = "Request failed";
            console.log("submit failed");
        });

        return false;
    };

});