var app = angular.module("kmla_court", ['ngTagsInput', 'ngSanitize', 'ui.select']);

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

app.controller("courtCtrl", function($scope, $http, $timeout, $interval){
        var articleList = [];

        $scope.articleList = articleList;

        $scope.method = 'GET';
        $scope.url = '/test/users.json';

        $scope.accused_name2 = [];

        $scope.dateTest = function(){
            var date = new Date($scope.accused_date);
            var result = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            console.log(result);
        }

        $scope.loadTags = function($query){
            return $http.get('process/suggest.php').then(function(response){
                var result = response.data;
                return result.filter(function(accused){
                    return accused.text.indexOf($query) != -1;
                })
            });
        };

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

        $scope.addNewArticle = function(_grade, _name, _accuse_date, _accuser, _article, _point){
            $scope.articleList.push({grade: _grade, name: _name, accuse_date: _accuse_date, accuser: _accuser, article: _article, point: _point});
            $scope.accused_grade = "";
            $scope.accused_name = "";
            $scope.accuser = "";
            $scope.accused_article = "";
            $scope.accused_point = "";

            console.log($scope.accused_date);

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