'use strict';

var app = angular.module("kmla_court", ['ui.select', 'ngSanitize', 'ui.bootstrap']);

app.filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);

      items.forEach(function(item) {
        var itemMatches = false;

        for (var i = 0; i < keys.length; i++) {
          var prop = keys[i];
          var text = props[prop].toLowerCase();
          if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
            itemMatches = true;
            break;
          }
        }

        if (itemMatches) {
          out.push(item);
        }
      });
    } else {
      // Let the output be the input untouched
      out = items;
    }

    return out;
  };
});

app.controller("courtCtrl", function($scope, $http){
        $scope.disabled = undefined;
        $scope.point_disabled = true;

        $scope.enable = function(){
            $scope.disabled = true;
        }

        $scope.disable = function(){
            $scope.disabled = true;
        }

        $scope.init = function(){
            $scope.accusedFetch();
            $scope.accuserFetch();
            $scope.articleKindFetch();
            $scope.today();
        }

        $scope.accusedSelect = {};
        $scope.accuserSelect = {};
        $scope.articleKindSelect = {};
        $scope.accusedArray = [];
        $scope.accuserArray = [];
        $scope.articleKindArray = [];

        $scope.accusedFetch = function() {
            $scope.code = null;
            $scope.response = null;
            $scope.dateFormat = 'MM-dd';
            $http({
                method: "GET",
                url: "process/getMemberList.php"
            }).then(function mySuccess(response){
                $scope.status2 = response.statusText;
                $scope.accusedArray = response.data;
            }, function myError(response){
                $scope.data2 = response.data || 'Request failed';
                $scope.status2 = response.statusText;
            });
        }

        $scope.accuserFetch = function() {
            $scope.code = null;
            $scope.response = null;

            $http({
                method: "GET",
                url: "process/getAccuserList.php"
            }).then(function mySuccess(response){
                $scope.status2 = response.statusText;
                $scope.accuserArray = response.data;
            }, function myError(response){
                $scope.data2 = response.data || 'Request failed';
                $scope.status2 = response.statusText;
            });
        };

        $scope.articleKindFetch = function() {
            $scope.code = null;
            $scope.response = null;

            $http({
                method: "GET",
                url: "process/getArticleKindList.php"
            }).then(function mySuccess(response){
                $scope.status2 = response.statusText;
                $scope.articleKindArray = response.data;
            }, function myError(response){
                $scope.data2 = response.data || 'Request failed';
                $scope.status2 = response.statusText;
            });
        };

        $scope.today = function() {
            $scope.accused_date2 = new Date();
        };

        $scope.popup = false;
        $scope.openCalender = function(){
            $scope.popup = true;
        }

        $scope.submitNewArticle = function(_grade, _name, _accuse_date, _accuser, _article, _point){
            var temp_data = {grade: _grade, name: _name, accuse_date: _accuse_date, accuser: _accuser, article: _article, point: _point};
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };

            $http({
                method: 'POST',
                url: 'process/accuse.php',
                data: temp_data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }).then(function mySuccess(response){
                $scope.status = response.data;
                $scope.accused_grade = "";
                $scope.accused_name = "";
                $scope.accuser = "";
                $scope.accused_article = "";
                $scope.accused_point = "";
            }, function myError(response){
                $scope.status = "Request failed";
            });
            return false;
        };

        $scope.test = function(){
            console.log($scope.accusedSelect);
            console.log($scope.accuserSelect);
            console.log($scope.articleKindSelect);
            console.log($scope.accused_date2);
        }

        $scope.submitListofArticle = function(){
            var temp_result = [];
            var temp_data;
            var date = $scope.dateTest();

            $scope.accusedSelect.selectedPeople.forEach(function(item){
                temp_data = {grade: item['grade'], name: item['name'], accuse_date: date, accuser: $scope.accuserSelect.selected['name'], article: $scope.articleKindSelect.selected['ak_eng'], point: ""};
                //submitNewArticle(item['grade'], item['name'], date, $scope.accuserSelect['name'], $scope.$scope.articleKindSelect['ak_eng']);
                temp_result.push(temp_data);

            });
            console.log(temp_result);
            /*
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };

            $http({
                method: 'POST',
                url: 'process/accuse.php',
                data: temp_result,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }).then(function mySuccess(response){
                $scope.status = response.data;
                $scope.accused_grade = "";
                $scope.accused_name = "";
                $scope.accuser = "";
                $scope.accused_article = "";
                $scope.accused_point = "";
            }, function myError(response){
                $scope.status = "Request failed";
            });*/
            return false;
        };

        $scope.dateTest = function(){
            var date = new Date($scope.accused_date2);
            var result = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            return result;
        }

        $scope.loadTags = function($query){
            return $http.get('process/suggest.php').then(function(response){
                var result = response.data;
                return result.filter(function(accused){
                    return accused.text.indexOf($query) != -1;
                })
            });
        };

        $scope.fetch = function() {
            $scope.code = null;
            $scope.response = null;


            $http({
                method: "GET",
                url: "/test/user.json"
            }).then(function mySuccess(response){
                $scope.status = response.statusText;
                $scope.data = response.data;
            }, function myError(response){
                $scope.data = response.data || 'Request failed';
                $scope.status = response.statusText;
            });
        };

        var articleList = [];
        $scope.articleList = articleList;
        $scope.addNewArticle = function(_grade, _name, _accuse_date, _accuser, _article, _point){
            $scope.articleList.push({grade: _grade, name: _name, accuse_date: _accuse_date, accuser: _accuser, article: _article, point: _point});
            $scope.accused_grade = "";
            $scope.accused_name = "";
            $scope.accuser = "";
            $scope.accused_article = "";
            $scope.accused_point = "";

            console.log($scope.accused_date);

        };
});

app.controller("listCtrl", function($scope, $http){
    var articleList = [];
    $scope.articleList = articleList;

    $scope.fetch = function(){
        $scope.code = null;
        $scope.response = null;

        $http({
            method: "GET",
            url: "/judicial/process/articleList.php"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.articleList = response.data;
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
        });
    };
});