var app = angular.module("kmla_court", []);

app.controller("courtCtrl", function($scope, $http){
        var articleList = [];

        $scope.articleList = articleList;

        $scope.method = 'GET';
        $scope.url = '/test/users.json';

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

        $scope.updateModel = function(method, url) {
            $scope.method = method;
            $scope.url = url;
        };
});