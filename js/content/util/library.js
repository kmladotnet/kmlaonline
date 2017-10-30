'use strict';

var app = angular.module("libApp", ['ui.bootstrap', 'ngSanitize']);

app.controller("libCtrl", function($scope, $http){

    $scope.init = function(){
        $scope.changePage('home');
        $scope.selected = {};
        $scope.isBookSelected = false;
    };

    $scope.error = function(){
        return $scope.login_error;
    }

    $scope.removeHTMLTags = function(text){
        return text ? String(text).replace("<b>", "").replace("</b>", "") : "";
    }

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

    $scope.isAvailable = function(book){
        if(book.price !== 'undefined' && book.discount !== '') return true;
        else return false;
    }

    $scope.authorRefined = function(author) {
        var temp = author.split("|");
        return temp.join(", ");
    };

    $scope.select = function(book){
        $scope.selected.book = book;
        $scope.isBookSelected = true;
    };

    $scope.login = function(pwd){
        $http({
            method: "GET",
            url: "/proc/util/library_login?pwd=" + pwd
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.output = response.data;
            $scope.login_error = false;
        }, function myError(response){
            $scope.output = response.data || 'Request failed';
            $scope.status = response.statusText;
            $scope.login_error = true;
        });
    };

    $scope.refineBookInfo = function(str){
        return str.replace("|", "<br />");
    };

    $scope.delay = function (book) {
        $http({
            method: "GET",
            // 비밀번호 데이터 베이스에 저장해서 굳이 이렇게 안해도 되게!
            url: "/proc/util/library_delay?pwd=" + pwd + "&request=" + book.delay_info
        }).then(function mySuccess(response){
            $scope.status2 = response.statusText;
            $scope.output2 = response.data;
            $scope.delay_error = false;
            console.log(response.data);
        }, function myError(response){
            $scope.output2 = response.data || 'Request failed';
            $scope.status2 = response.statusText;
            $scope.delay_error = true;
            console.log(response.data);
        });
    };

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
            url: '/proc/util/library_submit_new',
            data: $scope.selected,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }).then(function mySuccess(response){
            $scope.new_bbq = {};
            $scope.changePage('my-page');
            console.log("submit success");
        }, function myError(response){
            $scope.status = "Request failed";
            console.log("submit failed");
        });

        return false;
    };

});