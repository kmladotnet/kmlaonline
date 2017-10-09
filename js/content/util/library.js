'use strict';

var app = angular.module("libApp", ['ui.bootstrap', 'ngSanitize']);

app.controller("libCtrl", function($scope, $http){

    $scope.bookFetch = function(query) {
        $http({
            method: "GET",
            url: "/proc/util/library_api?query=" + query,
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.testResult = response.data;
            $scope.bookList = response.data.items;
            console.log($scope.bookList);
        }, function myError(response){
            $scope.testResult = response.data || 'Request failed';
            $scope.status = response.statusText;
        });
    }

    $scope.bookDesc = function(book){
        console.log("<p>" + book.title + "</p>"
                + "<p>" + book.author + " 지음 | " + book.publisher + " | " + book.pubdate + "</p>");
        return "<p>" + book.title + "</p>"
                + "<p>" + book.author + " 지음 | " + book.publisher + " | " + book.pubdate + "</p>";
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