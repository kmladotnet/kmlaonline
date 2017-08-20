var app = angular.module("kmla_court", []);

app.controller("courtCtrl", ['$scope', '$http', '$templateCache', function($scope, $http, $templateCache){
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

    $scope.fetch = function() {
        $scope.code = null;
        $scope.response = null;


        $http({
            method: 'GET',
            url: '/test/users.json'
        }).then(function(response){
            $scope.status = response.status;
            $scope.data = response.data;
        }, function(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.status;
        });
    };
}]);