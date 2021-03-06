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

    $scope.isLoggedIn = function(){
        $http({
            method: "GET",
            url: "/proc/util/library_logged_in"
        }).then(function mySuccess(response){
            console.log(response.data);
            alert("[도서관] 로그인 되어 있습니다.");
        }, function myError(response){
            console.log(response.data);
            if(response.data.error_code == 403){
                alert("KMLA Online에 로그인 후 이용해주세요.");
                location.href="/";
            }
        });
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
            url: "/proc/util/library_delay?request=" + book.delay_info
        }).then(function mySuccess(response){
            $scope.status2 = response.statusText;
            $scope.output2 = response.data;
            $scope.delay_error = false;
            console.log(response.data);
        }, function myError(response){
            $scope.output2 = response.data || 'Request failed';
            $scope.status2 = response.statusText;
            $scope.delay_error = true;
            console.log(response.status);
            if(response.status == 403) {
                alert("KMLA Online에서 로그아웃되었습니다. 다시 로그인하세요.");
                location.href="/";
            } else if(response.status == 423) {
                switch(parseInt(response.data.error_code)){
                    case 1:
                        alert("대출 기간 연장은 한 번만 가능합니다.");
                        break;
                    case 3:
                        alert("알 수 없는 오류가 발생하였습니다. (ERROR CODE: LIB01)");
                        break;
                    default:
                        alert("알 수 없는 오류가 발생하였습니다. (ERROR CODE: LIB02)");
                }
            }
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
            alert("성공적으로 접수되었습니다.");
            /*
            var notice = new PNotify({
                title: '도서관 책 신청',
                text: '성공적으로 접수되었습니다!',
                type: 'info',
                buttons: {
                    closer: false,
                    sticker: false
                }
            });
            notice.get().click(function() {
                notice.remove();
            }); */
            console.log("submit success");
        }, function myError(response){
            $scope.status = "Request failed";
            console.log("submit failed");
        });

        return false;
    };

});