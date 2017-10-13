'use strict';

var app = angular.module("libApp", ['ui.bootstrap', 'ngSanitize']);

app.controller("libCtrl", function($scope, $http){

    $scope.init = function(){
        $scope.changePage('home');
    };

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
    };

    $scope.bookDesc = function(book){
        return "<p>" + book.title + "</p>"
                + "<p>" + $scope.authorRefined(book.author) + " 지음 | " + book.publisher + " | " + book.pubdate.substring(0, 4) + "</p>";
    };

    $scope.authorRefined = function(author) {
        var temp = author.split("|");
        return temp.join(", ");
    };

    $scope.login = function(pwd){
        $http({
            method: "GET",
            url: "/proc/util/library_login?pwd=" + pwd
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.output = response.data;
        }, function myError(response){
            $scope.output = response.data || 'Request failed';
            $scope.status = response.statusText;
        });
    };

    $scope.refineBookInfo = function(str){
        var tmp = str.split('|');
        return tmp.join('\n');
    }

    $scope.submenu = function(){
    };

    $scope.changePage = function(page){
        switch(page){
            case 'home':
                $scope.subpage = '/src/content/template/library_home.html';
                break;
            case 'search':
                $scope.subpage = '/src/content/template/library_search.html';
                break;
            case 'my-page':
                $scope.subpage = '/src/content/template/library_my_page.html';
                break;
        }
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