var app = angular.module("kmla_court", []);

app.controller("courtCtrl", function($scope){
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